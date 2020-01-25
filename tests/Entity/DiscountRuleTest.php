<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use App\Entity\DiscountRule;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use App\DataFixtures\DiscountRuleFixtures;

class DiscountRuleTest extends KernelTestCase
{

    use FixturesTrait;

    /**
     * @return DiscountRule
     */
    private function getEntity(): DiscountRule
    {
        return (new DiscountRule)
            ->setRuleExpression("product.type == \'HiFi\' and product.price >= 1000product.type == \'HiFi\' and product.price >= 1000")
            ->setDiscountPercent(50)
        ;
    }

    /**
     * @param DiscountRule $entity
     * @param int $n
     * @param string $message
     */
    private function assertHasErrors(DiscountRule $entity, int $n = 0, string $message)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($entity);
        $this->assertCount($n, $errors, $message);
    }

    /**
     * @dataProvider provideValidationData
     */
    public function testDiscountRuleValidation($rule_expression, $discount_percent, $error_expected, $message)
    {

        $entity = $this->getEntity();
        $entity->setRuleExpression($rule_expression);
        $entity->setDiscountPercent($discount_percent);
        $this->assertHasErrors($entity, $error_expected, $message);
    }

    public function testRuleExpressionDuplication()
    {
        $this->loadFixtures([DiscountRuleFixtures::class]);

        $entity = $this->getEntity();
        $entity->setRuleExpression("product.type == \'HiFi\' and product.price >= 100");
        $entity->setDiscountPercent(50);
        $this->assertHasErrors($this->getEntity(), 1, "Entity already exists");
    }

    /**
     * @return Array
     */
    public function provideValidationData(): array
    {
        return [
            ['', 0, 2, "RuleExpression and DiscountPercent empty"],
            ['product.type == \'HiFi\' and product.price >= 1000', 30, 0, "Entity Valide"],
            ['ezadzaaz', 20, 1, "RuleExpression invalide"],
            ['product.type == \'HiFi\' and product.price >= 1000', 60, 1, "DiscountPercent > 50"],
            ['product.type == \'HiFi\' and product.price >= 1000', -1, 1, "DiscountPercent < 1"],
        ];
    }
}