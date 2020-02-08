<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SubscriptionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        foreach ($this->getSubscriptionData() as [$user_id, $plan, $valid_to, $payment_status, $free_plan_status]){
            $subscription = new Subscription();

            $subscription->setPlan($plan);
            $subscription->setValidTo($valid_to);
            $subscription->setFreePlanUsed($free_plan_status);
            $subscription->setPaymentStatus($payment_status);

            $user = $manager->getRepository(User::class)->find($user_id);
            $user->setSubscription($subscription);
            $manager->persist($user);
        }

        $manager->flush();


    }

    public function getSubscriptionData(): array
    {
        return [
            [1, Subscription::getPlanDataNameByIndex(2), (new \DateTime())->modify('+100 year'), 'paid', false], // su Admin
            [3, Subscription::getPlanDataNameByIndex(0), (new \DateTime())->modify('+1 month'), 'paid', true], //  paid user, 1month subs
            [4, Subscription::getPlanDataNameByIndex(1), (new \DateTime())->modify('+1 minute'), 'paid', false], //  non paid user

        ];
    }
}

