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

    public function __construct($ssh_adr,$ssh_login,$ssh_password){

        $this->m_host=$ssh_adr;
        $this->m_login = $ssh_login;
        $this->m_mdp = $ssh_password;
        
        $this->m_connexion = ssh2_connect($this->m_host);
	ssh2_auth_password($this->m_connexion,$this->m_login,$this->m_mdp);
        
       // $this->setStream("docker run --rm=true --name  test -it gpp  /bin/bash -c \"wget http://etudiant@192.168.1.89/LIDE/test.c 2>/dev/null && gcc test.c -o exec && ./exec\"");
        $this->setStream("./a.out");
        $this->m_stdout= "";
        $this->testattribut = "BONJOUR";
        
        
    }
    
    function setAttribut($att){
        $this->testattribut = $att;
    }
    
    function getAttribut(){
        
        return $this->testattribut;
    }
    
    function setStream($cmd){
        $this->m_stream = ssh2_exec($this->m_connexion,$cmd);
        stream_set_blocking($this->m_stream, true);
    }
    
    function lire(){

        return fread($this->m_stream, 4096);
    }
   
    function ecrire($msg){
        flush();
        fwrite($this->m_stream,$msg.PHP_EOL);
        
    }


};
?>