<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MainBundle\Entity\Langage;
use MainBundle\Entity\DetailLangage;

use MainBundle\Entity\Execution;
use MainBundle\Form\ExecutionType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Psr\Log\LoggerInterface;

class DefaultController extends Controller
{

    private function matchLanguageToAce($id)
    {
        //CODE TEMPORAIRE TODO EXTRAIRE DANS UN FICHIER JSON
        $em = $this->getDoctrine()->getManager();
        $name = $em->getRepository('MainBundle:Langage')->find($id)->getNom();

        $name = strtoupper($name);

        switch ($name) {
            case 'C++':
                return 'ace/mode/c_cpp';
                break;
            case 'C':
                return 'ace/mode/c_cpp';
                break;
            case 'JAVA':
                return 'ace/mode/java';
            default:
                return 'ace/mode/plain_text';
                break;
        }
    }

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $langages = $em->getRepository('MainBundle:Langage')->findByActif(true);

        $selected_langage = $em->getRepository('MainBundle:Langage')->findOneBy(array('actif' => true));

        $info = $this->getLanguageInfo($selected_langage->getId());

        $logger = $this->get('logger');
        $logger->info(print_r($info, true));


        $exec = new Execution();
        $form = $this->createform(ExecutionType::class, $exec);

        return $this->render('MainBundle:Default:index.html.twig', array(
            'list_langage' => $langages,
            'selected_langage' => $selected_langage->getId(),
            'selected_langage_name' => $info['name'],
            'form' => $form->createView(),
        ));
    }

    /* Méthode appelé lors d'un changement de langage
     *
     */

    private function getLanguageInfo($id)
    {
        $em = $this->getDoctrine()->getManager();

        $lang = $em->getRepository('MainBundle:Langage')->find($id);

        $name = $lang->getNom();
        $compilateur = $lang->getCompilateur();

        $details = $em->getRepository('MainBundle:DetailLangage')->findByLangage($id);

        $detailThatMatter = array();
        foreach ($details as $d) {
            $detailThatMatter[] = array(
                'ext' => $d->getExtension(),
                'model' => $d->getModele()
            );
        }

        $logger = $this->get('logger');
        $logger->info(print_r($detailThatMatter, true));

        return array(
            'ace' => $this->matchLanguageToAce($id),
            'modeles' => $detailThatMatter,
            'name' => $name,
            'compilateur' => $compilateur
        );
    }

    public function languageInfoAction(Request $request)
    {
        /*
         Renvoie les info sous forme d'un json contenant
          'ace' -> paramètre pour l'editeur
          'model' -> fichier modèle pour le langage
        */
        if ($request->isXMLHttpRequest()) {

            $id = $request->request->get('lang');

            $info = $this->getLanguageInfo($id);

            $response = new JsonResponse();
            $response->setData($info);
            return $response;
        }
        return new Response('This is not ajax!', 400);
    }

}
