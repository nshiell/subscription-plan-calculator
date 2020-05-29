<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\UserPlanPersistor;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PlanRepository;
use App\Entity\User;


class UserPlanPersistorTest extends TestCase
{
    public function testSaveFromRequestNotArray()
    {
        $requestMock = $this->createMock(Request::class);
        $planRepositoryMock = $this->createMock(PlanRepository::class);

        $requestMock->method('getContent')
             ->willReturn('BAD DATA');

        $user = new User;
        $persistor = new UserPlanPersistor($planRepositoryMock);
        $this->expectException(\InvalidArgumentException::class);
        $persistor->saveFromRequest($user, $requestMock);
    }

    public function testSaveFromRequestNotPresentCode()
    {
        $requestMock = $this->createMock(Request::class);
        $planRepositoryMock = $this->createMock(PlanRepository::class);

        $requestMock->method('getContent')
             ->willReturn('[{"codeZ": "gb", "isYearCost": false}]');

        $user = new User;
        $persistor = new UserPlanPersistor($planRepositoryMock);
        $this->expectException(\InvalidArgumentException::class);
        $persistor->saveFromRequest($user, $requestMock);
    }

    public function testSaveFromRequestNotValidCode()
    {
        $requestMock = $this->createMock(Request::class);

        $planRepositoryMock = $this->getMockBuilder(PlanRepository::class)
            ->setMethods(['findByCode'])
            ->disableOriginalConstructor()
            ->getMock();

        $planRepositoryMock->method('findByCode')
            ->willReturn([]);

        $requestMock->method('getContent')
             ->willReturn('[{"code": "ZZ", "isYearCost": false}]');

        $user = new User;
        $persistor = new UserPlanPersistor($planRepositoryMock);
        $this->expectException(\InvalidArgumentException::class);
        $persistor->saveFromRequest($user, $requestMock);
    }

    public function testSaveFromRequestNotIsYearCostProvided()
    {
        $requestMock = $this->createMock(Request::class);

        $planRepositoryMock = $this->getMockBuilder(PlanRepository::class)
            ->setMethods(['findByCode'])
            ->disableOriginalConstructor()
            ->getMock();

        $requestMock->method('getContent')
             ->willReturn('[{"code": "gb"}]');

        $user = new User;
        $persistor = new UserPlanPersistor($planRepositoryMock);
        $this->expectException(\InvalidArgumentException::class);
        $persistor->saveFromRequest($user, $requestMock);
    }
}