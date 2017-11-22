<?php

namespace MainBundle\Services;

class GestionSSH{

    private $shell;
    private $cmd;   //dernière commande lue
    private $msg;   //dernier message envoyé
    
    private static $TIME_OUT = 30;

    public function __construct($ssh_adr,$ssh_login,$ssh_password){

        $this->m_host=$ssh_adr;
        $this->m_login = $ssh_login;
        $this->m_mdp = $ssh_password;
        
        $connection = ssh2_connect($ssh_adr);
	ssh2_auth_password($connection,$ssh_login,$ssh_password);
        
        $this->shell = ssh2_shell($connection,"bash",null,10000,10000, SSH2_TERM_UNIT_CHARS);       
        
    }
    
    
    //Lire dans le shell
    
    function lire(){
        
        $out = "";
        $start = false;
        $start_time = time();
        $max_time = 0.5; //time in seconds
        
        while((time()-$start_time)< GestionSSH::$TIME_OUT){
            
            $new_start_time = time();
            while(((time()-$new_start_time) < $max_time)) {
                $line = fgets($this->shell);


                if(!(strstr($line,$this->cmd))) { //On n'affiche pas la commande 

                    if(preg_match('/\[start\]/',$line)&&!$start) {
                        $start = true;
                    }elseif(preg_match('/\[end\]/',$line)) {
                        $output [] = $out;
                        $output [] = "yes";
                        return $output;
                    }elseif($start&&!($line===$this->msg."\r\n")){   //On n'affiche pas le message envoyé
                        $out .= $line;
                    }
                }
                   
            }
            
            if($out!=null){
                    $output [] = $out;
                    $output [] = "no";

                    return $output;
            }
        }
    }
    
    //Exécute une commande
    
    function execCmd($cmd){
        $cmdSE = "echo '[start]';".$cmd."; echo '[end]'";
        fwrite($this->shell,$cmdSE . "\n");
        
        $this->cmd = $cmdSE;
    }
   
    //Envoie des messages qui ne sont pas des commandes;
    function ecrire($msg){
        
        flush();
        
        fwrite($this->shell,$msg."\n");
        
        $this->msg = $msg;
        
    }
    
};
?>