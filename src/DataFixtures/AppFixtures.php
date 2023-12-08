<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Materiel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create("fr_FR");
    }
    public function load(ObjectManager $manager): void
    { 
        $users = [];
        $publicuser = new User();
        $publicuser->setUsername('public@public');
        $publicuser->setRoles(['USER']);
        $publicuser->setPassword();


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
        for ($i=0 ; $i <= 10 ; $i++) {
            $materielEntry = new Materiel();
            $materielRef = $materielEnties[array_rand($materielEnties,1)];
            $materielEntry->setName($materielRef->getName());
            $materielEntry->setType($materielRef->getType());
            $materielEntry->setAvailable($materielRef->getAvailable());
            $materielEntry->setCreatedAt(new \DateTimeImmutable());
            $materielEntry->setUpdatedAt(new \DateTimeImmutable());
            $materielEntry->setStatus($materielRef->getStatus());
        }
    }
}
