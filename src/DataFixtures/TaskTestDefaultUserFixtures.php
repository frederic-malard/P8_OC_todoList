<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TaskTestDefaultUserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $task = new Task();

        $task
            ->setTitle("testDefault")
            ->setContent("contenu")
        ;

        $manager->persist($task);

        $manager->flush();
    }
}
