<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Personnel;

class PersonnelFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i < 10; $i++) { 
            $personne = new Personnel();
            $personne->setNomComplet("Babacar");
            $personne->setMatricule("mat45");
            $personne->setDateNaissance(new \DateTime());
            $personne->setSalaire(500000);

            $manager->persist($personne);
        }

        $manager->flush();
    }
    
}
