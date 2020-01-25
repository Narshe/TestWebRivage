<?php

namespace App\Message;

class EmailSummary
{

    /**
     * @var array $products Liste des produits qui ont subi une promotion
     */
    private $products;
    /**
     * @var string $message
     */
    private $adminEmail;

    /**
     * @param array $discounted_products
     * @param string $adminEmail
     */
    public function __construct(array $discounted_products = [], string $adminEmail = '')
    {
        $this->products = $discounted_products;
        $this->adminEmail = $adminEmail;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return string
     */
    public function getAdminEmail()
    {
        return $this->adminEmail;
    }
}