<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\DataFixtures\ORM;
use MainBundle\Entity\Serveur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of ServerFixture
 *
 * @author etudiant
 */
class ServerFixture extends Fixture{
     public function load(ObjectManager $manager)
    {
               
               
            $server = new Serveur();
            
            $server->setNom("server1");
            $server->setAdresse("adresse_server");
            $server->setUsername("username1");
            $server->setPassword("pass1");
            $server->setActif("1");
            $manager->persist($server);
            
            $server1 = new Serveur();
          
            $server1->setNom("server2");
            $server1->setAdresse("adresse_server2");
            $server1->setUsername("username2");
            $server1->setPassword("pass2");
            $server1->setActif("0");
            $manager->persist($server1);
       

	    $manager->flush();
			
    }
}
