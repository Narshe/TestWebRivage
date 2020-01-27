<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

use App\DataFixtures\DiscountRuleFixtures;

class DiscountRuleControllerTest extends WebTestCase
{   
    use FixturesTrait;

    public function testUserCanSeeAllDiscountRule()
    {
        $client = static::createClient();

        $this->loadFixtures([DiscountRuleFixtures::class]);
        $crawler = $client->request('GET', '/discount/rule');

        $this->assertSelectorTextContains('table', 'product.type == \'HiFi\' and product.price >= 100');
    }

    public function testUserCanCreateNewDiscountRuleWithValidInformation()
    {   
        $discount_rule = ['product.type == \'HiFi\' and product.price >= 1000',50];

        $client = static::createClient();
        $crawler = $client->request('GET', '/discount/rule/new');

        $crawler = $client->submitForm('Enregistrer', [
            'discount_rule[rule_expression]' => $discount_rule[0],
            'discount_rule[discount_percent]' => $discount_rule[1]
        ]);

        $client->followRedirect();

        $this->assertSelectorTextContains('table', 'product.type == \'HiFi\' and product.price >= 1000');    

    }

    public function testUserCanDeleteDiscountRule()
    {
        $this->loadFixtures([DiscountRuleFixtures::class]);

        $client = static::createClient();

        $crawler = $client->request('GET', '/discount/rule');

        $this->assertEquals(3, $crawler->filter('tbody tr')->count());

        $crawler = $client->submitForm('Supprimer');
        $crawler = $client->followRedirect();

        $this->assertEquals(2, $crawler->filter('tbody tr')->count());

    }
}
