<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Video;
use App\Tests\RoleAdmin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerVideosTest extends WebTestCase
{
    use RoleAdmin;
    public function testDeleteVideo()
    {
        $this->client->request('GET', '/admin/su/delete-video/14/386831576');
        $video = $this->entityManager->getRepository(Video::class)->find(14);

        $this->assertNull($video);
    }
}
