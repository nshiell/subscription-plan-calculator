<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=UserPlan::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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
            $userPlan->setUser($this);
        }

        return $this;
    }

    public function removeUserPlan(UserPlan $userPlan): self
    {
        if ($this->userPlans->contains($userPlan)) {
            $this->userPlans->removeElement($userPlan);
            // set the owning side to null (unless already changed)
            if ($userPlan->getUser() === $this) {
                $userPlan->setUser(null);
            }
        }

        return $this;
    }
}
