<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubscriptionRepository")
 * @ORM\Table(name="subscriptions")
 */
class Subscription
{
    /**
     * @var array
     */
    private static $planDataNames = ['free', 'pro', 'enterprise'];

    /**
     * @var array
     */
    private static $planDataPrices = [
      'free' => 0,  //0€
      'pro' => 15,   // 15€
      'enterprise' => 29  // 29€
    ];

    /**
     * @param int $index
     * @return string
     */
    public static function getPlanDataNameByIndex(int $index) :string
    {
        return self::$planDataNames[$index];
    }

    /**
     * @param string $name
     * @return int
     */
    public static function getPlanDataPriceByName(string $name): int
    {
        return self::$planDataPrices[$name];
    }

    /**
     * @return array
     */
    public static function getPlanDataNames(): array
    {
        return self::$planDataNames;
    }

    /**
     * @return array
     */
    public static function getPlanDataPrices(): array
    {
        return self::$planDataPrices;
    }



    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $plan;

    /**
     * @ORM\Column(type="datetime")
     */
    private $valid_to;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $payment_status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $free_plan_used;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getPlan(): ?string
    {
        return $this->plan;
    }

    /**
     * @param string $plan
     * @return $this
     */
    public function setPlan(string $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getValidTo(): ?\DateTimeInterface
    {
        return $this->valid_to;
    }

    /**
     * @param \DateTimeInterface $valid_to
     * @return $this
     */
    public function setValidTo(\DateTimeInterface $valid_to): self
    {
        $this->valid_to = $valid_to;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaymentStatus(): ?string
    {
        return $this->payment_status;
    }

    /**
     * @param string|null $payment_status
     * @return $this
     */
    public function setPaymentStatus(?string $payment_status): self
    {
        $this->payment_status = $payment_status;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getFreePlanUsed(): ?bool
    {
        return $this->free_plan_used;
    }

    /**
     * @param bool $free_plan_used
     * @return $this
     */
    public function setFreePlanUsed(bool $free_plan_used): self
    {
        $this->free_plan_used = $free_plan_used;

        return $this;
    }


}
