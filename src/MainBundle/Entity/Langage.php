<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Langage
 *
 * @ORM\Table(name="langage")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\LangageRepository")
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
     *
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="compilateur", type="string", length=255)
     */
    private $compilateur;

    /**
     * @var string
     *
     * @ORM\Column(name="dockerfile", type="text")
     */
    private $dockerfile;

    /**
     * @var string
     *
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
