<?php

namespace App\Tests;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\DataFixtures\TaskTestEditFixtures;
use App\DataFixtures\UserTestEditFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    private $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient([], ['HTTPS' => true]);

        /** @var EntityManagerInterface $em */
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $purger = new ORMPurger($em);
        $purger->purge();

        self::bootKernel();
    }

    private function login()
    {
        $repository = self::$container->get(UserRepository::class);

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'nom',
            'password' => 'unMotDePasse'
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();
    }

    private function loadFixturesInterne(bool $useTask = false)
    {
        if($useTask)
        {
            $this->loadFixtures([TaskTestEditFixtures::class]);
        }
        else
        {
            $this->loadFixtures([UserTestEditFixtures::class]);
        }
    }

    public function testTasksListResponse()
    {
        $this->loadFixturesInterne();
        $this->login();

        $crawler = $this->client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
    }

    public function testNoTaskWhenStart()
    {
        $this->loadFixturesInterne();
        $this->login();

        $crawler = $this->client->request('GET', '/tasks');

        $nbElements = $crawler->filter('div.alert-warning')->count();

        $this->assertEquals($nbElements, 1);
    }

    public function testTaskSeenWhenCreated()
    {
        $this->loadFixturesInterne();
        $this->login();

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

    public function testTaskEdit()
    {
        $this->loadFixturesInterne(true);
        $this->login();

        $repository = self::$container->get(TaskRepository::class);
        $task = $repository->findOneByTitle("viaFixtures");
        $id = $task->getId();

        $crawler = $this->client->request('GET', '/tasks/' . $id . '/edit');

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'titre modifié',
            'task[content]' => 'contenu modifié'
        ]);


        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertSelectorTextContains('h4 a', 'titre modifié');
        $this->assertSelectorTextContains('h4+p', 'contenu modifié');
    }

    public function testTaskToggle()
    {
        $this->loadFixturesInterne(true);
        $this->login();

        $repository = self::$container->get(TaskRepository::class);
        $task = $repository->findOneByTitle("viaFixtures");
        $id = $task->getId();
        $isDoneBeforeToggle = $task->isDone();
        
        $crawler = $this->client->request('GET', '/tasks');

        $nbNotDone = $crawler->filter('.glyphicon-remove')->count();
        $nbDone = $crawler->filter('.glyphicon-ok')->count();
        $nbBtnDo = $crawler->selectButton('Marquer comme faite')->count();
        $nbBtnUndo = $crawler->selectButton('Marquer non terminée')->count();

        $this->assertEquals($nbNotDone, 1);
        $this->assertEquals($nbDone, 0);
        $this->assertEquals($nbBtnDo, 1);
        $this->assertEquals($nbBtnUndo, 0);

        $form = $crawler->selectButton('Marquer comme faite')->form();
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $manager = self::$container->get("doctrine.orm.entity_manager");
        $manager->refresh($task);

        $isDoneAfterToggle = $task->isDone();

        $this->assertNotEquals($isDoneBeforeToggle, $isDoneAfterToggle);

        $nbNotDone = $crawler->filter('.glyphicon-remove')->count();
        $nbDone = $crawler->filter('.glyphicon-ok')->count();
        $nbBtnDo = $crawler->selectButton('Marquer comme faite')->count();
        $nbBtnUndo = $crawler->selectButton('Marquer non terminée')->count();

        $this->assertEquals(0, $nbNotDone);
        $this->assertEquals(1, $nbDone);
        $this->assertEquals(0, $nbBtnDo);
        $this->assertEquals(1, $nbBtnUndo);
    }

    public function testTaskDelete()
    {
        $this->loadFixturesInterne(true);
        $this->login();

        $crawler = $this->client->request('GET', '/tasks');

        $nbTaskElt = $crawler->filter('div.thumbnail')->count();

        $this->assertEquals($nbTaskElt, 1);

        $form = $crawler->selectButton('Supprimer')->form();
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $nbTaskElt = $crawler->filter('div.thumbnail')->count();

        $this->assertEquals(0, $nbTaskElt);
    }

    // public function testCurrentUserAddedToCreatedTask()
    // {

    // }
}
