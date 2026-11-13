<?php

require_once __DIR__ . "/../core/db_class.php";

class Product extends Database
{
    // Add a new brand
    public function addBrand($name)
    {
        $sql = "INSERT INTO brands (brand_name) VALUES (?)";
        return $this->execute($sql, [$name]);
    }

    // Get all brands ordered alphabetically
    public function getAllBrands()
    {
        $sql = "SELECT * FROM brands ORDER BY brand_name ASC";
        return $this->fetchAll($sql);
    }

    // Get a single brand by ID
    public function getBrandById($id)
    {
        $sql = "SELECT * FROM brands WHERE brand_id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    // Update a brand
    public function updateBrand($id, $name)
    {
        $sql = "UPDATE brands SET brand_name = ? WHERE brand_id = ?";
        return $this->execute($sql, [$name, $id]);
    }

    // Delete a brand
    public function deleteBrand($id)
    {
        $sql = "DELETE FROM brands WHERE brand_id = ?";
        return $this->execute($sql, [$id]);
    }

    // Add a new category
    public function addCategory($name)
    {
        $sql = "INSERT INTO categories (cat_name) VALUES (?)";
        return $this->execute($sql, [$name]);
    }

    // Get all categories ordered alphabetically
    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories ORDER BY cat_name ASC";
        return $this->fetchAll($sql);
    }

    // Get a single category by ID
    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE cat_id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    // Update a category
    public function updateCategory($id, $name)
    {
        $sql = "UPDATE categories SET cat_name = ? WHERE cat_id = ?";
        return $this->execute($sql, [$name, $id]);
    }

    // Delete a category
    public function deleteCategory($id)
    {
        $sql = "DELETE FROM categories WHERE cat_id = ?";
        return $this->execute($sql, [$id]);
    }

    // Add a new product
    public function addProduct($cat, $brand, $title, $price, $desc, $image_filename, $keywords)
    {
        $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->execute($sql, [$cat, $brand, $title, $price, $desc, $image_filename, $keywords]);
    }

    // Get all products with category and brand names
    public function getAllProducts()
    {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                ORDER BY p.product_id DESC";
        return $this->fetchAll($sql);
    }

    // Get a single product by ID with JOIN
    public function getProductById($id)
    {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    // Update a product
    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image_filename, $keywords)
    {
        $sql = "UPDATE products 
                SET product_cat = ?, product_brand = ?, product_title = ?, product_price = ?, 
                    product_desc = ?, product_image = ?, product_keywords = ?
                WHERE product_id = ?";
        return $this->execute($sql, [$cat, $brand, $title, $price, $desc, $image_filename, $keywords, $id]);
    }

    // Delete a product
    public function deleteProduct($id)
    {
        $sql = "DELETE FROM products WHERE product_id = ?";
        return $this->execute($sql, [$id]);
    }

    // Get featured products (random selection)
    public function getFeaturedProducts($limit = 6)
    {
        // Cast to int to prevent SQL injection
        $limit = (int)$limit;
        
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                ORDER BY RAND()
                LIMIT " . $limit;
        return $this->fetchAll($sql);
    }

    // Get products by category
    public function getProductsByCategory($cat_id)
    {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_cat = ?
                ORDER BY p.product_id DESC";
        return $this->fetchAll($sql, [$cat_id]);
    }

    // Get products by brand
    public function getProductsByBrand($brand_id)
    {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_brand = ?
                ORDER BY p.product_id DESC";
        return $this->fetchAll($sql, [$brand_id]);
    }

    // Search products
    public function searchProducts($query)
    {
        $searchTerm = '%' . $query . '%';
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_title LIKE ? OR p.product_keywords LIKE ?
                ORDER BY p.product_id DESC";
        return $this->fetchAll($sql, [$searchTerm, $searchTerm]);
    }
}
