<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient([], ['HTTPS' => true]);

        /** @var EntityManagerInterface $em */
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $purger = new ORMPurger($em);
        $purger->purge();
    }

    public function testTasksListResponse()
    {
        $crawler = $this->client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
    }

    public function testNoTaskWhenStart()
    {
        $crawler = $this->client->request('GET', '/tasks');

        $nbElements = $crawler->filter('div.alert-warning')->count();

        $this->assertEquals($nbElements, 1);
    }

    public function testTaskSeenWhenCreated()
    {
        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'titre première tache',
            'task[content]' => 'contenu première tache'
        ]);

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertSelectorTextContains('h4 a', 'titre première tache');
        $this->assertSelectorTextContains('h4+p', 'contenu première tache');
    }

    //     // $nbDivSuccess = $crawlerPost->filter('div.alert-success')->count();
    
}
