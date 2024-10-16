# symfony-formation


## Description
Ceci est une application bac à sable de symfony, de ce fait, j'en profite pour tester pas mal de chose au fur et à mesure. Le but étant d'apprendre à développer en symfony avec twig.

## Objectif
Ce projet a pour but d'explorer et d'apprendre le framework Symfony. Voici les concepts principaux abordés :
- Gestion du CRUD avec Doctrine
- Utilisation de Twig pour le templating
- Gestion des relations entre entités
- Utilisation de Mailpit pour tester l'envoi d'emails en local

## Prérequis
- PHP >= 8.3
- Composer
- Symfony CLI
- Mysql ou autre système de gestion de base de donnée, mais il faudra modifier la configuration

## Installation
1. Clone le dépôt git : `https://github.com/AinzNazarick/symfony-formation`
2. Installez les dépendances : `composer install`
3. Configurez la base de données dans .env
4. Créer la base de données : `php bin/console doctrine:database:create`
5. Appliquer les migrations : `php bin/console doctrine:migration:migrate` ou `php bin/console d:m:m`
6. Démarrage du serveur : `symfony server:start`
### Si besoin d'utiliser la fonctionnalité d'envoyer de mail 'Contact'
1. Ouvrir un terminal
2. Exécuter mailpit.exe depuis le terminal `'Localisation de votre projet'\bacasable\bin\mailpit.exe`

## Architecture du projet
- Structure des répertoires : 
  - `/src` : Contient le code source de l'application 
  - `/templates` : Contient les vues (fichiers Twig)

- Conventions :
  - Tous les noms de routes des controllers seront dans la forme de `nomDuControlleur.méthode`

## Fonctionnalités principales
Recipe : 
- CRUD sur des recettes de cuisine
- Création de vue pour pouvoir manier le CRUD depuis une interface utilisateur
- Le champ 'slug' dans la création d'une recette peut être rempli automatiquement depuis le nom de la recette 

Contact : 
- Création d'une vue qui permet d'envoyer un mail
- Utilisation de Mailpit pour simuler une boite mail local

Product et Category : 
- CRUD réaliser sur 2 entités avec une relation ManyToOne
- Création de vue pour pouvoir manier le CRUD depuis une interface utilisateur.

Utilisation d'une navbar afin de circuler entre les fonctionnalités qui ne sont pas liées les unes aux autres.

## Fonctionnalités expérimentales
Certaines fonctionnalités sont encore en phase de test ou d'apprentissage :
- Envoi d'emails avec Mailpit est fonctionnel, mais n'est pas encore configuré pour un environnement de production.
- Le système de gestion des recettes peut être amélioré en ajoutant des validations plus strictes.


## Problèmes rencontrés et solutions

## Améliorations futures


