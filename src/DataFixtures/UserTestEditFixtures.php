<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserTestEditFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->encoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setPassword(
            $this->encoder->encodePassword(
                $user,
                'unMotDePasse'
            )
        );

        $user
            ->setUsername("nom")
            ->setEmail("e@mail.fr")
        ;

        $manager->persist($user);

        $manager->flush();
    }
}
