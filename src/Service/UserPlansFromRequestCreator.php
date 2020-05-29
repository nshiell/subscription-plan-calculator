<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\PlanRepository;
use App\Entity\User;
use App\Entity\UserPlan;

class UserPlansFromRequestCreator
{
    const KEY_CODE    = 'code';
    const KEY_IS_YEAR = 'isYearCost';

    /** @var PlanRepository */
    private $planRepository;

    public function __construct(PlanRepository $planRepository)
    {
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

    public function createArrayFromRequest(Request $request): array
    {
        $userPlanRequestList = json_decode($request->getContent(), true);
        if (!is_array($userPlanRequestList) || !$this->isUserPlanSubmitValid($userPlanRequestList)) {
            throw new \InvalidArgumentException;
        }

        $plans = $this->planRepository->findAll();

        $userPlans = [];
        // go through each plan available and check whether to add it
        foreach ($plans as $plan) {
            $planCode = $plan->getCode();

            // go through each plan from the user request
            foreach ($userPlanRequestList as $userPlanRequest) {

                // this is the code the user wants to add
                $newCode = $userPlanRequest[self::KEY_CODE];

                if ($newCode == $planCode) {
                    $userPlans[] = (new UserPlan)
                        ->setPlan($plan)
                        ->setIsYearCost($userPlanRequest[self::KEY_IS_YEAR]);

                    // added a code for this plan, no nee to continue going
                    // through the request anymore
                    break;
                }
            }
        }
        return $userPlans;
    }
}