<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Entity\Plan;
use App\Entity\UserPlan;
use App\Service\UserPlansCalculator;

class UserPlansCalculatorTest extends TestCase
{
    /** @dataProvider userPlanProvider */
    public function testAdd($userPlans, $yearlyCost, $monthlyCost)
    {
        $userPlansCalculator = new UserPlansCalculator;
        $monthlyAndAnnualCost = $userPlansCalculator
            ->calculateMonthlyAndAnnualCost($userPlans);

        $this->assertSame(
            (float) $monthlyAndAnnualCost['yearly'],
            (float) $yearlyCost
        );

        $this->assertSame(
            (float) $monthlyAndAnnualCost['monthly'],
            (float) $monthlyCost
        );
    }

    public function userPlanProvider()
    {
        $planMLowYHigh = (new Plan)
            ->setCostMonth(3)
            ->setCostYear(999);

        $planMHighYLow = (new Plan)
            ->setCostMonth(999)
            ->setCostYear(3);

        $userPlanMonthlyLow = (new UserPlan)
            ->setPlan($planMLowYHigh)
            ->setIsYearCost(false);

        $userPlanYearlyLow = (new UserPlan)
            ->setPlan($planMHighYLow)
            ->setIsYearCost(true);

        return [
            [[
                $userPlanMonthlyLow,
                $userPlanYearlyLow
            ], 3, 3],
            [[
                $userPlanYearlyLow,
                $userPlanYearlyLow
            ], 6, 0],
            [[
                $userPlanMonthlyLow,
                $userPlanMonthlyLow
            ], 0, 6],
        ];
    }
}