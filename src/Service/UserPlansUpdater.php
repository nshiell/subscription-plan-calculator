<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\PlanRepository;
use App\Entity\User;
use App\Entity\Plan;
use App\Entity\UserPlan;
use Doctrine\ORM\EntityManagerInterface;

class UserPlansUpdater
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function overwriteAndSaveUserPlans(User $user, array $newUserPlans)
    {
        $userPlans = $user->getUserPlans();

        // wipe out all plans for this user, so we can rebuild them
        $userPlans->clear();
        $this->entityManager->flush();

        foreach ($newUserPlans as $newUserPlan) {
            $user->addUserPlan($newUserPlan);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}