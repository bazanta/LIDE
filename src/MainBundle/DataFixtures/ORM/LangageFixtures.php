<?php

namespace AppBundle\DataFixtures\ORM;
use MainBundle\Entity\Langage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of Fixtures
 *
 * @author etudiant
 */

class LangageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
               
            $langage = new Langage();
            $langage->setNom("c++");
            $langage->setCompilateur("g++");
            $langage->setDockerfile("toto");
            $langage->setScript("tata");
            $langage->setActif(1);
            $this->addReference('c', $langage);
            $manager->persist($langage);
            
            $langage1 = new Langage();
            $langage1->setNom("c");
            $langage1->setCompilateur("gcc");
            $langage1->setDockerfile("titi");
            $langage1->setScript("tete");
            $langage1->setActif(0);
            $this->addReference('c', $langage1);
            $manager->persist($langage1);
       

        $manager->flush();
    }
}