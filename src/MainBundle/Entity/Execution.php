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

  protected $launchParameters;

  protected $compilationOptions;

  protected $language;

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

  function getLaunchParameters(){
    return $this->launchParameters;
  }

  function getLanguage(){
    return $this->language;
  }

  function getCompilationOptions(){
    return $this->compilationOptions;
  }

  function setCompilationOptions($compilationOptions){
    $this->compilationOptions = $compilationOptions;
  }


  function setFiles($files){
    $this->files = $files;
  }

  function setInputMode($mode){
    $this->inputMode = $mode;
  }

  function setInputs($inputs){
    $this->inputs = $inputs;
  }

  function setCompileOnly($compileOnly){
    $this->compileOnly = $compileOnly;
  }

  function setLaunchParameters($launchParameters){
    $this->launchParameters = $launchParameters;
  }

  function setLanguage($language){
    $this->language = $language;
  }


}

?>
