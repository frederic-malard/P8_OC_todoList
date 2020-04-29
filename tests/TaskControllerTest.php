<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTasksListResponse()
    {
        $client = static::createClient([], ['HTTPS' => true]);
        $crawler = $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
    }

    public function testNoTaskWhenStart()
    {
        $client = static::createClient(
            [],
            [
                'HTTPS' => true
            ]
        );
        $crawler = $client->request('GET', '/tasks');

        $nbElements = $crawler->filter('div.alert-warning')->count();

        $this->assertEquals($nbElements, 1);
    }

    public function testTaskSeenWhenCreated()
    {
        $client = static::createClient(
            [],
            [
                'HTTPS' => true
            ]
        );

        $crawler = $client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'titre première tache',
            'task[content]' => 'contenu première tache'
        ]);

        $client->submit($form);

        $client->followRedirect();

        $this->assertSelectorExists('p#dansListe');
    }

    //     // $nbDivSuccess = $crawlerPost->filter('div.alert-success')->count();
    
}
