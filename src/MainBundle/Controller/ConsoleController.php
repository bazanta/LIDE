<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class ConsoleController extends Controller{


	public function indexAction(){

		$content=$this->get('templating')->render('MainBundle:Console:console.html.twig',array('retourssh'=>''));

		return new Response($content);


	}

	//Méthode appelée lors du clic sur le bouton Valider
	//Fait appel à la méthode du service ConnexionSSH qui permet de traiter une commande et de récupérer la réponse à cette commande

	public function traiterAction(Request $request){

		$gestionssh = $this->container->get('gestionssh');

		$cmd = $request->request->get('commande');


		$retourssh = $gestionssh->traiter($cmd);

		$content=$this->get('templating')->render('MainBundle:Console:console.html.twig',array('retourssh'=>$retourssh));



		return new Response($content);
	}

}

?>
