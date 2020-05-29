<?php

namespace App\Service;

class UserPlansCalculator
{
    /** @param \App\Entity\UserPlan[] $userPlans */
    public function calculateMonthlyAndAnnualCost(array $userPlans): array
    {
        $costs = ['monthly' => 0, 'yearly' => 0];
        foreach ($userPlans as $userPlan) {
            if ($userPlan->getIsYearCost()) {
                $costs['yearly']+= $userPlan->getPlan()->getCostYear();
            } else {
                $costs['monthly']+= $userPlan->getPlan()->getCostMonth();
            }
        }

        return $costs;
    }
}