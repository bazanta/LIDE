<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use MainBundle\Entity\Execution;
use MainBundle\Form\ExecutionType;

class ConsoleController extends Controller
{
    private function writeFilesInDir($files, $dir)
    {
        $listeFichiers = "";
        $logger = $this->get('logger');
        foreach ($files as $f) {
            //Écriture des fichiers dans un dossier temporaire
            if ($file_on_disk = fopen($dir . "/" . $f->name, "w")) {
                //Écriture contenu fichier
                $logger->info("Écriture fichier : $f->name");

                fwrite($file_on_disk, $f->content);
                $listeFichiers .= $f->name . " ";

                fclose($file_on_disk);
            } else {
                //Echec fopen
                exec("rm -rf $dir");
                return new Response("Echec écriture fichier $f->name sur serveur");
            }
        }
        return $listeFichiers;
    }

    //Méthode appelée par la vue console
    //Première méthode appelée après l'appui sur le bouton RUN

    public function execAction(Request $request)
    {
        $logger = $this->get('logger');
        $ssh = $this->get('gestionssh');

        $id_user = $this->getUser()->getId();

        if ($id_user == null) {
            return new Response(json_encode(array(
                'reponse' => "Vous êtes déconnecté",
                'fin' => 'oui'
            )));
        }

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


            $listeFichiers = $this->writeFilesInDir($fichiers, $tmpdir);

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

            $parametreCompilation = str_replace("\'", "\'\\\'\'", $exec->getCompilationOptions()); //Remplace tous les 'par \'
            $parametreLancement = str_replace("\'", "\'\\\'\'", $exec->getLaunchParameters()); //Idem

            $wgetAdr = $this->container->getParameter('wget_adr')."/$tmpdir/";

            $logger->info("Wget adress: $wgetAdr");

            $timeout = $this->container->getParameter('docker_stop_timeout');
            $cpu = $this->container->getParameter('docker_cpu');
            $memory = $this->container->getParameter('docker_memory');

            $cmd = "docker stop --time=0 id_$id_user"."A > /dev/null 2>&1; ";
            $cmd .= "timeout --signal=SIGKILL ".$timeout."s docker run --rm=true --name  id_$id_user"."A -it --cpus $cpu -m $memory";
            $cmd .= " gpp /bin/bash -c \"wget $wgetAdr" . "exec.sh 2>/dev/null  && chmod a+x exec.sh && sed -i -e 's/\\r$//' exec.sh && ";
            
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
                //Input interractive : rien à faire
            }

            //Liste des fichiers
            $cmd .= " -f '$listeFichiers'";

            //Mode compilation uniquement
            if ($exec->isCompileOnly() == 1) {
                $cmd .= " -c";
            }

            $cmd .= "\" 2>/dev/null";

            $logger->info("Starting docker with command : " . $cmd);
            //Execution de la commande de lancement du docker, qui compile et eventuellement execute
            $ssh->execCmd($cmd);

            // Pause de 2 secondes pour laisser le temps à la commande de s'exécuter
            sleep(1);

            $output = $ssh->lire($id_user);

            $logger->info($output[0]);
            $response = array(
                'cmd' => $cmd,
                'reponse' => $output[0],
                'fin' => $output[1]
            );

            exec("rm -rf $tmpdir");

            $logger->info("Reponse : " . json_encode($response));
            return new Response(json_encode($response));
        }

        return new Response(json_encode(array(
            'reponse' => "Erreur formulaire",
            'fin' => 'oui'
        )));
    }

    /**
     * Permet de répondre aux programme (pas nécessairement appelée);
     * @param Request $request
     * @return Response
     */
    public function answerAction(Request $request)
    {
        $logger = $this->get('logger');

        $ssh = $this->get('gestionssh');

        $msg = $request->request->get('msg');
        
        $logger->info("Reponse : $msg");

        $id_user = $this->getUser()->getId();

        if(!$ssh->dockerTermine($id_user)){
            
            $ssh->execCmd("docker start -ai id_$id_user"."A");
            $ssh->ecrire($msg);
            $output = $ssh->lire($this->getUser()->getId());

            $response = array(
                'reponse' => $output[0],
                'fin' => $output[1]
            );
        } else {
            $response = array(
                'reponse' => "Docker terminé",
                'fin' => "yes"
            );
        }
       
        $logger->info(json_encode($response));

        return new Response(json_encode($response));
    }

    /**
     * Stop le docker de l'utilisateurs
     * @param Request $request
     * @return Response
     */
    public function stopAction(Request $request){
        $ssh = $this->get('gestionssh');

        $id_user = $this->getUser()->getId();
        $this->get("logger")->info("STOP ".$id_user);
        if(!$ssh->dockerTermine($id_user)){
            $ssh->execAndRead("docker stop --time=0 id_$id_user"."A > /dev/null 2>&1; ");
            $output = $ssh->lire($this->getUser()->getId());

            $response = array(
                'out' => $output,
                'stopped' => 'ok'
            );
        } else {
            $response = array(
                'stopped' => 'already-dead'
            );
        }
        return new Response(json_encode($response));
    }
}

?>
