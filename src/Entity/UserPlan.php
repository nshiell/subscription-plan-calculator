<?php

namespace App\Entity;

use App\Repository\UserPlanRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=UserPlanRepository::class)
 * @ORM\Table(name="user_plan", 
 *    uniqueConstraints={
 *        @UniqueConstraint(name="user_plan_unique", 
 *            columns={"user_id", "plan_id"})
 *    }
 * )
 */
class UserPlan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userPlans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Plan::class, inversedBy="userPlans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plan;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isYearCost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    public function setPlan(?Plan $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function getIsYearCost(): ?bool
    {
        return $this->isYearCost;
    }

    public function setIsYearCost(bool $isYearCost): self
    {
        $this->isYearCost = $isYearCost;

        return $this;
    }
}
