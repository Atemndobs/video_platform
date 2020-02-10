<?php

namespace App\Tests\Controller\Admin;

use App\Tests\RoleUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerSubscriptionTest extends WebTestCase
{
    use RoleUser;


    public function testDeleteSubscription()
    {

        $crawler = $this->client->request('GET', '/admin/');
        $link = $crawler->filter('a:contains("cancel plan")')
            ->link();
        $this->client->click($link);
        $this->client->request('GET', '/video-list/category/toys,2');
        $this->assertContains('Video for <b>Members</b> only.', $this->client->getResponse()->getContent());
    }
}
