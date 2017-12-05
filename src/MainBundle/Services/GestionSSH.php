<?php

namespace MainBundle\Services;

class GestionSSH{

    private $connection;
    private $shell;
    private $cmd;   //dernière commande lue
    private $msg;   //dernier message envoyé
    
    private static $TIME_OUT = 30;

    public function __construct($ssh_adr,$ssh_login,$ssh_password){

        $this->connection = ssh2_connect($ssh_adr);
	ssh2_auth_password($this->connection,$ssh_login,$ssh_password);

        
        $this->shell = ssh2_shell($this->connection,"bash",null,10000,10000, SSH2_TERM_UNIT_CHARS);
        
    }
    
    
    /**
     * Lit dans le shell
     * @param type $id_user
     * @return string
     */
    
    function lire($id_user){
        
        $out = "";
        $start = false;
        $msg_rencontre =false;
        $start_time = time();
        $max_time = 2; //time in seconds
        
        
        
        while((time()-$start_time)< GestionSSH::$TIME_OUT){
            
            $new_start_time = time();
            while(((time()-$new_start_time) < $max_time)) {

                $line = fgets($this->shell);

                if(!(strstr($line,$this->cmd))) { //On n'affiche pas la commande 
                    
                    if(!$start&&preg_match("/beginOutput/", $line)) { //Permet de ne pas afficher les lignes d'initialisation du shell
                        $start = true;                       
                    }elseif($start){
                        $out .= $line;
                    }
                }
            }
        
            //Si la chaine est differente de null
            if($out!=null){
                
                //On teste si le docker est terminé
                $testfin = $this->testFin($id_user);
                
                //Le docker est terminé si testfin est égal à "NOT OK"
                if(strcmp($testfin, "FINI\n") === 0){
                    
                    //On récupère tout sauf la dernière ligne (invit de commande du shell);
                    $outTab = explode("\n",$out,-1);
                    
                    $out = "";
                    
                    //i commence à 1 pour ne pas afficher le message encoyé
                    for($i=1;$i<count($outTab);$i++){
                        $out.=$outTab[$i]."\n";
                    }
                                       
                    
                    $output [] = $out;
                    $output [] = "yes";
                    return $output;
                }
                else{
                    
                    $output [] = $out;
                    $output [] = "no";
                    return $output;
                }
            }
            
        }
    }
    
    
    /**
     * Teste si le docker est terminé ou non
     * @param type $id_user
     * @return type
     */
    function testFin($id_user){
        
        //On teste si la sortie de docker ps contient l'identifiant de l'utilisateur
        $stream = ssh2_exec($this->connection, "[[ $(docker ps -a) =~ \"id_$id_user\" ]] && echo \"OK\" || echo \"FINI\"");

        stream_set_blocking($stream, true);

        $output = stream_get_contents($stream);
     
        fclose($stream);
       
        return $output;
    }
    
    /**
     * Exécute une commande
     * @param type $cmd
     */
    function execCmd($cmd){
        $cmdSE = "echo 'beginOutput';".$cmd;
        fwrite($this->shell,$cmdSE . "\n");
        
        $this->cmd = $cmdSE;
    }
   
    /**
     * Ecrit un message dans le shell
     * @param type $msg
     */
    function ecrire($msg){
        
        flush();
        
        fwrite($this->shell,$msg."\n");
        
        $this->msg = $msg;
        
    }
    
};
?>