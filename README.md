# SAÉ 3 – Application de gestion d’un parc informatique

Ce projet a été réalisé dans le cadre de la SAÉ 3 du BUT Informatique.  
Il consiste à concevoir et déployer une application web permettant la gestion d’un parc informatique pédagogique sur un serveur Raspberry Pi.

Le projet mobilise plusieurs domaines : analyse, conception, développement web, base de données, système et réseau, statistiques et documentation.

---

## Organisation du dépôt

Le dépôt est structuré selon les différentes étapes et domaines du projet.

### Analyse
Ce dossier contient les documents liés à l’analyse du besoin :
- recueil des besoins
- cahier des charges
- analyse fonctionnelle

### Conception
Ce dossier contient les modèles et diagrammes :
- MCD et MLD de la base de données
- diagrammes UML
- diagrammes de cas d’utilisation

### DOC
Ce dossier regroupe la documentation du projet, notamment les livrables des différentes échéances.

### SRC
Ce dossier contient le code source de l’application web.

Sous-dossiers principaux :
- admin : fonctionnalités de l’administrateur
- sysadmin : consultation des journaux système
- tech : fonctionnalités du technicien
- stats : statistiques et probabilités
- inc : fichiers de configuration et connexion à la base de données
- data : fichiers CSV utilisés pour les statistiques
- style : feuilles de style CSS

Fichiers principaux :
- index.php
- login.php
- logout.php

### Spec
Ce dossier contient les spécifications fonctionnelles et techniques.

### test
Ce dossier contient les tests réalisés sur l’application.

---

## Fonctionnalités principales

### Authentification
- Connexion par identifiant et mot de passe.
- Gestion de plusieurs rôles : administrateur, administrateur système et technicien.

### Administrateur
- Création de comptes technicien.
- Gestion des systèmes d’exploitation.
- Gestion des constructeurs.
- Consultation du parc informatique.

### Technicien
- Consultation du parc informatique.
- Ajout et modification de machines via des formulaires.

### Administrateur système
- Consultation des journaux d’activité du serveur (logs Apache).
- Surveillance du fonctionnement du serveur.

### Statistiques
- Analyse des données du parc informatique à partir de fichiers CSV.
- Affichage d’indicateurs et de graphiques.

---

## Technologies utilisées

- Raspberry Pi sous Debian
- Apache, PHP
- MariaDB
- HTML, CSS
- Git et GitHub

---

## Branche du projet

Le code principal du projet se trouve sur la branche `master`.

---

## Objectif pédagogique

Ce projet permet de mettre en pratique des compétences en :
- système et réseau,
- développement web,
- base de données,
- analyse statistique,
- travail en équipe et gestion de projet.
