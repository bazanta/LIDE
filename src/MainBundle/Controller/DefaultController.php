<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MainBundle\Entity\Langage;


class DefaultController extends Controller
{


    public function indexAction()
    {
        $selected_langage = 0;
        $em = $this->getDoctrine()->getManager();

        $langages = $em->getRepository('MainBundle:Langage')->findBy(array('actif' => 1));

        $selected_langage_name = $em->getRepository('MainBundle:Langage')->findBy(array('id' => 1))[0]->getNom();

        return $this->render('MainBundle:Default:index.html.twig', array(
          'list_langage' => $langages,
          'selected_langage' => $selected_langage,
          'selected_langage_name' => $selected_langage_name
        ));
    }
}
