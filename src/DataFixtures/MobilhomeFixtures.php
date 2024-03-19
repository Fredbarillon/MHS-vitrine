<?php

namespace App\DataFixtures;

use App\Entity\Mobilhome;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

;

class MobilhomeFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger){}

  
    private $counter=1;

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= 11; $i++) {
            $mobilhome = new Mobilhome();
            $mobilhome->setLength($faker->randomFloat(1, 1, 20));
            $mobilhome->setWidth($faker->randomFloat(1, 1, 5));
            $mobilhome->setBrand($faker->text(15));
            $mobilhome->setModel($faker->text(20));
            $mobilhome->setYear($faker->numberBetween(1998,2023));
            $mobilhome->setNbBedroom($faker->numberBetween(1,4));
            $mobilhome->setPrice($faker->randomFloat(2, 3500, 30000));
            if ($i == 1 or $i == 5 or $i == 8) {
            
                $mobilhome->setCentralLivingroom(0);
              
            } else {
                $mobilhome->setCentralLivingroom(1);
                
            }
            $mobilhome->setAC($faker->numberBetween(0,1));
            $mobilhome->setDoubleGlazing($faker->numberBetween(0,1));
            $mobilhome->setOven($faker->numberBetween(0,1));
            
            $mobilhome->setStock($faker->numberBetween(0,7));

            $mobilhome->setCreatedAt(new \DateTimeImmutable());
            // get the admin id
            $adminReferenced = $this->getReference('admin-');
            $mobilhome->setBoss($adminReferenced);

      


            // get the right slug
            $slugConcat = $mobilhome->getBrand() . '.' . $mobilhome->getModel() . '.' . $mobilhome->getYear();
            $mobilhome->setSlug($this->slugger->slug($slugConcat)->lower());
           
            $this->setReference('mobilhomeref-'. $this->counter, $mobilhome);
            $manager->persist($mobilhome);
            $this->counter++;
            
            // add the mobilhome ref
            
            
        }
        $manager->flush();
    }
}
