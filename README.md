# LIDE

Le but de ce projet est de créer un environnement de développement en ligne dédié aux étudiants de licence. 
Cette application leur permettra d’éviter de télécharger tous les logiciels nécessaires durant les cours d’informatique (compilation,...). 
L'interface devra être sobre et la plus simple possible pour ne pas déstabiliser un étudiant débutant.

## Explication du projet

### Interface

Fonctionnalités implémentées :
 * Administration : Permet entre autre de gérer les langages
 * Authentification restreinte (Connexion + rôles + inscription par mail filtrée suivant le respet de préfixes paramétrable)
 * Gestion de plusieurs fichiers de code
 * Import de fichiers
 * Export de fichiers (sauvegarde) sur l'ordinateur
 * Sauvegarde en session : ctrl+s
 * Création avec le choix de l'extension voulue et la possibilité de choisir un modèle
 * Compilation/exécution de code avec docker paramétrable (compilation seule, choix des options, des entrées, ...)
 * Gestion de plusieurs langages (C++, c, ...)
 * Personalisation de l'interface (couleur, taille)
 * Responsive

### Exécution

Exécution conteneurisée avec docker sur un serveur. 
Exécution sécurisée et isolée avec la mémoire, l'accès au CPU et le temps d'exécution qui sont limités.

### Administration

Ajouter un nouveau langage à l'application :
 * Demander à l'informaticien de créer un dockerFile sur le serveur d'exécution en le nommant avec un nom spécifique (docker build --tag nom .)
 * Paramétrer l'application grâce à l'entité Langage
    * Nommer le langage
    * Renseigner les options de compilation par défauts
    * Préciser le compilateur
    * Intégrer le dockerFile créé
    * Renseigné le nom utilisé pour créer le docker sur le serveur
    * Ajouter le script pour compiler/exécuter en automatique
    * Activer le langage seulement si le docker correspondant à été créé sur le serveur
 * Ajouter une extension pour le langage dans DetailLangage
    * Renseigner l'extension (cpp, java, ...)
    * Ajouter un modèle par défaut
    * Choisir l'ordre de l'extension par rapport aux autres du même langage
    * Relier avec le langage créé précedement

## Installation

Attention, l'utilisateur du serveur web par exemple pour apache, www-data doit avoir les droits d'écriture et de lecture sur le projet et surtout le dossier web.  
   
Il faut d'avoir installé :
 * Un serveur web avec la base de données (apache, ...)
    * Exemple sous linux de LAMP (Linux Apache2 Mariadb PHP):
        * sudo apt install apache2 php mariadb-server libapache2-mod-php php-mysql
 * PHP 7 et composer
 * L'extention ssh pour php (libssh2, php-ssh2, ... suivant votre distribution)
 * nodesjs v8.* et npm 5.3 
    * Pour avoir la bonne version de nodejs sous linux :
        * curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
        * apt-get install-y nodejs
 * git (pour cloner le projet)
  
Ensuite, cloner le projet dans le dossier de votre serveur web (pour apache /var/www/html) avec git.  
Puis dans le dossier du projet, exécuter les étapes suivantes pour le bon fonctionnement du projet.

### Parameters

Dans le dossier du projet puis app/config, créer le fichier parameters.yml identique au fichier parameters.yml.dist en renseignant les bonnes valeurs. 
```
parameters:
    # Base de donénes
    database_host: localhost (ou 127.0.0.1)
    database_port: null
    database_name: lide <-- nom de la base de données
    database_user: user  <-- utilisateur de la base de données
    database_password: pass  <-- mot de passe de l'utilisateur de la base de données

    # Envoie de mail pour l'inscription
    mailer_transport: smtp
    mailer_host: smtp.gmail.com
    mailer_user: exemple@gmail.com  <-- Adresse mail gmail pour envoyer les mails d'inscription
    mailer_password: pass  <-- Mot de passe de l'adresse gmail
    mailer_auth_mode: login
    mailer_encryption: ssl
    mails_suffixe:  <-- Liste des suffixes autorisés pour l'inscription
        - etud.univ-angers.fr

    # Connexion SSH sur le serveur d'exécution
    ssh_host: 127.0.0.1 <-- ip du serveur d'exécution
    ssh_login: etudiant <-- login du serveur d'exécution
    ssh_password: pass <-- mot de passe de l'utilisateur du serveur d'exécution
    ssh_port: 22 <-- port pour se connecter en ssh au serveur d'exéution
    wget_adr: 'http://etudiant@192.168.1.6/LIDE/web' <-- la machine qui contient l'application pour que l'exécution docker récupère les fichiers de code de l'utilisateur

    # Paramètre pour docker
    docker_stop_timeout: 6 <-- Temps autorisé en seconde pour les exécutions docker sans intéractions
    docker_memory: 250M <-- Mémoire maximum autorisée pour les exécutions docker
    docker_cpu: 1 <-- null si non pris en compte par le noyau sinon la part du cpu voulu
    
    secret: ThisTokenIsNotSoSecretChangeIt
```


### Composer

Pour installer les dépendances PHP :
```
composer install
```
Si composer install renvoie une erreur, il faut probablement installer les extensions php nécessaire au bon déroulement de php en suivant les recommandation (exemple : php-curl, php-xml, php-mcrypt, php-zip, php-gd php-intl php-json php-mbstring)

### NPM

Pour installer les dépendances web :
```
npm install
```

### Gulp

Installé en global avec NPM :
```
sudo npm install gulp -g
```

Pour récupérer les fichiers css, js :
```
gulp all
```

### Base de données

Pour créer la base de données :
```
php app/console doctrine:database:create
```

Pour créer le schéma de la base de données :
```
php app/console doctrine:schema:create
```

Pour mettre à jour le schéma de la base de données si changement :
Avoir le retour de ce qui va être fait :
```
php app/console doctrine:schema:update --dump-sql
```
Forcer la mise à jour de la base de données :
```
php app/console doctrine:schema:update --force
```

## Deploiement

L'application sera créée comme suit dans le dossier du projet :
    * current : version courante du projet
    * releases : différentes version du projet
    * repo
    * shared : fichiers partagés entre les versions (paramétrages, ...)
Chaque déploiement entraine la création d'une release. Si le déploiement se passe bien, alors le dossier "current" pointera sur cette nouvelle release.
Pour exécuter des commmandes symfony par exemple sur la version courante, il faut aller dans le dossier current. Par exemple pour mettre à jour la base de données.
  
Pré-requis :  
Avoir l'application sur le poste qui va déployer sur le serveur (voir partie installation).

1) Paramètrages 

 - Dupliquer le fichier prod.rb en le renommant avec le nom pour le deploiement dans le dossier config/deploy
 - Modifier les lignes :
    * set :stage, :prod # remplacer :prod par le nouveau nom
    * set :symfony_env, "prod" # préciser l'environnement voulu : prod ou dev
    * set :deploy_to, '/var/www/html/LIDE' # Préciser le chemin sur le server dans l'exemple, le dossier du serveur web apache
    * server 'domaineServeur_ou_Ip', user: 'user', port: 22, roles: %w{app db web} # Remplacer avec les informations du serveur de déploiement

2) Installation Capistrano sur la machine voulant déployer pour le déploiement automatique

gem install bundler # avoir ruby gems sur le poste d'installé
bundle install

3) Paramètrages serveur

Attention, le serveur doit avoir (voir partie installation pour plus de détails) :
 * un serveur web (apache, nginx, ...)
 * PHP 7 et composer
 * nodesjs v8.6.0 et npm 5.3
 * l'extention ssh pour php (libssh2, php-ssh2, ... suivant votre distribution)

Créer la base de données en utf8_general_ci avec un utilisateur spécifique ayant des droits que sur cette base de données pour une meilleur sécurité.

4) Deploiement
```
cap prod deploy # prod : le nom du fichier voulu dans config/deploy ici, prod
```
5) Premier deploiement
```
cap prod deploy  
```
=> erreur pas de fichier parameters.yml  
Sur le serveur, aller dans le dossier du projet puis shared/app/config puis créer le fichier parameters.yml vide
```
cap prod deploy  
```
=> erreur base de données (Pas d'accès)  
Sur le serveur, aller dans le dossier du projet puis shared/app/config puis modifier le fichier parameters.yml avec les bons paramètres (voir partie Parameters)
```
cap prod deploy  
```
=> Succès !

Se connecter en ssh sur le serveur, aller dans le dossier du projet puis current/ pour avoir la version courante.
Exécuter les lignes de commande pour créer le schéma de la base de données et charger les fixtures.
 * php app/console doctrine:schema:create
 * php app/console doctrine:fixtures:load
  
Attention, il faut créer les images docker sur le serveur d'exécution et paramétrer en conséquence les langages.
