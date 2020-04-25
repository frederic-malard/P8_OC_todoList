<?php

namespace App\Tests;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testIsDoneFalse()
    {
        $task = new Task();
        $this->assertEquals($task->isDone(), false);
    }

    // vérifie que le createdAt soit bien a la date actuelle lorsqu'on créé une tache. Créé une nouvelle tâche et une DateTime et compare. Marge de 10 secondes au cas où il y ait un délai.
    public function testCreatedAt()
    {
        $task = new Task();

        $createdAt = $task->getCreatedAt();
        // $timestampCreatedAt = $createdAt->getTimestamp();

        $maintenant = new \DateTime();
        // $timestampMaintenant = $maintenant->getTimestamp();

        // $difference = abs($timestampCreatedAt - $timestampMaintenant);

        // $this->assertLessThan(10, $difference);
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
}
