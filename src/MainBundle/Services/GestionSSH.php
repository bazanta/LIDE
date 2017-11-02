<?php

namespace MainBundle\Services;

class GestionSSH{

    private $m_host;
    private $m_login;
    private $m_mdp;
    private $m_connexion;
    private $m_stream;
    private $m_stdout;
    private $testattribut;
    private $shell;

    public function __construct($ssh_adr,$ssh_login,$ssh_password){

        $this->m_host=$ssh_adr;
        $this->m_login = $ssh_login;
        $this->m_mdp = $ssh_password;
        
        $this->m_connexion = ssh2_connect($this->m_host);
	ssh2_auth_password($this->m_connexion,$this->m_login,$this->m_mdp);
        
        $this->shell = ssh2_shell($this->m_connexion,"bash",null,8000,8000, SSH2_TERM_UNIT_CHARS);       
        
    }
    
    
    //Lire dans le shell
    
    function lire($cmd, $msg=null){
        
        $out = "";
        $start = false;
        $start_time = time();
        $max_time = 2; //time in seconds
        while(((time()-$start_time) < $max_time)) {
            $line = fgets($this->shell);
            
            
            if(!strstr($line,$cmd)) { //On affiche pas la commande ni le message envoyé
                if((is_null($msg))||(!is_null($msg)&&(strcmp($line, $msg) !== 0))){
                    if(preg_match('/\[start\]/',$line)) {
                        $start = true;
                    }elseif(preg_match('/\[end\]/',$line)) {
                        return $out;
                    }elseif($start){
                        $out .= $line;
                    }
                }
                
            }
        }
        
        
        return $out;
    }
    
    //Exécute une commande
    
    function lancerCommande($cmd){
        $cmdSE = "echo '[start]';$cmd;echo '[end]'";
        fwrite($this->shell,$cmdSE . "\n");
    }
   
    //Envoie des messages qui ne sont pas des commandes;
    function ecrire($msg){
        
        flush();
        
        fwrite($this->shell,$msg."\n");
        
    }
    
    


};
?>