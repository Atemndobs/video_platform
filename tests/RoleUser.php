<?php


namespace App\Tests;


trait RoleUser
{
    private $client ;
    private $entityManager;

    protected function setUp()
    {

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'jd@symf4.loc',
            'PHP_AUTH_PW' => 'passw',
        ]);

        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');

    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leak
    }

}
