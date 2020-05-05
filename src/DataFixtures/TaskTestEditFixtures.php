<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskTestEditFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $task = new Task();

        $task
            ->setTitle("viaFixtures")
            ->setContent("contenu")
            ->setUser($this->getReference("user"))
        ;

        $manager->persist($task);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserTestEditFixtures::class,
        );
    }
}
