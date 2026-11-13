<?php

require_once __DIR__ . "/../classes/ProductClass.php";

class ProductController
{
    private $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    // Add a new brand
    public function addBrand($name)
    {
        return $this->product->addBrand($name);
    }

    // Get all brands
    public function getAllBrands()
    {
        return $this->product->getAllBrands();
    }

    // Get a single brand by ID
    public function getBrandById($id)
    {
        return $this->product->getBrandById($id);
    }

    // Update a brand
    public function updateBrand($id, $name)
    {
        return $this->product->updateBrand($id, $name);
    }

    // Delete a brand
    public function deleteBrand($id)
    {
        return $this->product->deleteBrand($id);
    }

    // Add a new category
    public function addCategory($name)
    {
        return $this->product->addCategory($name);
    }

    // Get all categories
    public function getAllCategories()
    {
        return $this->product->getAllCategories();
    }

    // Get a single category by ID
    public function getCategoryById($id)
    {
        return $this->product->getCategoryById($id);
    }

    // Update a category
    public function updateCategory($id, $name)
    {
        return $this->product->updateCategory($id, $name);
    }

    // Delete a category
    public function deleteCategory($id)
    {
        return $this->product->deleteCategory($id);
    }

    // Add a new product
    public function addProduct($cat, $brand, $title, $price, $desc, $image_filename, $keywords)
    {
        return $this->product->addProduct($cat, $brand, $title, $price, $desc, $image_filename, $keywords);
    }

    // Get all products
    public function getAllProducts()
    {
        return $this->product->getAllProducts();
    }

    // Get a single product by ID
    public function getProductById($id)
    {
        return $this->product->getProductById($id);
    }

    // Update a product
    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image_filename, $keywords)
    {
        return $this->product->updateProduct($id, $cat, $brand, $title, $price, $desc, $image_filename, $keywords);
    }

    // Delete a product
    public function deleteProduct($id)
    {
        return $this->product->deleteProduct($id);
    }

    // Get featured products
    public function getFeaturedProducts($limit = 6)
    {
        return $this->product->getFeaturedProducts($limit);
    }

    // Get products by category
    public function getProductsByCategory($cat_id)
    {
        return $this->product->getProductsByCategory($cat_id);
    }

    // Get products by brand
    public function getProductsByBrand($brand_id)
    {
        return $this->product->getProductsByBrand($brand_id);
    }

    // Search products
    public function searchProducts($query)
    {
        return $this->product->searchProducts($query);
    }
}
