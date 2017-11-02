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
       
        $ssh->lancerCommande("docker stop --time=0 test");
        $output = $ssh->lire("docker stop --times=0 test");
        
        $ssh->lancerCommande("docker run --rm=true --name  test -it gpp  /bin/bash -c \"wget http://etudiant@192.168.1.92/LIDE/test.c 2>/dev/null && gcc test.c -o exec && ./exec\"");
        $output = $ssh->lire("docker run --rm=true --name  test -it gpp  /bin/bash -c \"wget http://etudiant@192.168.1.92/LIDE/test.c 2>/dev/null && gcc test.c -o exec && ./exec\"");
        
        return new Response($output);

    }
       
    //Permet de répondre aux programme (pas nécessairement appelée);
    
    public function answerAction(Request $request){
       

        $ssh = $this->get('gestionssh');


        $msg = $request->request->get('msg');

        $ssh->lancerCommande("docker start -ai test");
        $ssh->ecrire($msg);
        $output = $ssh->lire("docker start -ai test",$msg);
        
        return new Response($output);

    }

}

?>
