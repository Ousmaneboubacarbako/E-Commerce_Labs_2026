<?php

require_once __DIR__ . "/../classes/CartClass.php";

class CartController
{
    private $cart;

    public function __construct()
    {
        $this->cart = new Cart();
    }

    // Check if product is in cart
    public function isInCart($product_id, $ip)
    {
        return $this->cart->isInCart($product_id, $ip);
    }

    // Add item to cart
    public function addToCart($product_id, $qty, $ip, $customer_id = null)
    {
        return $this->cart->addToCart($product_id, $qty, $ip, $customer_id);
    }

    // Get all cart items
    public function getCartItems($ip)
    {
        return $this->cart->getCartItems($ip);
    }

    // Get cart count
    public function getCartCount($ip)
    {
        return $this->cart->getCartCount($ip);
    }

    // Get cart total
    public function getCartTotal($ip)
    {
        return $this->cart->getCartTotal($ip);
    }

    // Update cart quantity
    public function updateCartQty($product_id, $ip, $qty)
    {
        return $this->cart->updateCartQty($product_id, $ip, $qty);
    }

    // Remove from cart
    public function removeFromCart($product_id, $ip)
    {
        return $this->cart->removeFromCart($product_id, $ip);
    }

    // Clear cart
    public function clearCart($ip)
    {
        return $this->cart->clearCart($ip);
    }

    // Process payment and create order
    public function processPayment($customer_id, $ip, $reference, $amount)
    {
        return $this->cart->processPayment($customer_id, $ip, $reference, $amount);
    }

    // Get order details by ID
    public function getOrderById($order_id)
    {
        return $this->cart->getOrderById($order_id);
    }
}
