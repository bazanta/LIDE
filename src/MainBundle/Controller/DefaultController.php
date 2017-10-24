<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MainBundle\Entity\Langage;
use MainBundle\Entity\DetailLangage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController extends Controller
{

    private function matchLanguageToAce($id){
      //CODE TEMPORAIRE TODO EXTRAIRE DANS UN FICHIER JSON
      $em = $this->getDoctrine()->getManager();
      $name = $em->getRepository('MainBundle:Langage')->findBy(array('id' => $id))[0]->getNom();

      switch ($name) {
        case 'C++':
          return 'ace/mode/c_cpp';
        break;
        case 'C':
          return 'ace/mode/c_cpp';
        break;
        default:
          return 'ace/mode/plain_text';
          break;
      }
    }

    private function getLanguageInfo($id){
      $em = $this->getDoctrine()->getManager();

      $model = $em->getRepository('MainBundle:DetailLangage')->findBy(array('langage' => $id))[0]->getModele();
      $name = $em->getRepository('MainBundle:Langage')->findBy(array('id' => $id))[0]->getNom();

      return array(
        'ace'=> $this->matchLanguageToAce($id),
        'model' => $model,
        'name' => $name
      );
    }



    public function indexAction()
    {
        $selected_langage = 1;
        $em = $this->getDoctrine()->getManager();

        $langages = $em->getRepository('MainBundle:Langage')->findBy(array('actif' => 1));

        $info = $this->getLanguageInfo($selected_langage);



        return $this->render('MainBundle:Default:index.html.twig', array(
          'list_langage' => $langages,
          'selected_langage' => $selected_langage,
          'selected_langage_name' => $info['name'],
          'model' => $info['model'],
          'ace_mode' => $info['ace']
        ));
    }

/* Méthode appelé lors d'un changement de langage
 *
 */

    public function languageInfoAction(Request $request){
      /*
       Renvoie les info sous forme d'un json contenant
        'ace' -> paramètre pour l'editeur
        'model' -> fichier modèle pour le langage
      */
      if($request->isXMLHttpRequest()){

        $id = $request->request->get('lang');

        $info = $this->getLanguageInfo($id);

        $response = new JsonResponse();
        $response->setData($info);
        return $response;
      }
      return new Response('This is not ajax!', 400);
    }
}
