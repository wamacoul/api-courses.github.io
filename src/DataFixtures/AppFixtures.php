<?php

namespace App\DataFixtures;
use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;

class AppFixtures extends Fixture
{
    /* 
     * l'encodeur de mots de passe
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        

        for($u =0; $u <10 ; $u++){
            $user = new User();
            $chrono = 1;
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setFirstName($faker->firstName())
                 ->setLastName($faker->lastName)
                 ->setEmail($faker->email)
                 ->setPassword($hash);
            
            $manager->persist($user);
            for($c=0; $c < mt_rand(5,20); $c++){
                $customer = new Customer();
                $customer->setFirstName($faker->firstName())
                        ->setLastName($faker->lastName)
                        ->setCompagny($faker->company)
                        ->setEmail($faker->email)
                        ->setUser($user);
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
        }
       
        $manager->flush();
    }
}
