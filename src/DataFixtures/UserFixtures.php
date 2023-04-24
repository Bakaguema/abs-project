<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\CityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher, private CityRepository $cityRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        
      $cities = $this->cityRepository->findAll();
      
       

        for ($i=0; $i < 100; $i++) { 
            $user = new User();
            $cityIndex = array_rand($cities, 1);
            $user->setEmail('user' . $i . '@gmail.com');
            $user->setPassword($this->hasher->hashPassword($user, 'user'));
            $user->setFirstname('John');
            $user->setLastname('Doe');
            $user->setConditions(true);
            $user->setActive(true);
            $user->setIsSubscribe(false);
            $user->setCity($cities[$cityIndex]);
            $manager->persist($user);
        }
        $manager->flush();

    }
}
