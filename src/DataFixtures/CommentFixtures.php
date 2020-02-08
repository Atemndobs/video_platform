<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
//use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->CommentData() as [$content, $user, $video, $created_at])
        {
            $comment = new Comment();
            $user = $manager->getRepository(User::class)->find($user);
            $video = $manager->getRepository(Video::class)->find($video);


            $comment->setContent($content);
            $comment->setUser($user);
            $comment->setVideo($video);

            $comment->setCreateAtForFixtures(new \DateTime(($created_at)));

            $manager->persist($comment);
        }
        $manager->flush();
    }

    private function CommentData()
    {
        return [
          ['Comment Pys', 1,10,'2018-10-08 12:34:45'],
          ['And now  a comment for Videos here is not insane enought but lets see 2', 2,10,'2018-10-10 06:34:45'],
          ['comment for Videos here is not insane enought but lets see 3', 3,11,'2018-09-11 11:34:45'],
          ['here is not insane enought but lets see  4', 2,12,'2019-10-05 10:34:45'],
          ['now  a comment for Videos here is not insane enought but l Comment Pys 6', 1,12,'2018-1-07 08:34:45'],
          ['Comment Videos here is not insane enought Pys 9', 3,12,'2018-2-07 09:34:45'],
          ['Comment Pys 8', 3,12,'2018-3-07 11:34:45'],
          ['Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus
                odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate
                fringilla. Donec lacinia congue felis in faucibus.', 3,10,'2018-3-07 11:34:45'],
        ];
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
