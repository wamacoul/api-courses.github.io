<?php

namespace App\DataFixtures;
use App\Entity\Customer;
use App\Entity\Invoice;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $chrono = 1;
        for($c=0; $c < 30; $c++){
            $customer = new Customer();
            $customer->setFirstName($faker->firstName())
                     ->setLastName($faker->lastName)
                     ->setCompagny($faker->company)
                     ->setEmail($faker->email);
            $manager->persist($customer);

            for($i=0; $i < mt_rand(3,10); $i++){
                $invoice = new Invoice();
                $invoice->setAmount($faker->randomFloat(2,250,5000))
                        ->setSentAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months','now',null)))
                        ->setStatus($faker->randomElement(['SENT', 'PAID', 'CANCELED']))
                        ->setChrono($chrono)
                        ->setCustomer($customer);
                $chrono++; 
                        
                $manager->persist($invoice);
            }
        }
        $manager->flush();
    }
}
