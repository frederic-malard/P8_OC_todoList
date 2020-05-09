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

class RegistrationControllerTest extends WebTestCase
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

    // teste que les utilisateurs créés soient vus en base de données, et par la même occasion qu'un mot de passe correct soit accepté (complète les tests suivants)
    public function testUserSeenWhenCreated()
    {
        $repository = self::$container->get(UserRepository::class);

        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Ajouter')->form([
            'registration_form[username]' => 'nom',
            'registration_form[password][first]' => 'motDePasse1',
            'registration_form[password][second]' => 'motDePasse1',
            'registration_form[email]' => 'email@mail.fr'
        ]);

        $this->client->submit($form);

        $this->client->followRedirect();

        $nbUsers = count($repository->findAll());

        $this->assertEquals(1, $nbUsers);
    }

    // ajouter test refus différence mdp1 et mdp2
    public function testCreateRefuseDifferentPasswords()
    {
        $repository = self::$container->get(UserRepository::class);

        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Ajouter')->form([
            'registration_form[username]' => 'nom',
            'registration_form[password][first]' => 'motDePasse1',
            'registration_form[password][second]' => 'motDePasse2',
            'registration_form[email]' => 'email@mail.fr'
        ]);

        $this->client->submit($form);

        $nbUsers = count($repository->findAll());

        $this->assertEquals(0, $nbUsers);
    }

    // ajouter test refus mdp length < 6
    public function testCreateRefuseShortPasswords()
    {
        $repository = self::$container->get(UserRepository::class);

        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Ajouter')->form([
            'registration_form[username]' => 'nom',
            'registration_form[password][first]' => 'mdp1',
            'registration_form[password][second]' => 'mdp1',
            'registration_form[email]' => 'email@mail.fr'
        ]);

        $this->client->submit($form);

        $nbUsers = count($repository->findAll());

        $this->assertEquals(0, $nbUsers);
    }

    // ajouter test refus mdp qui ne contient pas de chiffres
    public function testCreateRefusePasswordsWithoutNumbers()
    {
        $repository = self::$container->get(UserRepository::class);

        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Ajouter')->form([
            'registration_form[username]' => 'nom',
            'registration_form[password][first]' => 'motDePasse',
            'registration_form[password][second]' => 'motDePasse',
            'registration_form[email]' => 'email@mail.fr'
        ]);

        $this->client->submit($form);

        $nbUsers = count($repository->findAll());

        $this->assertEquals(0, $nbUsers);
    }

    // ajouter test refus mdp qui ne contient pas de lettres
    public function testCreateRefusePasswordsWithoutLetters()
    {
        $repository = self::$container->get(UserRepository::class);

        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Ajouter')->form([
            'registration_form[username]' => 'nom',
            'registration_form[password][first]' => '1234567',
            'registration_form[password][second]' => '1234567',
            'registration_form[email]' => 'email@mail.fr'
        ]);

        $this->client->submit($form);

        $nbUsers = count($repository->findAll());

        $this->assertEquals(0, $nbUsers);
    }

    // ajouter test refus mdp qui contient un caractère interdit (autre chose que des chiffres et des lettres)
    public function testCreateRefusePasswordsWithoutForbiddenChars()
    {
        $repository = self::$container->get(UserRepository::class);

        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Ajouter')->form([
            'registration_form[username]' => 'nom',
            'registration_form[password][first]' => 'motDePasse&1',
            'registration_form[password][second]' => 'motDePasse&1',
            'registration_form[email]' => 'email@mail.fr'
        ]);

        $this->client->submit($form);

        $nbUsers = count($repository->findAll());

        $this->assertEquals(0, $nbUsers);
    }
}
