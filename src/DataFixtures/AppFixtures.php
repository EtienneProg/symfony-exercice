<?php

namespace App\DataFixtures;

use App\Entity\Prestation;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $user = new User();
        $user->setEmail('user@gmail.com');
        $user->setNom($faker->lastName());
        $user->setDateInscription(new DateTime());
        $user->setPrenom($faker->FirstName());
        $user->setPassword($this->userPasswordHasher->hashPassword($user, '123'));
        $user->setRoles(['ROLE_USER']);


        for ($i = 0; $i < 10; $i++) {
            $presta = new Prestation();
            $presta->setNom($faker->name());
            $presta->setDateCommande($faker->dateTime);
            $presta->setExtrait($faker->text(50));
            $presta->setDescription($faker->text(255));
            $presta->setTel("0" . strval(rand(6, 7)) . str_pad(strval(rand(0, 99999999)), 8, "0", STR_PAD_LEFT));
            $presta->setRemuneration($faker->randomFloat(2, 0, 10000));
            $manager->persist($presta);
        }

        for ($i = 0; $i < 4; $i++) {
            $presta = new Prestation();
            $presta->setNom($faker->name());
            $presta->setDateCommande($faker->dateTime);
            $presta->setExtrait($faker->text(50));
            $presta->setDescription($faker->text(255));
            $presta->setTel("0" . strval(rand(6, 7)) . str_pad(strval(rand(0, 99999999)), 8, "0", STR_PAD_LEFT));
            $presta->setRemuneration($faker->randomFloat(2, 0, 10000));
            $presta->setUser($user);
            $user->addPrestation($presta);
            $manager->persist($presta);
        }

        $manager->persist($user);
        $manager->flush();
    }
}
