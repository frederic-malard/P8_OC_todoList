<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserTestEditFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user
            ->setUsername("nom")
            ->setEmail("e@mail.fr")
            ->setPassword("unMotDePasse")
        ;

        $manager->persist($user);

        $manager->flush();
    }
}
