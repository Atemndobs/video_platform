<?php

namespace App\Tests\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerCacheTest extends WebTestCase
{
    public function testCache()
    {

        $client = self::createClient();

        $client->enableProfiler();
        $this->assertTrue(true);

        $client->request('GET', 'https://127.0.0.1:8000/video-list/category/movies,4');
        $this->assertGreaterThan(
            9,
            $client->getProfile()->getCollector('db')->getQueryCount()
        );

        $client->enableProfiler();

        $client->request('GET', 'https://127.0.0.1:8000/video-list/category/movies,4');

        $this->assertEquals(
            3,
            $client->getProfile()->getCollector('db')->getQueryCount()
        );
    }
}
