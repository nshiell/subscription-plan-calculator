<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class UserPlanPersistor
{
    public function saveFromRequest(User $user, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            throw new \InvalidArgumentException;
        }
    }
}