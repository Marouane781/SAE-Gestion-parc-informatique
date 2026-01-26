# SAÉ 3 – Gestion d’un parc informatique

Ce projet a été réalisé dans le cadre de la SAÉ 3 du BUT Informatique.  
L’objectif est de concevoir et déployer une application permettant la gestion d’un parc informatique pédagogique à l’aide d’un serveur Raspberry Pi.

L’application permet de gérer différents profils d’utilisateurs et de manipuler les données du parc informatique (machines, systèmes d’exploitation, constructeurs), tout en proposant une partie statistique et un accès aux journaux système.

---

## Organisation du dépôt

Le dépôt GitHub est structuré de la manière suivante :

- `Analyse/` : analyse des besoins et recueil des exigences (fonctionnelles et non fonctionnelles)
- `Conception/` : diagrammes et modèles (UML, MCD, MLD, cas d’utilisation)
- `DOC/` : documentation générale du projet
- `SRC/` : code source de l’application web (PHP, HTML, CSS)
- `Spec/` : spécifications fonctionnelles et techniques
- `test/` : tests réalisés sur l’application

---

## Fonctionnalités principales

### Authentification
- Connexion par identifiant et mot de passe
- Gestion de plusieurs rôles : administrateur, administrateur système et technicien

### Administrateur
- Création de comptes technicien
- Gestion des systèmes d’exploitation
- Gestion des constructeurs
- Consultation du parc informatique

### Technicien
- Consultation du parc informatique
- Ajout et modification de machines via des formulaires

### Administrateur système
- Consultation des journaux d’activité (logs Apache)
- Surveillance du serveur

### Statistiques
- Analyse des données du parc informatique à partir de fichiers CSV
- Affichage d’indicateurs et de graphiques

---

## Technologies utilisées

- Raspberry Pi / Debian
- Apache, PHP
- MariaDB
- HTML, CSS
- Git / GitHub

---

## Branche

Le code principal du projet se trouve sur la branche `master`.

---

## Objectif pédagogique

Ce projet permet de mettre en pratique des compétences en :
- système et réseau,
- développement web,
- base de données,
- analyse statistique,
- travail en équipe.
