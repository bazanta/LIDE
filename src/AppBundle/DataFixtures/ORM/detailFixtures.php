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
           // $detaillangage->setLangage($langage)
            $detaillangage->setExtension(".cpp");
            $detaillangage->setModele("Hellow word");
            $detaillangage->setActif(1);
            $detaillangage->setOrdre(0);
             $detaillangage->
           
            $manager->persist($detaillangage);
         
            $detaillangage1 = new DetailLangage();
            $detaillangage1->setExtension(".c");
            $detaillangage1->setModele("Hellow word!");
            $detaillangage1->setActif(0);
            $detaillangage1->setOrdre(1);
          
           
            $manager->persist($detaillangage1);
       

        $manager->flush();
    }
}
