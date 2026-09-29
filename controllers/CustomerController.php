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
}