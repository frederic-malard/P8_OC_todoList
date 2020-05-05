<?php

namespace App\Tests;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function testIsDoneFalse()
    {
        $task = new Task();
        $this->assertEquals($task->isDone(), false);
    }

    // vérifie que le createdAt soit bien a la date actuelle lorsqu'on créé une tache. Créé une nouvelle tâche et une DateTime et compare.
    public function testCreatedAt()
    {
        $task = new Task();

        $createdAt = $task->getCreatedAt();

        $maintenant = new \DateTime();
        
        $this->assertEqualsWithDelta($maintenant, $createdAt, 1);
    }

    public function testToggleTrue()
    {
        $task = new Task();

        $task->toggle();

        $this->assertEquals($task->isDone(), true);
    }

    public function testToggleFalse()
    {
        $task = new Task();

        $task->toggle();
        $task->toggle();

        $this->assertEquals($task->isDone(), false);
    }

    public function testBlankTitleRefused()
    {
        $task = new Task();

        $task
            ->setCreatedAt(new \DateTime())
            ->setTitle("")
            ->setContent("faire le truc à faire")
        ;

        self::bootKernel();

        $nbErrors = count(self::$container->get('validator')->validate($task));

        $this->assertNotEquals(0, $nbErrors);
    }

    public function testBlankContentRefused()
    {
        $task = new Task();

        $task
            ->setCreatedAt(new \DateTime())
            ->setTitle("jogging")
            ->setContent("")
        ;

        self::bootKernel();

        $nbErrors = count(self::$container->get('validator')->validate($task));

        $this->assertNotEquals(0, $nbErrors);
    }

    public function testCorrectValuesAccepted()
    {
        $task = new Task();

        $task
            ->setCreatedAt(new \DateTime())
            ->setTitle("compter jusqu'à 3")
            ->setContent("1, 2, 3")
        ;

        self::bootKernel();

        $errors = self::$container->get('validator')->validate($task);

        $this->assertCount(0, $errors);
    }

    public function testRefuseToChangeUserOfExistingTask()
    {

    }

    public function testAllTasksHaveUser()
    {
        
    }
}
