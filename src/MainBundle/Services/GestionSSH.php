<?php

namespace MainBundle\Services;

class GestionSSH{

    private $m_host;
    private $m_login;
    private $m_mdp;
    private $m_connexion;

    public function __construct($ssh_adr,$ssh_login,$ssh_password){

        $this->m_host=$ssh_adr;
        $this->m_login = $ssh_login;
        $this->m_mdp = $ssh_password;

	$this->m_connexion = ssh2_connect($this->m_host);
	ssh2_auth_password($this->m_connexion,$this->m_login,$this->m_mdp);

    }

    function traiter($cmd){

	$stream = ssh2_exec($this->m_connexion,$cmd);


	stream_set_blocking($stream, true);
	$stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);

	$result=stream_get_contents($stream_out);

	//Si la réponse de la commande est vide alors on teste si la cmd n'a pas lancé un message d'erreur
	if($result === ""){
		$stream_err = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
		$result=stream_get_contents($stream_err);
	}

	return $result;

    }

};

?>
