<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$name, $last_name, $email, $password, $api_key, $roles])
        {
             $user = new User();

             $user->setName($name)
                 ->setLastName($last_name)
                 ->setEmail($email)
                 ->setPassword($this->passwordEncoder->encodePassword($user, $password));

             $user->setVimeoApiKey($api_key);
             $user->setRoles($roles);
             $manager->persist($user);
        }
        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            ['John', 'Wayne', 'jw@symf4.loc', 'passw', 'hjd8dehdh', ['ROLE_ADMIN']],
            ['John', 'Wayne2', 'jw@2symf4.loc', 'passw',  null, ['ROLE_ADMIN']],
            ['John', 'Doe', 'jd@symf4.loc', 'passw', null, ['ROLE_USER']],
            ['Ted', 'Bunty', 'jb@symf4.loc', 'passw', null, ['ROLE_USER']],

        ];
    }
}
