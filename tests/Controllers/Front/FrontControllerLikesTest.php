<?php

namespace App\Tests\Controller\Front;

use App\Tests\RoleUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerLikesTest extends WebTestCase
{

    use RoleUser;
    private $client;

    public function testLikes()
    {

        $this->client->request('POST','/video-list/11/like');

        $crawler = $this->client->request('GET', '/video-list/category/movies,4');
        $this->assertSame('(2)', $crawler->filter('small.text-muted.number-of-likes-11')->text());

    }

    public function TestDislike()
    {
        $this->client->request('POST','/video-list/1/dislike');

        $crawler = $this->client->request('GET', '/video-list/category/movies,4');
        $this->assertSame('(2)', $crawler->filter('small.text-muted.number-of-dislikes-1')->text());
    }

    public function testNumberOfLikedVideos1()
    {
        $this->client->request('POST','/video-list/1/like');
        $this->client->request('POST','/video-list/1/like');

        $crawler = $this->client->request('GET', '/admin/videos');
        $this->assertEquals(4, $crawler->filter('tr')->count());
    }

    public function testNumberOfLikedVideos2()
    {
        $this->client->request('POST','/video-list/9/unlike');
        $this->client->request('POST','/video-list/8/unlike');

        $crawler = $this->client->request('GET', '/admin/videos');
        $this->assertEquals(1, $crawler->filter('tr')->count());
    }


}
