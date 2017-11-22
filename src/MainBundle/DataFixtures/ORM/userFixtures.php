<?php

namespace AppBundle\DataFixtures\ORM;
use MainBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

namespace AppBundle\DataFixtures\ORM;

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
           $encoder = $this->container->get('security.password_encoder');
           $password = $encoder->encodePassword($user, 'pass_1234');
           $user->setPassword($password);

    $manager->persist($user);
    $manager->flush();
    }
    
}
