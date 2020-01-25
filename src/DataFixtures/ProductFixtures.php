<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        foreach ($this->getProducts() as $product) {
            $p = new Product();
            $p->setName($product['name']);
            $p->setPrice($product['price']);
            $p->setDiscountedPrice($product['discounted_price']);
            $p->setType($product['type']);
            $manager->persist($p);
        }

        $manager->flush();
    }

    /**
     * @return Array;
     */
    private function getProducts () {
        return [
            [
                'name' => 'Cafetière',
                'price' => 15.50,
                'discounted_price' => null,
                'type' => 'Electro-ménager'
            ],
            [
                'name' => 'Enceinte Bluetooth',
                'price' => 100.00,
                'discounted_price' => null,
                'type' => 'HiFi'   
            ],
            [
                'name' => 'Un autre test',
                'price' => 80.00,
                'discounted_price' => null,
                'type' => 'HiFi'   
            ],
            [
                'name' => 'Encore un test',
                'price' => 200.00,
                'discounted_price' => null,
                'type' => 'Livre'   
            ]
        ];
    }
}
