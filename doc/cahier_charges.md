# Cahier des Charges

## I. Introduction

### 1.1 Informations Générales
Ce document présente le cahier des charges du projet de développement d'une application web de gestion de demandes de dépannage. Il décrit les fonctionnalités attendues, les différents types d'utilisateurs, ainsi que les contraintes techniques et matérielles.

### 1.2 Objectifs du Document
Ce document a pour objectif de définir clairement les besoins et les attentes du client en matière de développement de l'application. Il servira de référence pour l'équipe de développement tout au long du processus.

### 1.3 Structure du Document
Le document est divisé en quatre sections principales :
- Introduction
- Enoncé
- Pré-requis
- Priorités

### 1.4 Documents Référencés
Nous avons différents documents référencés tel que :
- La charte informatique 
- La gestion des risques du projet
- La documentation de l'installation du serveur
- Le dossier de conception
- L'explication du choix de notre logo ainsi que la charte graphique de notre site

## II. Enoncé

### 2.1 Description du Problème
Le projet vise à mettre en place une application web en PHP et MySQL permettant de collecter les demandes de dépannage émanant des utilisateurs dans les salles informatiques. L'application devra gérer quatre types d'utilisateurs : l'administrateur système, l'administrateur web, des techniciens, l'utilisateur inscrit et le visiteur.

### 2.2 Contexte
L'application sera hébergée sur un serveur web installé sur un Raspberry Pi 4, accessible en connexion SSH depuis les salles machines.

### 2.3 Objectifs du Projet
L'objectif principal du projet est de développer une plateforme fonctionnelle et sécurisée permettant la gestion des demandes de dépannage. Les fonctionnalités principales incluent la création de comptes, la soumission de demandes, la visualisation de l'état des demandes et la gestion des tickets par les différents types d'utilisateurs.

## III. Pré-requis

### 3.1 Connaissances Requises
Les compétences nécessaires pour mener à bien ce projet incluent une maîtrise de PHP, MySQL, ainsi que des connaissances en développement web et en sécurité informatique.

### 3.2 Ressources Matérielles et Logicielles
- Serveur web hébergé sur un Raspberry Pi 4
- Connexion SSH depuis les salles machines.
- Connexion au serveur web grâce à des requêtes HTTP

### 3.3 Compétences Nécessaires
- Développement web (PHP, MySQL)
- Gestion des bases de données
- Sécurité informatique

## IV. Priorités

Les priorités de développement ont été définies en accord avec le client et sont les suivantes :

1. Mise en place de l'interface utilisateur (page d'accueil, tableau de bord, formulaires)
2. Gestion des utilisateurs (inscription, authentification, profils)
3. Gestion des demandes de dépannage (création, statut, affectation aux techniciens)
4. Journal d'activité (enregistrement des validations, tentatives de connexion ratées)
5. Niveaux d'urgence et libellés associés
6. Statistiques pour l'administrateur système
7. Historique des tickets fermés

**Note :** Ces priorités peuvent être ajustées en cours de développement en fonction des besoins et des retours du client.