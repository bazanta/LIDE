# LIDE

Le but de ce projet est de créer un environnement de développement en ligne dédié aux étudiants de licence. 
Cette application leur permettra d’éviter de télécharger tous les logiciels nécessaires durant les cours d’informatique (compilation,...). 
L'interface devra être sobre et la plus simple possible pour ne pas déstabiliser un étudiant débutant.

## Getting Started

### Installation

Attention, l'utilisateur du serveur web par exemple pour apache, www-data doit avoir les droits d'écriture et de lecture sur le projet et surtout le dossier web.

### Composer

Pour installer les dépendances PHP :
```
composer install
```

### NPM

Il faut la version v8.6.0 de nodejs et 5.3 de npm.

Pour installer les dépendances web :
```
npm install
```

### Gulp

Installé en global avec NPM.
```
npm install gulp -g
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

Pour mettre à jour le schéma de la base de données :
Avoir le retour de ce qui va être fait :
```
php app/console doctrine:schema:update --dump-sql
```
Forcer la mise à jour:
```
php app/console doctrine:schema:update --force
```

## Deploiement

L'application sera créée comme suit dans le dossier du projet :
    * current : version courante du projet
    * releases : différentes version du projet
    * repo
    * shared : fichiers partagés entre les versions (paramétrages, ...)
Chaque déploiement entraine la création d'une release. Si le déploiement se passe bien, alors le dossier current pointera sur cette nouvelle release.
Pour exécuter des commmandes symfony par exemple sur la version courante, il faut aller dans le dossier current. Par exemple pour mettre à jour la base de données.

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

Attention, le serveur doit avoir :
 * un serveur web (apache, nginx, ...)
 * PHP 7 et composer
 * nodesjs v8.6.0 et npm 5.3
 * l'extention ssh pour php (libssh2, php-ssh2, ... suivant votre distribution)

Créer la base de données en utf8_general_ci avec un utilisateur spécifique ayant des droits que sur cette base de données pour une meilleur sécurité.

4) Deploiement

cap prod deploy # prod : le nom du fichier voulu dans config/deploy ici, prod

5) Premier deploiement

cap prod deploy
=> erreur pas de fichier parameters.yml
Sur le serveur, aller dans le dossier du projet puis shared/app puis créer le fichier parameters.yml vide

cap prod deploy
=> erreur base de données (Pas d'accès)
Sur le serveur, aller dans le dossier du projet puis shared/app puis modifier le fichier parameters.yml avec les bons paramètres

cap prod deploy
=> Succès !

Se connecter en ssh sur le serveur, aller dans le dossier du projet puis current/ pour avoir la version courante.
Exécuter les lignes de commande pour créer le schéma de la base de données et charger les fixtures.
 * php app/console doctrine:schema:create
 * php app/console doctrine:fixtures:load

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
    * Précider le compilateur
    * Intégrer le dockerFile créé
    * Renseigné le nom utilisé pour créer le docker sur le serveur
    * Ajouter le script pour compiler/exécuter en automatique
    * Activer le langage seulement si le docker correspondant à été créé sur le serveur
 * Ajouter une extension pour le langage dans DetailLangage
    * Renseigner l'extension (cpp, java, ...)
    * Ajouter un modèle par défaut
    * Choisir l'ordre de l'extension par rapport aux autres du même langage
    * Relier avec le langage créé précedement