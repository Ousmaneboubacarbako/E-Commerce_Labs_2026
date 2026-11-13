<?php

require_once __DIR__ . "/../core/db_class.php";

class Cart extends Database
{
    // Check if product is already in cart
    public function isInCart($product_id, $ip)
    {
        $sql = "SELECT COUNT(*) as count FROM cart WHERE p_id = ? AND ip_add = ?";
        $result = $this->fetchOne($sql, [$product_id, $ip]);
        return $result && $result['count'] > 0;
    }

    // Add product to cart
    public function addToCart($product_id, $qty, $ip, $customer_id = null)
    {
        $sql = "INSERT INTO cart (p_id, ip_add, qty, c_id) VALUES (?, ?, ?, ?)";
        return $this->execute($sql, [$product_id, $ip, $qty, $customer_id]);
    }

    // Get all cart items for a user (by IP)
    public function getCartItems($ip)
    {
        $sql = "SELECT cart.*, 
                       products.product_title, 
                       products.product_price, 
                       products.product_image,
                       products.product_id
                FROM cart 
                JOIN products ON cart.p_id = products.product_id 
                WHERE cart.ip_add = ?";
        return $this->fetchAll($sql, [$ip]);
    }

    // Get cart item count
    public function getCartCount($ip)
    {
        $sql = "SELECT COUNT(*) as count FROM cart WHERE ip_add = ?";
        $result = $this->fetchOne($sql, [$ip]);
        return $result ? (int)$result['count'] : 0;
    }

    // Get cart total price
    public function getCartTotal($ip)
    {
        $items = $this->getCartItems($ip);
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['qty'] * $item['product_price'];
        }
        
        return $total;
    }

    // Update cart item quantity
    public function updateCartQty($product_id, $ip, $qty)
    {
        $sql = "UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?";
        return $this->execute($sql, [$qty, $product_id, $ip]);
    }

    // Remove item from cart
    public function removeFromCart($product_id, $ip)
    {
        $sql = "DELETE FROM cart WHERE p_id = ? AND ip_add = ?";
        return $this->execute($sql, [$product_id, $ip]);
    }

    // Clear entire cart
    public function clearCart($ip)
    {
        $sql = "DELETE FROM cart WHERE ip_add = ?";
        return $this->execute($sql, [$ip]);
    }

    /**
     * Process payment and create order
     * Runs in a database transaction to ensure data integrity
     * 
     * @param int $customer_id Customer ID
     * @param string $ip Customer IP address
     * @param string $reference Paystack transaction reference
     * @param float $amount Payment amount
     * @return int|false Order ID on success, false on failure
     */
    public function processPayment($customer_id, $ip, $reference, $amount)
    {
        // Get database connection
        $conn = $this->getConnection();
        
        try {
            // Start transaction
            $conn->beginTransaction();

            // 1. Generate invoice number (6-digit random number)
            $invoice_no = mt_rand(100000, 999999);
            
            // Check if invoice number already exists (rare collision)
            $checkSql = "SELECT COUNT(*) as count FROM orders WHERE invoice_no = ?";
            $result = $this->fetchOne($checkSql, [$invoice_no]);
            
            // Regenerate if collision detected
            while ($result && $result['count'] > 0) {
                $invoice_no = mt_rand(100000, 999999);
                $result = $this->fetchOne($checkSql, [$invoice_no]);
            }

            // 2. Insert into orders table
            $orderSql = "INSERT INTO orders (customer_id, invoice_no, order_date, order_status) 
                         VALUES (?, ?, NOW(), 'paid')";
            $this->execute($orderSql, [$customer_id, $invoice_no]);
            
            // Get the newly created order_id
            $order_id = $conn->lastInsertId();

            // 3. Copy cart items into orderdetails
            $cartItems = $this->getCartItems($ip);
            
            if (empty($cartItems)) {
                throw new Exception("Cart is empty");
            }

            $orderDetailsSql = "INSERT INTO orderdetails (order_id, product_id, qty) VALUES (?, ?, ?)";
            foreach ($cartItems as $item) {
                $this->execute($orderDetailsSql, [$order_id, $item['product_id'], $item['qty']]);
            }

            // 4. Insert into payment table
            $paymentSql = "INSERT INTO payment (amt, customer_id, order_id, currency, payment_date) 
                           VALUES (?, ?, ?, 'GHS', NOW())";
            $this->execute($paymentSql, [$amount, $customer_id, $order_id]);

            // 5. Clear the cart
            $this->clearCart($ip);

            // Commit transaction
            $conn->commit();

            return $order_id;

        } catch (Exception $e) {
            // Rollback on any error
            $conn->rollBack();
            error_log("Payment processing failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get order details by order ID
     * Includes customer info, order items, and product details
     * 
     * @param int $order_id Order ID
     * @return array|false Order details or false on failure
     */
    public function getOrderById($order_id)
    {
        // Get order and customer information
        $orderSql = "SELECT o.*, c.customer_name, c.customer_email, c.customer_contact, 
                            c.customer_country, c.customer_city, p.amt, p.currency, p.payment_date
                     FROM orders o
                     JOIN customer c ON o.customer_id = c.customer_id
                     JOIN payment p ON o.order_id = p.order_id
                     WHERE o.order_id = ?";
        
        $order = $this->fetchOne($orderSql, [$order_id]);
        
        if (!$order) {
            return false;
        }

        // Get order items with product details
        $itemsSql = "SELECT od.*, p.product_title, p.product_price, p.product_image
                     FROM orderdetails od
                     JOIN products p ON od.product_id = p.product_id
                     WHERE od.order_id = ?";
        
        $items = $this->fetchAll($itemsSql, [$order_id]);
        
        // Calculate total
        $total = 0;
        foreach ($items as $item) {
            $total += $item['qty'] * $item['product_price'];
        }
        
        $order['items'] = $items;
        $order['total'] = $total;
        
        return $order;
    }
}
