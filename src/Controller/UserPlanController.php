<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Service\UserPlansUpdater;
use App\Service\UserPlansFromRequestCreator;
use App\Service\UserPlansCalculator;

class UserPlanController
{
    /** @var UserPlansUpdater */
    private $userPlansUpdater;

    /** @var UserPlansFromRequestCreator */
    private $userPlansFromRequestCreator;

    /** @var UserPlansCalculator */
    private $calculator;

    public function __construct(
        UserPlansUpdater $userPlansUpdater,
        UserPlansFromRequestCreator $userPlansFromRequestCreator,
        UserPlansCalculator $calculator)
    {
        $this->userPlansUpdater = $userPlansUpdater;
        $this->userPlansFromRequestCreator = $userPlansFromRequestCreator;
        $this->calculator = $calculator;
    }

    /**
     * @Route("/user/{username}/plan", name="user_plan_list", methods={"POST"})
     */
    public function save(Request $request, User $user): JsonResponse
    {
        try {
            $userPlans = $this->userPlansFromRequestCreator
                ->createArrayFromRequest($request);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse('BAD JSON', Response::HTTP_BAD_REQUEST);
        }

        $this->userPlansUpdater->overwriteAndSaveUserPlans($user, $userPlans);

        return new JsonResponse('OK', Response::HTTP_CREATED);
    }

    /**
     * @Route("/calculator", name="calculator", methods={"POST"})
     */
    public function calculate(Request $request): JsonResponse
    {
        try {
            $userPlans = $this->userPlansFromRequestCreator
                ->createArrayFromRequest($request);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse('BAD JSON', Response::HTTP_BAD_REQUEST);
        }

        $monthlyAndAnnualCost = $this->calculator
            ->calculateMonthlyAndAnnualCost($userPlans);

        return new JsonResponse($monthlyAndAnnualCost, Response::HTTP_CREATED);
    }
}