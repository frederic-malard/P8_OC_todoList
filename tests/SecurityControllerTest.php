<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use App\DataFixtures\UserTestEditFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SecurityControllerTest extends WebTestCase
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

        $this->initializeFixture();
    }

    private function initializeFixture()
    {
        self::bootKernel();

        $this->loadFixtures([UserTestEditFixtures::class]);
    }

    public function testLoginCorrectDatas()
    {
        $repository = self::$container->get(UserRepository::class);

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'nom',
            'password' => 'unMotDePasse'
        ]);

        // $this->expectException(AuthenticationException::class);

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $nbAlertDanger = $crawler->filter('.alert-danger')->count();

        $this->assertEquals(0, $nbAlertDanger);
    }

    public function testLoginIncorrectDatas()
    {
        $repository = self::$container->get(UserRepository::class);

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'esegsrg',
            'password' => 'setsergs'
        ]);

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $nbBtnDanger = $crawler->filter('.alert-danger')->count();

        $this->assertEquals(1, $nbBtnDanger);
    }
}
