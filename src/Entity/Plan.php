<?php

namespace App\Entity;

use App\Repository\PlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlanRepository::class)
 */
class Plan implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $costMonth;

    /**
     * @ORM\Column(type="float")
     */
    private $costYear;

    /**
     * @ORM\OneToMany(targetEntity=UserPlan::class, mappedBy="plan", orphanRemoval=true)
     */
    private $userPlans;

    public function __construct()
    {
        $this->userPlans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCostMonth(): ?float
    {
        return $this->costMonth;
    }

    public function setCostMonth(float $costMonth): self
    {
        $this->costMonth = $costMonth;

        return $this;
    }

    public function getCostYear(): ?float
    {
        return $this->costYear;
    }

    public function setCostYear(float $costYear): self
    {
        $this->costYear = $costYear;

        return $this;
    }

    /**
     * @return Collection|UserPlan[]
     */
    public function getUserPlans(): Collection
    {
        return $this->userPlans;
    }

    public function addUserPlan(UserPlan $userPlan): self
    {
        if (!$this->userPlans->contains($userPlan)) {
            $this->userPlans[] = $userPlan;
            $userPlan->setPlan($this);
        }

        return $this;
    }

    public function removeUserPlan(UserPlan $userPlan): self
    {
        if ($this->userPlans->contains($userPlan)) {
            $this->userPlans->removeElement($userPlan);
            // set the owning side to null (unless already changed)
            if ($userPlan->getPlan() === $this) {
                $userPlan->setPlan(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'code'      => $this->getCode(),
            'name'      => $this->getName(),
            'costMonth' => $this->getCostMonth(),
            'costYear'  => $this->getCostYear()
        ];
    }
}
