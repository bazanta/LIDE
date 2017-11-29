<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MainBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of User
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @author etudiant
 */
class User extends BaseUser {
    //put your code here
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
   

    
   
    public function __construct()
    {
        parent::__construct();
        // your own logic
    
    }
    
     public function getId(){
        return $this->id;
    }
    
    public function getExpiresAt(){
        return $this->expiresAt;
    }
    
    
    public  function getCredentialsExpireAt(){
        
          return $this->credentialsExpireAt;
    }
    
      public function setExpiresAt(\DateTime $date)
    {
        $this->expiresAt = $date;
    
    }
    public function setEmail($email){

        $this->checkEmail($email);
        return parent::setEmail($email);
       }

public function setEmailCanonical($emailCanonical) {

 $this->checkEmail($emailCanonical);
 return parent::setEmailCanonical($emailCanonical);
}

private function checkEmail($email) {

 if(!empty($email) 
    && filter_var($email, FILTER_VALIDATE_EMAIL) 
    && (preg_match("/^(.*)@etud.univ-angers.fr$/",$email) || preg_match("/^(.*)@univ-angers.fr$/",$email))) { 
  return $email;  
 } else {
  throw new \Exception('Email invalide');
 }

}


   
}
