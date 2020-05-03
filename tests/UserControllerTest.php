<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use App\DataFixtures\UserTestEditFixtures;
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
    }

    private function initializeFixture()
    {
        self::bootKernel();

        $this->loadFixtures([UserTestEditFixtures::class]);
    }

    public function testUsersListResponse()
    {
        $crawler = $this->client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
    }

    public function testNoUserWhenStart()
    {
        $crawler = $this->client->request('GET', '/users');

        $nbElements = $crawler->filter('.btn-succes')->count();

        $this->assertEquals($nbElements, 0);
    }

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

    // public function testUserEdit()
    // {
        
    //     $this->initializeFixture();
        
    //     $repository = self::$container->get(UserRepository::class);
    //     $user = $repository->findAll()[0];
    //     $id = $user->getId();

    //     $crawler = $this->client->request('GET', '/users/' . $id . '/edit');

    //     $form = $crawler->selectButton('Modifier')->form([
    //         'user[username]' => 'nouveauNom',
    //         'user[password][first]' => 'nouveauMdp',
    //         'user[password][second]' => 'nouveauMdp',
    //         'user[email]' => 'nouvel@email.fr'
    //     ]);

    //     $this->client->submit($form);
    //     // $crawler = $this->client->followRedirect();

    //     $manager = self::$container->get("doctrine.orm.entity_manager");
    //     $manager->refresh($user);

    //     $this->assertEquals('nouveauNom', $user->getUsername());
    //     $this->assertEquals('nouveauMdp', $user->getPassword());
    //     $this->assertEquals('nouvel@email.fr', $user->getEmail());
    // }

    // ajouter test refus différence mdp1 et mdp2
    // public function testCreateRefuseDifferentPasswords()
    // {
    //     $repository = self::$container->get(UserRepository::class);

    //     $crawler = $this->client->request('GET', '/users/create');

    //     $form = $crawler->selectButton('Ajouter')->form([
    //         'user[username]' => 'nom',
    //         'user[password][first]' => 'motDePasse',
    //         'user[password][second]' => 'autreMotDePasse',
    //         'user[email]' => 'email@mail.fr'
    //     ]);

    //     $this->expectException(AuthenticationException::class);

    //     $this->client->submit($form);

    //     $this->client->followRedirect();

    //     $nbUsers = count($repository->findAll());

    //     $this->assertEquals(1, $nbUsers);
    // }

    // public function testEditRefuseDifferentPasswords()
    // {

    // }

    // ajouter tests validateurs, email etc, cf grafikart
}
