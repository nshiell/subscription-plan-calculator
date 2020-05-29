<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\UserPlansFromRequestCreator;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PlanRepository;
use App\Entity\Plan;
use App\Entity\UserPlan;


class UserPlansFromRequestCreatorTest extends TestCase
{
    public function testSaveFromRequestNotArray()
    {
        $requestMock = $this->createMock(Request::class);
        $planRepositoryMock = $this->createMock(PlanRepository::class);

        $requestMock->method('getContent')
             ->willReturn('BAD DATA');

        $creator = new UserPlansFromRequestCreator($planRepositoryMock);
        $this->expectException(\InvalidArgumentException::class);
        $creator->createArrayFromRequest($requestMock);
    }

    public function testSaveFromRequestNotPresentCode()
    {
        $requestMock = $this->createMock(Request::class);
        $planRepositoryMock = $this->createMock(PlanRepository::class);

        $requestMock->method('getContent')
             ->willReturn('[{"codeZ": "gb", "isYearCost": false}]');

        $creator = new UserPlansFromRequestCreator($planRepositoryMock);
        $this->expectException(\InvalidArgumentException::class);
        $creator->createArrayFromRequest($requestMock);
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

        $creator = new UserPlansFromRequestCreator($planRepositoryMock);
        $this->expectException(\InvalidArgumentException::class);
        $creator->createArrayFromRequest($requestMock);
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

        $creator = new UserPlansFromRequestCreator($planRepositoryMock);
        $this->expectException(\InvalidArgumentException::class);
        $creator->createArrayFromRequest($requestMock);
    }

    public function testSaveFromRequestNotIsYearCostBool()
    {
        $requestMock = $this->createMock(Request::class);

        $planRepositoryMock = $this->getMockBuilder(PlanRepository::class)
            ->setMethods(['findByCode'])
            ->disableOriginalConstructor()
            ->getMock();

        $requestMock->method('getContent')
             ->willReturn('[{"code": "gb", "isYearCost": "false"}]');

        $creator = new UserPlansFromRequestCreator($planRepositoryMock);
        $this->expectException(\InvalidArgumentException::class);
        $creator->createArrayFromRequest($requestMock);
    }

    public function testSaveFromRequestSave()
    {
        $requestMock = $this->createMock(Request::class);

        $planRepositoryMock = $this->getMockBuilder(PlanRepository::class)
            ->setMethods(['findByCode', 'findAll'])
            ->disableOriginalConstructor()
            ->getMock();

            $planRepositoryMock->method('findByCode')
            ->with($this->equalTo(['gb', 'us']))
            ->willReturn([1, 2]);

        $allPlans = [
            (new Plan)->setCode('gb'),
            (new Plan)->setCode('us')
        ];

        $planRepositoryMock->method('findAll')
            ->willReturn($allPlans);

        $requestMock->method('getContent')
             ->willReturn('[
                {"code": "gb", "isYearCost": false},
                {"code": "us", "isYearCost": true}
            ]');

        $creator = new UserPlansFromRequestCreator($planRepositoryMock);
        $userPlans = $creator->createArrayFromRequest($requestMock);
        $this->assertCount(2, $userPlans);

        $this->assertInstanceOf(UserPlan::class, $userPlans[0]);
        $this->assertFalse($userPlans[0]->getIsYearCost());
        $this->assertSame($allPlans[0], $userPlans[0]->getPlan());

        $this->assertInstanceOf(UserPlan::class, $userPlans[1]);
        $this->asserttrue($userPlans[1]->getIsYearCost());
        $this->assertSame($allPlans[1], $userPlans[1]->getPlan());
    }
}