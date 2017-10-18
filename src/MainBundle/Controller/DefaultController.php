<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

  /*Retourne un tableau avec les noms des langages stockés dans la BD*/
    private function getLangages(){
//TODO Recuperation dans la BD
      return array('C++' ,'C', 'Java');
    }

    public function indexAction()
    {
        $arr_lang = $this->getLangages();


        return $this->render('MainBundle:Default:index.html.twig', array(
          'list_langage' => $arr_lang,
          'selected_langage' => 'C++'
        ));
    }
}
