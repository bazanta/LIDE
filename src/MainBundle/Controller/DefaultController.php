<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MainBundle\Entity\Langage;
use MainBundle\Entity\DetailLangage;

use MainBundle\Entity\Execution;
use MainBundle\Form\ExecutionType;
use MainBundle\Form\OptionsInterfaceType;

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

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');              

        // Création du formulaire d'exécution
        $exec = new Execution();
        $form = $this->createform(ExecutionType::class, $exec);

        // Création formulaire paramètrage interface
        $user = $this->getUser();
        $formInterface = $this->createform(OptionsInterfaceType::class, $user);

        // Récupération des langages
        $langages = $em->getRepository('MainBundle:Langage')->findByActif(true);  

        // Récupération du code en session
        $jsonFiles = $request->getSession()->get('files'.$user->getId());

        // Récupération du langage
        $langage = $request->getSession()->get('langage'.$user->getId());
        if ($langage == null) {
            $selected_langage = $em->getRepository('MainBundle:Langage')->findOneBy(array('actif' => true));
            $langage = $selected_langage->getId();
        }
        $info = $this->getLanguageInfo($langage);
        $logger->info(print_r($info, true));

        return $this->render('MainBundle:Default:index.html.twig', array(
            'list_langage' => $langages,
            'selected_langage_name' => $info['name'],
            'form' => $form->createView(),
            'formInterface' => $formInterface->createView(),
            'jsonFiles' => json_encode($jsonFiles),
            'langage' => $langage
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

    /**
     * Renvoie les info du langage sous forme d'un json contenant
     *   'ace' -> paramètre pour l'editeur
     *   'model' -> fichier modèle pour le langage
     */
    public function languageInfoAction(Request $request)
    {        
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('lang');
            $info = $this->getLanguageInfo($id);

            return new JsonResponse($info);
        }
        return new Response('This is not ajax!', 400);
    }

    public function saveCodeAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $userID = $this->getUser()->getId();
            $jsonFiles = $request->request->get('files');
            $request->getSession()->set('files'.$userID, json_decode($jsonFiles));

            $langage = $request->request->get('langage');
            $request->getSession()->set('langage'.$userID, $langage);


            return new JsonResponse("OK");
        }
        return new Response('This is not ajax!', 400);
    }

    public function updateInterfaceAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {

            $user = $this->getUser();
            $form= $this->createform(OptionsInterfaceType::class, $user);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $rep = "OK";
            } else {
                $rep = "Formulaire non valide";
            }        
            
            return new JsonResponse($rep);
        }
        return new Response('This is not ajax!', 400);
    }

}
