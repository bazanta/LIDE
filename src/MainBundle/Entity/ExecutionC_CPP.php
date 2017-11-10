<?php
/**
 * Classe Execution : Base pour les formulaire de demande de compilation/éxécution pour le C et C++
 *
 */



namespace MainBundle\Entity;



class ExecutionC_CPP extends Execution{

  protected $warningLevel;
  protected $optimisationLevel;

  function getOptimisationLevel(){
    return $this->optimisationLevel;
  }

  function setOptimisationLevel($optimisationLevel){
    $this->optimisationLevel = $optimisationLevel;
  }

  function getWarningLevel(){
    return $this->warningLevel;
  }

  function setWarningLevel($warningLevel){
    $this->warningLevel = $warningLevel;
  }
}
?>
