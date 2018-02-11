<?php


namespace AppBundle\DataFixtures\ORM;
use MainBundle\Entity\DetailLangage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Description of detailFixtures
 *
 * @author etudiant
 */
class detailFixtures extends Fixture {
    
      public function load(ObjectManager $manager)
    {
               
            $detaillangage = new DetailLangage();
            $detaillangage->setLangage($this->getReference('language-cpp'));
            $detaillangage->setExtension("cpp");
            $detaillangage->setModele(file_get_contents("src/MainBundle/Resources/modeles/cpp/whatsyourname.cpp"));
            $detaillangage->setActif(1);
            $detaillangage->setOrdre(0);
			
           
            $manager->persist($detaillangage);
         
            $detaillangage1 = new DetailLangage();
            $detaillangage1->setExtension("c");
			$detaillangage1->setLangage($this->getReference('language-c'));
            $detaillangage1->setModele(file_get_contents("src/MainBundle/Resources/modeles/c/whatsyourage.c"));
            $detaillangage1->setActif(1);
            $detaillangage1->setOrdre(1);
          
           
            $manager->persist($detaillangage1);

            $detaillangage1 = new DetailLangage();
            $detaillangage1->setExtension("java");
			$detaillangage1->setLangage($this->getReference('language-java'));
            $detaillangage1->setModele(file_get_contents("src/MainBundle/Resources/modeles/java/helloworld.java"));
            $detaillangage1->setActif(0);
            $detaillangage1->setOrdre(2);
          
           
            $manager->persist($detaillangage1);
       

        $manager->flush();
    }
}
