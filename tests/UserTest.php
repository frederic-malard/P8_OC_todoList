<?php

namespace App\Tests;

use App\Entity\User;
use App\DataFixtures\UserTestEditFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    use FixturesTrait;

    private function initializeFixture()
    {
        self::bootKernel();

        $this->loadFixtures([UserTestEditFixtures::class]);
    }

    public function testRoleDefault()
    {
        $user = new User();

        $this->assertEquals($user->getRoles(), ["ROLE_USER"]);
    }

    public function testRoleUser()
    {
        $user = new User();

        $user->setRoles(["ROLE_USER"]);

        $this->assertEquals($user->getRoles(), ["ROLE_USER"]);
    }

    public function testRoleAdmin()
    {
        $user = new User();

        $user->setRoles(["ROLE_ADMIN"]);

        $this->assertContains("ROLE_ADMIN", $user->getRoles());
    }

    public function testRefuseTwiceSameUsername()
    {
        $this->initializeFixture();

        $user = new User();

        $user
            ->setUsername("nom")
            ->setEmail("e@mail.fr")
            ->setPassword("unMotDePasse")
        ;

        self::bootKernel();

        $nbErrors = count(self::$container->get('validator')->validate($user));

        $this->assertNotEquals(0, $nbErrors);
    }

    public function testAcceptDifferentUsername()
    {
        $this->initializeFixture();

        $user = new User();

        $user
            ->setUsername("nomDifferent")
            ->setEmail("e@mail.fr")
            ->setPassword("unMotDePasse")
        ;

        self::bootKernel();

        $errors = self::$container->get('validator')->validate($user);

        $this->assertCount(0, $errors);
    }

    public function testBlankEmailRefused()
    {
        $this->initializeFixture();

        $user = new User();

        $user
            ->setUsername("nomDifferent")
            ->setEmail("")
            ->setPassword("unMotDePasse")
        ;

        self::bootKernel();

        $nbErrors = count(self::$container->get('validator')->validate($user));

        $this->assertNotEquals(0, $nbErrors);
    }

    public function testWrongEmailRefused()
    {
        $this->initializeFixture();

        $user = new User();

        $user
            ->setUsername("nomDifferent")
            ->setEmail("emailInvalide")
            ->setPassword("unMotDePasse")
        ;

        self::bootKernel();

        $nbErrors = count(self::$container->get('validator')->validate($user));

        $this->assertNotEquals(0, $nbErrors);
    }

    // no test for normal email : already tested testAcceptDifferentUsername
}
