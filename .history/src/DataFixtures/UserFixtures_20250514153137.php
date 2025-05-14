<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }
    
    /**@throws Exception */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
        $user = (new User())
            ->setFirstName("Firstname $i")
            ->setLastName("Lastname $i")
            ->setGuestNumber(random_int(0,10))
            ->setEmail("email.$i@mail.fr")
            ->setCreatedAt(new DateTimeImmutable());

            $user->setPassword("hash");

        $manager->persist($user);
        }
        $manager->flush();
    }
}
