<?php

namespace MainBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of User
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser 
{    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\Regex(
     *  pattern="/^(.*)@(etud.)?univ-angers.fr$/",
     *  match=true,
     *  message="Mail invalide"
     * )
     */
    protected $email;

    /**
     * var $aceTheme
     * @ORM\Column(type="string")
     */
    protected $aceTheme = "tomorrow_night";

    /**
     * var $consoleTheme
     * @ORM\Column(type="string")
     */
    protected $consoleTheme = "dark";

    /**
     * var $sizeEditeur
     * @ORM\Column(type="integer")
     */
    protected $sizeEditeur = 12;
    
   
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTime $date=null)
    {
        $this->expiresAt = $date;
    } 
    
    public function setCredentialsExpireAt(\DateTime $date=null)
    {
        $this->expiresAt = $date;
    }  
    
    public  function getCredentialsExpireAt()
    {        
        return $this->credentialsExpireAt;
    }

    public function getAceTheme()
    {
        return $this->aceTheme;
    }

    public function setAceTheme($aceTheme)
    {
        $this->aceTheme = $aceTheme;
    }  

    public function getConsoleTheme()
    {
        return $this->consoleTheme;
    }

    public function setConsoleTheme($consoleTheme)
    {
        $this->consoleTheme = $consoleTheme;
    }  

    public function getSizeEditeur()
    {
        return $this->sizeEditeur;
    }

    public function setSizeEditeur($sizeEditeur)
    {
        $this->sizeEditeur = $sizeEditeur;
    }  
}
