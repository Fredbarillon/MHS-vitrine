<?php

namespace App\DataFixtures;

use App\Entity\Boss;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

;

class AdminFixtures extends Fixture
{
// need to create a construct to hash password
public function __construct(
    private UserPasswordHasherInterface $passwordEncoder,
    private SluggerInterface $slugger
){}

private $adminRef;

public function load(ObjectManager $manager): void
{
    $admin =new Boss();
    $admin->setEmail("admin@test2.fr");
    $admin->setPassword(
        $this->passwordEncoder->hashPassword($admin, '12345678')
    );
    $admin->setRoles(['ROLES_ADMIN']);
    
    // add the admin ref
    $this->adminRef= $admin->getId();
    $this->addReference('admin-'.$this->adminRef, $admin);
    
    
    $manager->persist($admin);
    $manager->flush();
    }
}
