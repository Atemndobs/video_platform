<?php


namespace App\Listeners;


use App\Entity\User;
use App\Entity\Video;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class NewVideoListener
{
    // the listener methods receive an argument which gives you access to
    // both the entity object of the event and the entity manager itself
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Video) {
            return;
        }

        $entityManager = $args->getObjectManager();
        // ... do something with the Product entity
        $users = $entityManager->getRepository(User::class)->findAll();
        foreach ($users as $user){
            exit($entity->getTitle());
        }

    }
}
