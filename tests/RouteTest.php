<?php

namespace Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RouteTest extends WebTestCase
{

    /**
     * @param string $url
     * @param strin $method
     * @dataProvider providePublicUrls
     */
    public function testPublicRoutes(string $url, string $method)
    {

        $client = static::createClient();

        $client->request($method, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @return array
     */
    public function providePublicUrls() 
    {
        return [
            ['/discount/rule', 'GET'], 
            ['/discount/rule/new', 'GET'],
            ['/discount/rule/new', 'POST']
        ];

    }



}