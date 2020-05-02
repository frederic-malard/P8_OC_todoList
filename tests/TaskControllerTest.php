<?php

namespace App\Tests;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    // private $client;

    // public function setUp(): void
    // {
    //     parent::setUp();

    //     $this->client = static::createClient([], ['HTTPS' => true]);

    //     /** @var EntityManagerInterface $em */
    //     $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

    //     $purger = new ORMPurger($em);
    //     $purger->purge();
    // }

    public function chargerFixture($nomFixture)
    {
        self::bootKernel();
        $this->loadFixtueFiles([
            __DIR__ . '/' . $nomFixture . 'Fixtures.yaml'
        ]);
    }

    public function testTasksListResponse()
    {
        $client = static::createClient([], ['HTTPS' => true]);

        $crawler = $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
    }

    public function testNoTaskWhenStart()
    {
        $this->chargerFixture("TaskControllerTest");

        $client = static::createClient([], ['HTTPS' => true]);
        
        $crawler = $client->request('GET', '/tasks');

        $nbElements = $crawler->filter('div.alert-warning')->count();

        $this->assertEquals($nbElements, 1);
    }

    public function testTaskSeenWhenCreated()
    {
        $client = static::createClient([], ['HTTPS' => true]);
        
        $crawler = $client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'titre première tache',
            'task[content]' => 'contenu première tache'
        ]);

        $client->submit($form);

        $client->followRedirect();

        //assertredirect

        $this->assertSelectorExists('p#dansListe');
    }

    //     // $nbDivSuccess = $crawlerPost->filter('div.alert-success')->count();
    
}
