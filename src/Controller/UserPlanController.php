<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Plan;
use App\Entity\User;
use App\Service\UserPlansUpdater;
use App\Service\UserPlansFromRequestCreator;

class UserPlanController
{
    /** @var UserPlansUpdater */
    private $userPlansUpdater;

    /** @var UserPlansFromRequestCreator */
    private $userPlansFromRequestCreator;

    public function __construct(UserPlansUpdater $userPlansUpdater,
        UserPlansFromRequestCreator $userPlansFromRequestCreator)
    {
        $this->userPlansUpdater = $userPlansUpdater;
        $this->userPlansFromRequestCreator = $userPlansFromRequestCreator;
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
}