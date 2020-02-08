<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class VideoFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {

         foreach ($this->VideoData() as [$title, $path, $category_id]){
             $duration = random_int(10,300);
             $category = $manager->getRepository(Category::class)->find($category_id);

             $video = new  Video();

             $video->setTitle($title);
             $video->setPath('https://player.vimeo.com/video/'.$path);
             $video->setCategory($category);
             $video->setDuration($duration);

             $manager->persist($video);

         }

        $manager->flush();

         $this->loadLikes($manager);
         $this->loadDislikes($manager);
    }

    public function loadLikes(ObjectManager $manager)
    {
        foreach ($this->likesData() as [$video_id, $user_id]){
            $video = $manager->getRepository(Video::class)->find($video_id);
            $user = $manager->getRepository(User::class)->find($user_id);
            $video->addUsersThatlike($user);
            $manager->persist($video);
        }
        $manager->flush();
    }

    public function LoadDislikes(ObjectManager $manager)
    {
        foreach ($this->dislikesData() as [$video_id, $user_id])
        {
            $video = $manager->getRepository(Video::class)->find($video_id);
            $user = $manager->getRepository(User::class)->find($user_id);

            $video->addUsersThatDontLike($user);
            $manager->persist($video);
        }
        $manager->flush();
    }

    public function likesData()
    {
        return [
            [12, 1],
            [12, 2],
            [11, 1],
            [5, 1],
            [2, 1],
            [8, 3],
            [9, 3],
            [2, 2],
            [7, 1],
        ];
    }

    public function dislikesData()
    {
        return [
            [10, 2],
            [7, 2],
            [3, 2],
            [10, 1],
            [10, 3],
            [12, 3],
            [6, 3],
            [5, 3],
        ];
    }

    public function VideoData()
    {
        return [
            ['Movies 1',375749787,4],
            ['Movies 2',374292554,4],
            ['Movies 3',138198412,4],
            ['Movies 4',372750763,4],
            ['Movies 5',260092577,4],
            ['Movies 6',387735313,4],
            ['Movies 7',46543566,4],
            ['Movies 8',386586290,4],
            ['Movies 9',386586290,4],
            ['Movies 10',386586290,4],
            ['Movies 11',386586290,4],
            ['Movies 12',386586290,4],


            ['Family 1',385369619,18],
            ['Family 2',386831576,18],

            ['Romantic comedy 1',385179037, 20],
            ['Romantic comedy 2',385179037, 20],

            ['Romantic drama 1',385179037, 21],

            ['Toys 1',383850186,2],
            ['Toys 2',382599680,2],
            ['Toys 3',384503634,2],
            ['Toys 4',384503634,2],
            ['Toys 5',384874292,2],
        ];

    }
}
