<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetailLangage
 *
 * @ORM\Table(name="detail_langage")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\DetailLangageRepository")
 */
class DetailLangage
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
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Langage")
     * @ORM\JoinColumn(nullable=false)
     */
    private $langage;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=5)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="modele", type="text")
     */
    private $modele;

    /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;
    
     /**
     * @var int
     *
     * @ORM\Column(name="order", type="integer")
     */
    private $ordre;

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
     * Set extension
     *
     * @param string $extension
     * @return DetailLangage
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set modele
     *
     * @param string $modele
     * @return DetailLangage
     */
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return string 
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return DetailLangage
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

    /**
<<<<<<< Updated upstream
=======
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return DetailLangage
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
>>>>>>> Stashed changes
     * Set langage
     *
     * @param \MainBundle\Entity\Langage $langage
     *
     * @return DetailLangage
     */
    public function setLangage(\MainBundle\Entity\Langage $langage)
    {
        $this->langage = $langage;

        return $this;
    }

    /**
     * Get langage
     *
     * @return \MainBundle\Entity\Langage
     */
    public function getLangage()
    {
        return $this->langage;
    }
}
