# LIDE

Le but de ce projet est de créer un environnement de développement en ligne dédié aux étudiants de licence. Cette application leur permettra d’éviter de télécharger tous les logiciels nécessaires durant les cours d’informatique (compilation,...). Cette interface
devra être sobre et le plus simple possible pour ne pas déstabiliser un étudiant débutant.

## Getting Started

### Composer

Pour installer les dépendances PHP (ex: bundle) :
```
composer install
```

### NPM

Pour installer les dépendances web :
```
npm install
```

### Gulp

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

## Explication du projet

### Interface


### Serveurs


### Communication entre l'interface et les serveurs