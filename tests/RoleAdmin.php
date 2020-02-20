<?php


namespace App\Tests;


trait RoleAdmin
{
    private $client ;
    private $entityManager;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();

        $container = self::$kernel->getContainer();

        $container->self::$container->get('App\Utils\Interfaces\CacheInterface');
        $this->cache->clear();

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'jw@symf4.loc',
            'PHP_AUTH_PW' => 'passw',
        ]);

        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');

    }

    public function tearDown(): void
    {

        parent::tearDown();
        $this->cache->clear();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leak
    }

}
