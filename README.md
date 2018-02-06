# LIDE

Le but de ce projet est de créer un environnement de développement en ligne dédié aux étudiants de licence. 
Cette application leur permettra d’éviter de télécharger tous les logiciels nécessaires durant les cours d’informatique (compilation,...). 
Cette interface devra être sobre et le plus simple possible pour ne pas déstabiliser un étudiant débutant.

## Explication du projet

### Interface

### Serveurs

### Communication entre l'interface et les serveurs


## Getting Started

### Installation

Attention, le dossier web doit être propriété de l'utilisateur du serveur web par exemple pour apache, www-data.
drwxr-xr-x   8 www-data www-data   4096 janv. 26 14:37 web/

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

1) Paramètrages 

 - Dupliquer le fichier prod.rb avec le nom pour le deploiement dans le dossier config/deploy
 - Modifier les lignes :
    * set :stage, :prod # remplacer :prod par le nouveau nom
    * set :symfony_env, "prod" # préciser l'environnement voulu : prod ou dev
    * set :deploy_to, '/var/www/html/LIDE' # Préciser le chemin sur le server
    * server 'domaineServeur_ou_Ip', user: 'user', port: 22, roles: %w{app db web} # Remplacer avec les informations du serveur de déploiement

2) Installation Capistrano

gem install bundler
bundle install

3) Paramètrages serveur

Créer la base de données en utf8_general_ci avec un utilisateur spécifique ayant des droits que sur cette base de données.

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
    - php app/console doctrine:schema:create
    - php app/console doctrine:fixtures:load

## Explication du projet

### Interface


### Serveurs


### Communication entre l'interface et les serveurs