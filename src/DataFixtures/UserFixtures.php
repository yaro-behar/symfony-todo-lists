<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private const USER_PASSWORD = 'qwerty';

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@domain.com");
            $user->setRoles([User::ROLE_USER]);
            $user->setPassword($this->passwordEncoder->encodePassword($user, self::USER_PASSWORD));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
