<?php

namespace App\Tests\Controller\Front;

use App\Entity\Subscription;
use App\Tests\RoleUser;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerSubscriptionTest extends WebTestCase
{
    use RoleUser;

    /**
     * @param $url
     * @dataProvider urlsWithVideo
     */
    public function testLoggedInUserDoesNotSeeTextForNoMembers($url)
    {
        $this->client->request('GET', $url);

        $this->assertNotContains('Video for <b>Members</b> only.', $this->client->getResponse()->getContent());
    }

    /**
     * @throws \Exception
     */
    public function testExpiredSubscription()
    {
        $subscription = $this->entityManager->getRepository(Subscription::class)->find(2);
        $invalid_date = new \DateTime();
        $invalid_date->modify('-1 day');
        $subscription->setValidTo($invalid_date);

        $this->entityManager->persist($subscription);
        $this->entityManager->flush();

        $this->client->request('GET', 'video-list/category/movies,4');
        $this->assertContains('Video for <b>Members</b> only.', $this->client->getResponse()->getContent() );
    }
    public function urlsWithVideo()
    {
        yield ['/video-list/category/movies,4'];
        yield ['/search-results?query=movie'];
    }

}


class FrontControllerSubscriptionTest2 extends WebTestCase
{
    private $entityManager;

    /**
     * @param $url
     * @dataProvider urlsWithVideo
    {
     */
    public function testNotLoggedInMemberSeesTextForNoMembers($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertContains('Video for <b>Members</b> only.', $client->getResponse()->getContent());
    }

    public function urlsWithVideo()
    {
        yield ['/video-list/category/movies,4'];
        yield ['/search-results?query=movie'];
    }


    /**
     * @param $url
     * @dataProvider urlsWithVideo2
     */
    public function testNotLoggedInUserSeesVideosForNoMembers($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertContains('https://player.vimeo.com/video/289729765', $client->getResponse()->getContent());
    }

    public function urlsWithVideo2()
    {
        yield ['/video-list/category/toys,2/2'];
        yield ['/search-results?query=movie+3'];
        yield ['/video-details/16#video_comments'];

    }
}
