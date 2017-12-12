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

            $content = file_get_contents("src/MainBundle/Resources/script/exec_cpp.sh");
            $langage->setScript($content);

            $langage->setActif(1);
            $manager->persist($langage);

            $langage1 = new Langage();
            $langage1->setNom("c");
            $langage1->setCompilateur("gcc");
            $langage1->setDockerfile("titi");
            $langage1->setScript("tete");
            $langage1->setActif(0);
            $manager->persist($langage1);

			$manager->flush();
			$this->addReference('language-cpp', $langage);
			$this->addReference('language-c', $langage1);
    }
}