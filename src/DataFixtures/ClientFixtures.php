<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Dette;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <=20 ; $i++) { 
            $client=new Client();
            $client->setSurname("surname".$i);
            $client->setTelephone("7754678".$i);
            $client->setAdresse("adresse".$i);
            if ($i%2==0) {
                $user=new User();
                $user->setLogin("login".$i);
                $user->setPassword("password".$i);
                $user->setNom("nom".$i);
                $user->setPrenom("prenom".$i);
                $client->setCompte($user);
                for ($j=0; $j <=$i ; $j++) { 
                    $dette=new Dette();
                    $dette->setCreateAt(new \DateTimeImmutable());
                    $dette->setMontant(1000*$i*$j);
                    if ($j%2 ==0) {
                        $dette->setMontantVerser(1000*$j*$i-1000);
                    }else {
                           $dette->setMontantVerser(1000*$j*$i);
                        }
                    
                }
            }
            $manager->persist($client);

        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
