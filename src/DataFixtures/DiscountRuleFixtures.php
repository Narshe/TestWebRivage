<?php

namespace App\DataFixtures;

use App\Entity\DiscountRule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DiscountRuleFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getDiscountRules() as $discountRule) {
            $dr = new DiscountRule();
            $dr->setRuleExpression($discountRule['expression']);
            $dr->setDiscountPercent($discountRule['discount_percent']);
            $manager->persist($dr);
        }

        $manager->flush();
    }

    /**
     * @return Array
     */
    private function getDiscountRules () {
        return [
            [
                'expression' => 'product.type == \'HiFi\' and product.price >= 100',
                'discount_percent' => 20,
            ],
            [
                'expression' => 'product.type == \'Electro-ménager\' and product.price < 100',
                'discount_percent' => 10,
            ], 
            [
                'expression' => 'product.type == \'HiFi\'',
                'discount_percent' => 10,
            ]
        ];
    }

}
