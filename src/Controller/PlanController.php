<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PlanRepository;
use App\Entity\Plan;

class PlanController
{
    /** @var PlanRepository */
    private $planRepository;

    public function __construct(PlanRepository $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    /**
     * @Route("/plan", name="plan_list", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        return new JsonResponse($this->planRepository->findAll());
    }

    /**
     * @Route("/plan/{code}", name="view", methods={"GET"})
     */
    public function view(Plan $plan): JsonResponse
    {
        return new JsonResponse($plan);
    }
}