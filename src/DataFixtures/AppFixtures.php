<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Plan;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist((new Plan)
            ->setCode('gb')
            ->setName('UK')
            ->setCostMonth(10)
            ->setCostYear(50)
        );

        $manager->persist((new Plan)
            ->setCode('fr')
            ->setName('France')
            ->setCostMonth(10)
            ->setCostYear(60)
        );

        $manager->persist((new Plan)
            ->setCode('de')
            ->setName('Germany')
            ->setCostMonth(15)
            ->setCostYear(75)
        );

        $manager->persist((new Plan)
            ->setCode('us')
            ->setName('USA')
            ->setCostMonth(25)
            ->setCostYear(150)
        );

        $manager->persist((new Plan)
            ->setCode('jp')
            ->setName('Japan')
            ->setCostMonth(15)
            ->setCostYear(65)
        );

        $manager->persist((new User)
            ->setUsername('person1')
        );

        $manager->persist((new User)
            ->setUsername('person2')
        );

        $manager->flush();
    }
}
