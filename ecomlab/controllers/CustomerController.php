<?php

require_once __DIR__ . "/../classes/CustomerClass.php";

class CustomerController
{
    private $customer;

    public function __construct()
    {
        $this->customer = new Customer();
    }

    public function login($email, $pass)
    {
        $customer = $this->customer->login($email, $pass);

        if ($customer !== false) {
            return $customer;
        }

        return [
            "error" => "Invalid email or password."
        ];
    }

    public function getCustomerById($customer_id)
    {
        return $this->customer->getCustomerById($customer_id);
    }

    public function getAllCustomers()
    {
        return $this->customer->getAllCustomers();
    }

    public function updateCustomer($customer_id, $name, $email, $country, $city, $contact, $image = null)
    {
        return $this->customer->updateCustomer($customer_id, $name, $email, $country, $city, $contact, $image);
    }

    public function updatePassword($customer_id, $new_password)
    {
        return $this->customer->updatePassword($customer_id, $new_password);
    }

    public function checkEmailExists($email, $exclude_customer_id = null)
    {
        return $this->customer->checkEmailExists($email, $exclude_customer_id);
    }
}