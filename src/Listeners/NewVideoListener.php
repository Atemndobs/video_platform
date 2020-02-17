<?php


namespace App\Listeners;


use App\Entity\User;
use App\Entity\Video;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Twig\Environment;

class NewVideoListener
{
    // \Twig_Environment   same as Twig/Environment??
    /**
     * @var Environment
     */
    private $templating;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(Environment $templating, \Swift_Mailer $mailer)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;

    }

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
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->templating->render('emails/new_video.html.twig', [
                        'name'=>$user->getName(),
                        'video' => $entity
                    ]),
                    'text/html'
                );

            $this->mailer->send($message);
        }

    }

}
