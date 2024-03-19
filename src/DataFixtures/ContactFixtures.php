<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 15; $i++) {
            $contact = new Contact();
            $contact->setFirstName($faker->firstName);
            $contact->setLastName($faker->lastName);
            $contact->setEmail($faker->email);
            $contact->setTelephone('0604525842');
            $contact->setAddress($faker->streetAddress);
            $contact->setCity($faker->city);
            $contact->setZipCode(str_replace(' ', '', $faker->postcode));
            $contact->setMessage($faker->text(500));

            // Set contact type based on $i
            if ($i <= 5) {
                $contact->setBuyer(true);
                $contact->setSeller(false);
                $contact->setJustWantInfo(false);
            } elseif ($i <= 10) {
                $contact->setSeller(true);
                $contact->setBuyer(false);
                $contact->setJustWantInfo(false);
            } else {
                $contact->setSeller(false);
                $contact->setBuyer(false);
                $contact->setJustWantInfo(true);
            }

            $this->addReference('contact-' . $i, $contact);
            $manager->persist($contact);
        }

        $manager->flush();
    }
}
