<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Langage
 *
 * @ORM\Table(name="langage")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\LangageRepository") 
 * @UniqueEntity(fields={"nom"}, errorPath="nom", message="Ce langage est déjà enregistré")
 * @UniqueEntity(fields={"dockerName"}, errorPath="dockerName", message="Une autre image docker porte déjà ce nom")
 */
class Langage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string     
     * @ORM\Column(name="options", type="string", length=255, nullable=true)
     */
    private $options;

    /**
     * @var string     
     * @Assert\NotBlank()
     * @ORM\Column(name="compilateur", type="string", length=255)
     */
    private $compilateur;

    /**
     * @var string      
     * @Assert\NotBlank()
     * @ORM\Column(name="dockerfile", type="text")
     */
    private $dockerfile;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="dockerName",  type="string", length=50)
     */
    private $dockerName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="script", type="text")
     */
    private $script;

    /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Function for display this object
     */
    public function __toString() 
    {
        return $this->nom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Langage
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set nom
     *
     * @param string $options
     * @return Langage
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options
     *
     * @return string 
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set compilateur
     *
     * @param string $compilateur
     * @return Langage
     */
    public function setCompilateur($compilateur)
    {
        $this->compilateur = $compilateur;

        return $this;
    }

    /**
     * Get compilateur
     *
     * @return string 
     */
    public function getCompilateur()
    {
        return $this->compilateur;
    }

    /**
     * Set dockerfile
     *
     * @param string $dockerfile
     * @return Langage
     */
    public function setDockerfile($dockerfile)
    {
        $this->dockerfile = $dockerfile;

        return $this;
    }

    /**
     * Get dockerfile
     *
     * @return string 
     */
    public function getDockerfile()
    {
        return $this->dockerfile;
    }

    /**
     * Set dockerName
     *
     * @param string $dockerName
     * @return Langage
     */
    public function setDockerName($dockerName)
    {
        $this->dockerName = $dockerName;

        return $this;
    }

    /**
     * Get dockerName
     *
     * @return string 
     */
    public function getDockerName()
    {
        return $this->dockerName;
    }

    /**
     * Set script
     *
     * @param string $script
     * @return Langage
     */
    public function setScript($script)
    {
        $this->script = $script;

        return $this;
    }

    /**
     * Get script
     *
     * @return string 
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Langage
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }
}
