<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\UserPlanPersistor;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;


class UserPlanPersistorTest extends TestCase
{
    public function testAdd()
    {
        $requestMock = $this->createMock(Request::class);

        $requestMock->method('getContent')
             ->willReturn('BAD DATA');

        $user = new User;
        $persistor = new UserPlanPersistor();
        $this->expectException(\InvalidArgumentException::class);
        $persistor->saveFromRequest($user, $requestMock);
    }
}