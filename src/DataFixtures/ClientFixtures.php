<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Dette;
use App\Entity\Paiement;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <=20; $i++) { 
            $client= new Client();
            $client->setSurname("surname".$i);
            $client->setAdresse("adresse".$i);
            $client->setTelephone("77100101".$i);
            if ($i%2==0) {
                $user= new User();
                $user->setLogin('login'.$i);
                $user->setPassword('password');
                $user->setNom('nom'.$i);
                $user->setPreNom('prenom'.$i);
                $client->setUser($user);

                for ($j=1; $j <=$i ; $j++) { 
                    $dette=new Dette();
                    $dette->setDateAt(new \DateTimeImmutable());
                    $dette->setMontant(1000*$j*$i);
                    if ($j % 2 ==0) {
                    $dette->setMontantVerse(1000*$j*$i-1000);
                    $paiement1=new Paiement();
                    $paiement1->setMontant(1000);
                    $dette->addPaiement($paiement1);
                    
                    $paiement2=new Paiement();
                    $paiement2->setMontant(1000*$j*$i-2000);
                    $dette->addPaiement($paiement2);
                    }else {
                        $dette->setMontantVerse(1000*$j*$i);
                        $paiement=new Paiement();
                        $paiement->setMontant(1000*$j*$i);
                        $dette->addPaiement($paiement);
                    }
                    $client->addDette($dette);

                    
                }
            }
            $manager->persist($client);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}