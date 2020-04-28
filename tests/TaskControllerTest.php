<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTasksListResponse()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
    }

    public function testNoTaskWhenStart()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $nbElements = $crawler->filter('div.alert-warning')->count();

        $this->assertEquals($nbElements, 1);
    }

    public function testTaskSeenWhenCreated()
    {
        $clientPost = static::createClient(
            [],
            [
                'HTTPS' => true
            ]
        );

        // $clientGet = clone $clientPost;
        
        $crawlerPost = $clientPost->request(
            'POST',
            '/tasks/create',
            [
                "content" => "première tache contenu",
                "title" => "première tache titre"
            ]
        );

        // $nbDivSuccess = $crawlerPost->filter('div.alert-success')->count();
        $nbPDansList = $crawlerPost->filter('p#dansList')->count();
        $nbPDansCreate = $crawlerPost->filter('p#dansCreate')->count();

        // $this->assertEquals($nbDivSuccess, 1);
        $this->assertEquals($nbPDansList, 1);
        $this->assertEquals($nbPDansCreate, 0);

        // $this->assertResponseRedirects('/tasks');

        // $clientGet = static::createClient(
        //     [],
        //     [
        //         'HTTPS' => true
        //     ]
        // );

        // $crawlerGet = $clientGet->request(
        //     'GET',
        //     '/tasks'
        // );

        // $nbAlerts = $crawlerGet->filter('div.alert-warning')->count();

        $nbAlerts = $crawlerPost->filter('div.alert-warning')->count();

        $this->assertEquals($nbAlerts, 0);
    }

    // public function testTaskSeenWhenCreated()
    // {
    //     $client = static::createClient(
    //         [],
    //         [
    //             'HTTPS' => true
    //         ]
    //     );

    //     $crawler = $client->request('GET', '/tasks/create');

    //     $form = $crawler->selectButton('Ajouter')->form([
    //         'title' => 'titre première tache',
    //         'content' => 'contenu première tache'
    //     ]);

    //     $client->submit($form);

    //     $this->assertResponseRedirect('/login');

    //     $client->followRedirect();

    //     $this->assertSelectorExists('p#dansList');
    // }
    
}
