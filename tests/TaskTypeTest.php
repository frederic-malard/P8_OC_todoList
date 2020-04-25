<?php

// tests/Form/Type/TaskTypeTest.php
namespace App\Tests\Form\Type;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'titre tache',
            'content' => 'contenu tache'
        ];

        $objectToCompare = new Task();
        $object = clone $objectToCompare;

        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(TaskType::class, $objectToCompare);

        // ...populate $object properties with the data stored in $formData

        // c'est moi qui ait ajouté mais je suis pas sur du tout
        $object
            ->setTitle("titre tache")
            ->setContent("contenu tache")
        ;

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
