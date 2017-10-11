<?php

class ConnexionSSH{
    
    private $mdp;
    
    public function __construct($ssh_password){
        
        $this->mdp = $ssh_password;
        
    }
    

    function connexionSSH(){


	$name = 'etudiant';

	$file = fopen("config.txt",'r');
	$mdp = fgets($file);
	fclose($file);


	$connexion = ssh2_connect('localhost',22);

	if(!$connexion){
		echo "Connexion refusée";
	}
	else{
		echo "Connexion ok";
	}

	$result = ssh2_auth_password($connexion,$name, $this->mdp);

	if(!$result){

		echo "Authentification refusée";

	}
	else{

		echo "Authentification ok";

	}

	ssh2_exec($connexion,"touch ~/test");
	ssh2_exec($connexion,"logout");

    }
    
    
 
};

?>