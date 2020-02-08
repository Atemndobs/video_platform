<?php


namespace App\Controller\Traits;


use App\Entity\User;
use App\Entity\Video;

trait Likes
{

    public function dislikeVideo(Video $video)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $user->addDislikedVideo($video);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return 'disliked';
    }

    public function likeVideo(Video $video)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $user->addLikedVideo($video);

        $em  = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return 'liked';
    }

    public function unlikeVideo(Video $video)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $user->removeLikedVideo($video);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return 'undo liked';
    }

    public function undoDislikeVideo(Video $video)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $user->removeDislikedVideo($video);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return 'undo disliked';
    }
}
