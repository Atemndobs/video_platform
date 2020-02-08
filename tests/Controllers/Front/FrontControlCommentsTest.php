<?php

namespace App\Tests\Controllers\Front;

use App\Tests\RoleAdmin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControlCommentsTest extends WebTestCase
{



   //use RoleUser;
    private $client;

    public function testNotLoggedInUser()
    {

        $client = static::createClient();

       //$client=  $this->client ;
        $client->followRedirects();

        $crawler = $client->request('GET', '/video-details/12');

        $form = $crawler->selectButton('Add')->form([
            'comment'=> 'Test comment'
        ]);

        $client->submit($form);
        $this->assertContains('Please sign in', $client->getResponse()->getContent());
    }



}


class FrontControlUserCommentTest extends WebTestCase
{
    use RoleAdmin;
    public function testNewCommentAndNumberOfComments()
    {

        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/video-details/19');

        $form = $crawler->selectButton('Add')->form([
            'comment'=> 'Test comment'
        ]);

        $this->client->submit($form);

        $this->assertContains('Test comment', $this->client->getResponse()->getContent());

        $crawler = $this->client->request('GET', '/video-details/category/toys,2');

        $this->assertSame('Comments (1)', $crawler->filter('a.lm-1')->text());

    }
}
