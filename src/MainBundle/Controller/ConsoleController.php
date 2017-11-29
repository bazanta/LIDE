<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use MainBundle\Entity\Execution;
use MainBundle\Form\ExecutionType;

class ConsoleController extends Controller
{


    public function indexAction()
    {

        $content = $this->get('templating')->render('MainBundle:Console:console.html.twig', array(""));

        $ssh = $this->get('gestionssh');

        return new Response($content);

    }


    //Méthode appelée par la vue console
    //Première méthode appelée après l'appui sur le bouton RUN

    public function execAction(Request $request)
    {
        $logger = $this->get('logger');
        $ssh = $this->get('gestionssh');

        $id_user = "test";
        $ip_proxy = "172.29.18.192";

        $exec = new Execution();
        $form = $this->createform(ExecutionType::class, $exec);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = "testUser";

            $logger->info(print_r($request->get('execution'), true));

            $logger->info("Fichier additionnels : " . print_r($exec->getAdditionalFiles(), true));

//Écriture des fichiers sur le disques
            $tmpdir = exec("mktemp -d $user.XXXXXX");
            $logger->info("Dossier temporaire : $tmpdir");

//Récupération script compilation & éxecution dans la DB, ecriture sur le disk.
            $idLangage = $exec->getLanguage();
            $logger->info("Id langage : " . $idLangage);
            $em = $this->getDoctrine()->getManager();
            $script = $em->getRepository('MainBundle:Langage')->find($idLangage)->getScript();

            $logger->info($script);

            $file_on_disk = fopen("$tmpdir/exec.sh", "w");
            fwrite($file_on_disk, $script);

            fclose($file_on_disk);

//Copie fichier source user

            $fichiers = json_decode($exec->getFiles());

            $logger->info(print_r($fichiers, true));


            $listeFichiers = "";

            foreach ($fichiers as $f) {
                //Écriture des fichiers dans un dossier temporaire
                if ($file_on_disk = fopen($tmpdir . "/" . $f->name, "w")) {
                    //Écriture contenu fichier
                    $logger->info("Écriture fichier : $f->name");

                    fwrite($file_on_disk, $f->content);
                    $listeFichiers .= $f->name . " ";

                    fclose($file_on_disk);
                } else {
                    //Echec fopen
                    exec("rm -rf $tmpdir");
                    return new Response("Echec écriture fichier $f->name sur serveur");
                }
            }

//Copie fichier additionnels
            foreach ($exec->getAdditionalFiles() as $file){
                $fileName = $file->getClientOriginalName();
                $file->move(
                    $tmpdir,
                    $fileName
                );
                $listeFichiers.= $fileName . " ";
                $logger->info($fileName);
            }


            $parametreCompilation = str_replace("\'", "\'\\\'\'", $exec->getCompilationOptions()); //Remplace tous les <'> par <\'>
            $parametreLancement = str_replace("\'", "\'\\\'\'", $exec->getLaunchParameters()); //Idem

            $wgetAdr = "http://etudiant@$ip_proxy/LIDE/web/$tmpdir/";

            $cmd="";
    //        $cmd = "docker stop --time=0 test; ";
            $cmd .= "docker run --rm=true --name  $id_user -it gpp  /bin/bash -c \"wget $wgetAdr" . "exec.sh 2>/dev/null  && chmod a+x exec.sh && sed -i -e 's/\\r$//' exec.sh && ";

//Parametre de compilation
            $cmd .= " ./exec.sh -o '$parametreCompilation' -w $wgetAdr";
//Arguments
            $cmd .= " -a '$parametreLancement'";
//Mode de gestion des entrées
            if ($exec->getInputMode() == 'none') {
                $cmd .= " -n";
            } else if ($exec->getInputMode() == 'text') { //Création d'un fichier d'entrée. Lancement du programme du type ./a.out args ... < input_file
                $inputFilename = exec("cd $tmpdir && mktemp XXX");

                $inputFile = fopen("$tmpdir/$inputFilename", "w");
                fwrite($inputFile, $exec->getInputs());
                fclose($inputFile);

                $listeFichiers .= " $inputFilename";

                $cmd .= " -i $inputFilename";
            } else {
                //Input interractive
            }

//Liste des fichiers
            $cmd .= " -f '$listeFichiers'";

//Mode compilation uniquement
            if ($exec->isCompileOnly() == 1) {
                $cmd .= " -c";
            }

            $cmd .= "\"";

            $logger->info(exec("ls $tmpdir"));
            $logger->info("CMD DOCKER : " . $cmd);
//Execution de la commande de lancement du docker, qui compile et eventuellement execute
            $ssh->execCmd($cmd);
            $output = $ssh->lire();

            $response = array(
                'reponse' => $output[0],
                'fin' => $output[1]
            );

            exec("rm -rf $tmpdir");

            $logger->info("Réponse : ". $output[0]);
            $logger->info(json_encode($response));
            return new Response(json_encode($response));
        }

        return new Response(json_encode(array(
            'reponse' => "Erreur formulaire",
            'fin' => 'oui'
        )));
    }
    //Permet de répondre aux programme (pas nécessairement appelée);

    public function answerAction(Request $request)
    {


        $ssh = $this->get('gestionssh');

        $msg = $request->request->get('msg');

        $ssh->execCmd("docker start -ai test");
        $ssh->ecrire($msg);
        $output = $ssh->lire();

        $response = array(
            'reponse' => $output[0],
            'fin' => $output[1]
        );

        $logger = $this->get('logger');
        $logger->info(json_encode($response));

        return new Response(json_encode($response));
    }
}

?>
