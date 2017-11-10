<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class ConsoleController extends Controller{
    
    

    public function indexAction(){

        $content=$this->get('templating')->render('MainBundle:Console:console.html.twig',array(""));
        
        $ssh = $this->get('gestionssh');

        return new Response($content);


    }
    
    
    //Méthode appelée par la vue console
    //Première méthode appelée après l'appui sur le bouton RUN

    public function execAction(Request $request){
        $logger = $this->get('logger');
        $ssh = $this->get('gestionssh');
        
        $id_user = "test";
        $ip_proxy = "172.29.13.12";
        $fichier = "test.c";
        
        $cmd = "docker stop --time=0 test";
       
        $ssh->execCmd($cmd);
        $output = $ssh->lire();
        
        $cmd = "docker run --rm=true --name  $id_user -it gpp  /bin/bash -c \"wget http://etudiant@$ip_proxy/LIDE/$fichier 2>/dev/null && gcc $fichier -o exec && ./exec\"";
        
        
        $ssh->execCmd($cmd);
        $output = $ssh->lire();
        
        
        $response = array(
            'reponse' => $output[0],
            'fin' => $output[1]
        );
        
        $logger->info("Réponse : "+$reponse);
        return new Response(json_encode($response));

    }
       
    //Permet de répondre aux programme (pas nécessairement appelée);
    
    public function answerAction(Request $request){
       

        $ssh = $this->get('gestionssh');

        $msg = $request->request->get('msg');

        $ssh->execCmd("docker start -ai test");
        $ssh->ecrire($msg);
        $output = $ssh->lire();
        
        $response = array(
            'reponse' => $output[0],
            'fin' => $output[1]
        );
        
        return new Response(json_encode($response));

    }

}

?>
