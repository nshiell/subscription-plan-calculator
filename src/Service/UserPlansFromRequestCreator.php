<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\PlanRepository;
use App\Entity\User;
use App\Entity\UserPlan;
use Doctrine\ORM\EntityManagerInterface;

class UserPlanPersistor
{
    /** @var PlanRepository */
    private $planRepository;

    public function __construct(PlanRepository $planRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->planRepository = $planRepository;
    }

    private function isUserPlanSubmitValid(array $userPlanRequestList): bool
    {
        $codes = [];
        foreach ($userPlanRequestList as $userPlanRequest) {
            if (!isset ($userPlanRequest['code'])) {
                return false;
            }

            if (!isset ($userPlanRequest['isYearCost'])) {
                return false;
            }

            if (!is_bool($userPlanRequest['isYearCost'])) {
                return false;
            }

            $codes[] = $userPlanRequest['code'];
        }

        $codes = array_unique($codes);
        $codesFound = $this->planRepository->findByCode($codes);
        return (count($codesFound) == count($codes));
    }

    public function saveFromRequest(User $user, Request $request)
    {
        $userPlanRequestList = json_decode($request->getContent(), true);
        if (!is_array($userPlanRequestList) || !$this->isUserPlanSubmitValid($userPlanRequestList)) {
            throw new \InvalidArgumentException;
        }

        $plans = $this->planRepository->findAll();
        $userPlans = $user->getUserPlans();

        // wipe out all plans for this user, so we can rebuild them
        $userPlans->clear();
        $this->entityManager->flush();

        // go through each plan available and check whether to add it
        foreach ($plans as $plan) {
            $planCode = $plan->getCode();

            // go through each plan from the user request
            foreach ($userPlanRequestList as $userPlanRequest) {

                // this is the code the user wants to add
                $newCode = $userPlanRequest['code'];

                if ($newCode == $planCode) {
                    $user->addUserPlan((new UserPlan)
                        ->setPlan($plan)
                        ->setIsYearCost($userPlanRequest['isYearCost'])
                    );

                    // added a code for this plan, no nee to continue going
                    // through the request anymore
                    break;
                }
            }
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}