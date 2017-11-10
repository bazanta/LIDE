<?php

/**
 * Classe Execution : Base pour les formulaire de demande de compilation/éxécution.
 *
 */
namespace MainBundle\Entity;

class Execution{

  protected $files; //À partir du JSON générer par le JavaScript, on obtient un tableau de tableau, T[i]['name'] pour le nom, T[i]['content'] pour le contenu du fichier i

  protected $inputMode; // 0 -> mode interactif, 1 -> rentré à l'avance par l'utilisateur. Mode sans input -> mode 1 avec $inputs chaine vide

  protected $inputs;

  protected $compileOnly;

  function getFiles(){
    return $this->files;
  }

  function getInputMode(){
    return $this->inputMode;
  }

  function getInputs(){
    return $this->inputs;
  }

  function isCompileOnly(){
    return $this->compileOnly;
  }

}

?>
