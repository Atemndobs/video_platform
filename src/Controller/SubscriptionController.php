<?php

namespace App\Controller;

use App\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    /**
     * @Route("/subscription", name="subscription")
     */
    public function index()
    {
        return $this->render('subscription/index.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }


    /**
     * @Route("/pricing", name="pricing")
     */
    public function pricing()
    {

        return $this->render('front/pricing.html.twig', [
            'name'=>Subscription::getPlanDataNames(),
            'price'=> Subscription::getPlanDataPrices(),
        ]);
    }
}
