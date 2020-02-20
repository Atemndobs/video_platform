<?php

namespace App\Tests\Controllers\Admin;

use App\Tests\RoleAdmin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTranslationTest extends WebTestCase
{
    use RoleAdmin;
    public function testTranslations()
    {

        $this->client->request('GET', 'https://127.0.0.1:8000/de/admin/');

        $this->assertContains('Mein Profil', $this->client->getResponse()->getContent());
    }
}
