<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $userData = array(
            0 => array(
                'email' => 'user@user.com',
                'password' => 'pass',
                'role' => ['ROLE_USER'],
            ),
            1 => array(
                'email' => 'admin@admin.com',
                'password' => 'pwd',
                'role' => ['ROLE_ADMIN'],
            )
        );

        foreach ($userData as $user) {
            $newUser = new User();
            $newUser->setEmail($user['email']);
            $newUser->setPassword($this->encoder->hashPassword($newUser, $user['password']));
            $newUser->setRoles($user['role']);
            $manager->persist($newUser);

        }

        $manager->flush();
    }
}
