<?php

namespace AppBundle\DataFixtures\ORM;
use MainBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class userFixtures extends Fixture
{    
    public function load(ObjectManager $manager)
    {        
        $encoder = $this->container->get('security.password_encoder');
        $userManager = $this->container->get('fos_user.user_manager');

        //$user = new User();
        $user = $userManager->createUser();
        $user->setUsername('admin');
        $user->setEmail("admin@etud.univ-angers.fr");
        $password = $encoder->encodePassword($user, 'admin');
        $user->setPassword($password);
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));
        $manager->persist($user);
			
	    $user1 = new User();
        $user1->setUsername('lide');
	    $user1->setEmail("lide@etud.univ-angers.fr");
        $password1 = $encoder->encodePassword($user1, 'lide');
        $user1->setPassword($password1);
        $user1->setEnabled(true);
        $user1->setRoles(array('ROLE_USER'));
 	    $manager->persist($user1);
			
		$manager->flush();
    }
    
}
