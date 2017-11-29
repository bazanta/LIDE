<?php

namespace AppBundle\DataFixtures\ORM;
use MainBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of userFixtures
 *
 * @author etudiant
 */
class userFixtures  extends Fixture{
    //put your code here
    
     public function load(ObjectManager $manager)
    {
           $user = new User();
           $user->setUsername('admin');
	   $user->setEmail("admin@etud.univ-angers.fr");
           $encoder = $this->container->get('security.password_encoder');
           $password = $encoder->encodePassword($user, 'admin');
           $user->setPassword($password);
	   $manager->persist($user);
			
	   $user1 = new User();
           $user1->setUsername('lide');
	   $user1->setEmail("lide@etud.univ-angers.fr");
           $encoder1 = $this->container->get('security.password_encoder');
           $password1 = $encoder1->encodePassword($user1, 'lide');
           $user1->setPassword($password1);
 	   $manager->persist($user1);
			
		$manager->flush();
    }
    
}
