<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $user1= new User();
        $user1->setEmail('admin@gmail.com');
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setPassword($this->passwordEncoder->hashPassword($user1, 'admin123'));
      
        $manager->persist($user1);

        $user2= new User();
        $user2->setEmail('user@gmail.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->passwordEncoder->hashPassword($user2, 'user123'));
      
        $manager->persist($user2);
        $manager->flush();
    }
}
