<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class ConsoleController extends Controller{
    

    
    

    public function indexAction(){

        

        $content=$this->get('templating')->render('MainBundle:Console:console.html.twig',array(""));

        return new Response($content);


    }
        
        


        public function inputAction(Request $request){
            $logger = $this->get('logger');
        
            $ssh = $this->container->get('gestionssh');


            $msg = $request->request->get('msg');

            $ssh->ecrire($msg);
            
            return new Response();

	}
        
        public function outputAction(Request $request){
            $logger = $this->get('logger');
            $ssh = $this->container->get('gestionssh');
            
            $output = $ssh->lire();
            $logger->info("Log output : ".$output);
            if($output){
                $logger->info("Log output : ".$output);
                return new Response($output);
            }
            else{
                return new Response("END");
            }
            
        }
       
}

?>
