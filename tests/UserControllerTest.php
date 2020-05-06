<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use App\DataFixtures\UserTestEditFixtures;
use App\DataFixtures\UserTestAdminFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserControllerTest extends WebTestCase
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
            'username' => 'admin',
            'password' => 'unMotDePasse'
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();
    }

    public function testUsersEditResponse()
    {
        $this->loadFixtures([UserTestAdminFixtures::class]);
        $this->login();

        $crawler = $this->client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
    }

    // public function testNoUserWhenStart()
    // {
    //     $crawler = $this->client->request('GET', '/users');

    //     $nbElements = $crawler->filter('.btn-succes')->count();

    //     $this->assertEquals($nbElements, 0);
    // }

    public function testUserSeenWhenCreated()
    {
        $repository = self::$container->get(UserRepository::class);

        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'nom',
            'user[password][first]' => 'motDePasse',
            'user[password][second]' => 'motDePasse',
            'user[email]' => 'email@mail.fr'
        ]);

        $this->client->submit($form);

        $this->client->followRedirect();

        $nbUsers = count($repository->findAll());

        $this->assertEquals(1, $nbUsers);
    }

    public function testUserEdit()
    {
        $this->loadFixtures([UserTestAdminFixtures::class]);
        
        $repository = self::$container->get(UserRepository::class);
        $user = $repository->findOneByUsername("nom");
        $id = $user->getId();
        
        $this->login();

        $crawler = $this->client->request('GET', '/users/' . $id . '/edit');
        
        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'nouveauNom',
            'user[password][first]' => 'nouveauMdp',
            'user[password][second]' => 'nouveauMdp',
            'user[email]' => 'nouvel@email.fr'
        ]);

        $response = $this->client->submit($form);

        $manager = self::$container->get("doctrine.orm.entity_manager");
        $manager->refresh($user);

        $this->assertEquals('nouveauNom', $user->getUsername());
        $this->assertEquals('nouvel@email.fr', $user->getEmail());
    }

    // ajouter test refus différence mdp1 et mdp2
    public function testCreateRefuseDifferentPasswords()
    {
        $repository = self::$container->get(UserRepository::class);

        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'nom',
            'user[password][first]' => 'motDePasse',
            'user[password][second]' => 'autreMotDePasse',
            'user[email]' => 'email@mail.fr'
        ]);

        $this->client->submit($form);

        $nbUsers = count($repository->findAll());

        $this->assertEquals(0, $nbUsers);
    }

    // public function testEditRefuseDifferentPasswords()
    // {

    // }

    // ajouter tests validateurs, email etc, cf grafikart
}
