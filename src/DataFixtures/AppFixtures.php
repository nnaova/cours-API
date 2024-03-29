<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Materiel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create("fr_FR");
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    { 
        // Public
        $publicUser = new User();
        $publicUser->setUsername("public");
        $publicUser->setRoles(["ROLE_PUBLIC"]);
        $publicUser->setPassword($this->userPasswordHasher->hashPassword($publicUser, "public"));
        $manager->persist($publicUser);
        
        // Authentifiés
        for ($i = 0; $i < 5; $i++) {
            $userUser = new User();
            $password = $this->faker->password(2, 6);
            $userUser->setUsername($this->faker->userName() . "@". $password);
            $userUser->setRoles(["ROLE_USER"]);
            $userUser->setPassword($this->userPasswordHasher->hashPassword($userUser, $password));
            $manager->persist($userUser);
        }

        // Admins
        $adminUser = new User();
        $adminUser->setUsername("admin");
        $adminUser->setRoles(["ROLE_ADMIN"]);
        $adminUser->setPassword($this->userPasswordHasher->hashPassword($adminUser, "password"));
        $manager->persist($adminUser);

        $materielEnties = [];
        for ($i=0; $i <= 100; $i++) { 
            $materielEntry = new Materiel();
            $materielEntry->setName($this->faker->realtext(10));
            $materielEntry->setType(rand(1, 3));
            $materielEntry->setAvailable(array_rand([true, false]));
            $materielEntry->setCreatedAt(new \DateTimeImmutable());
            $materielEntry->setUpdatedAt(new \DateTimeImmutable());
            $materielEntry->setStatus('En stock');
            $manager->persist($materielEntry);
            $manager->flush();
        }
    }
}
