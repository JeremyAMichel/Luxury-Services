<?php

namespace App\DataFixtures;

use App\Entity\Candidate;
use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public const USERS = [
        'admin' => [
            'email' => 'admin@admin.com',
            'roles' => ['ROLE_ADMIN'],
            'password' => 'adminadmin',
            'isVerified' => true
        ],
        'test' => [
            'email' => 'test@test.com',
            'roles' => ['ROLE_USER'],
            'password' => 'testtest',
            'isVerified' => true
        ],
        // Ajout des utilisateurs test supplémentaires
        'test2' => ['email' => 'test2@test2.com', 'roles' => ['ROLE_USER'], 'password' => 'test2test2', 'isVerified' => true],
        'test3' => ['email' => 'test3@test3.com', 'roles' => ['ROLE_USER'], 'password' => 'test3test3', 'isVerified' => true],
        'test4' => ['email' => 'test4@test4.com', 'roles' => ['ROLE_USER'], 'password' => 'test4test4', 'isVerified' => true],
        'test5' => ['email' => 'test5@test5.com', 'roles' => ['ROLE_USER'], 'password' => 'test5test5', 'isVerified' => true],
        'test6' => ['email' => 'test6@test6.com', 'roles' => ['ROLE_USER'], 'password' => 'test6test6', 'isVerified' => true],

        'notverified' => [
            'email' => 'notverified@notverified.com',
            'roles' => ['ROLE_USER'],
            'password' => 'notverified',
            'isVerified' => false
        ],
        'deletedone' => [
            'email' => 'deletedone@deletedone.com',
            'roles' => ['ROLE_USER'],
            'password' => 'deletedone',
            'isVerified' => true
        ],
        // Ajout des recruteurs supplémentaires
        'recruiter' => ['email' => 'recruiter@recruiter.com', 'roles' => ['ROLE_RECRUITER'], 'password' => 'recruiter', 'isVerified' => true],
        'recruiter2' => ['email' => 'recruiter2@recruiter2.com', 'roles' => ['ROLE_RECRUITER'], 'password' => 'recruiter2', 'isVerified' => true],
        'recruiter3' => ['email' => 'recruiter3@recruiter3.com', 'roles' => ['ROLE_RECRUITER'], 'password' => 'recruiter3', 'isVerified' => true],
        'recruiter4' => ['email' => 'recruiter4@recruiter4.com', 'roles' => ['ROLE_RECRUITER'], 'password' => 'recruiter4', 'isVerified' => true],
        'recruiter5' => ['email' => 'recruiter5@recruiter5.com', 'roles' => ['ROLE_RECRUITER'], 'password' => 'recruiter5', 'isVerified' => true],
        'recruiter6' => ['email' => 'recruiter6@recruiter6.com', 'roles' => ['ROLE_RECRUITER'], 'password' => 'recruiter6', 'isVerified' => true],
        'recruiter7' => ['email' => 'recruiter7@recruiter7.com', 'roles' => ['ROLE_RECRUITER'], 'password' => 'recruiter7', 'isVerified' => true],
        'recruiter8' => ['email' => 'recruiter8@recruiter8.com', 'roles' => ['ROLE_RECRUITER'], 'password' => 'recruiter8', 'isVerified' => true],
        'recruiter9' => ['email' => 'recruiter9@recruiter9.com', 'roles' => ['ROLE_RECRUITER'], 'password' => 'recruiter9', 'isVerified' => true],
        'recruiter10' => ['email' => 'recruiter10@recruiter10.com', 'roles' => ['ROLE_RECRUITER'], 'password' => 'recruiter10', 'isVerified' => true],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $key => $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setRoles($userData['roles']);
            $user->setIsVerified($userData['isVerified']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $userData['password']
            );
            $user->setPassword($hashedPassword);

            $manager->persist($user);

            // Créer une référence pour une utilisation ultérieure si nécessaire
            $this->addReference('user_' . $key, $user);
        }

        $manager->flush();
    }
}
