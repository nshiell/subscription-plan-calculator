<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\UserPlanPersistor;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;


class UserPlanPersistorTest extends TestCase
{
    public function testSaveFromRequestNotArray()
    {
        $requestMock = $this->createMock(Request::class);

        $requestMock->method('getContent')
             ->willReturn('BAD DATA');

        $user = new User;
        $persistor = new UserPlanPersistor();
        $this->expectException(\InvalidArgumentException::class);
        $persistor->saveFromRequest($user, $requestMock);
    }

    public function testSaveFromRequestEmpty()
    {
        $requestMock = $this->createMock(Request::class);

        $requestMock->method('getContent')
             ->willReturn('[]');

        $user = new User;
        $persistor = new UserPlanPersistor();
        $persistor->saveFromRequest($user, $requestMock);
        $this->assertTrue(true);
    }
}