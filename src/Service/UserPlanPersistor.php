<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\PlanRepository;
use App\Entity\User;

class UserPlanPersistor
{
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

            $codes[] = $userPlanRequest['code'];
        }

        $codes = array_unique($codes);
        $codesFound = $this->planRepository->findByCode($codes);
        return (count($codesFound) == count($codes));
    }

    public function saveFromRequest(User $user, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data) || !$this->isUserPlanSubmitValid($data)) {
            throw new \InvalidArgumentException;
        }
    }
}