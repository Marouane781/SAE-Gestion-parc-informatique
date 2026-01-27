# **CAHIER DES CHARGES**

## **Projet : Plateforme de Gestion de Parc Informatique**

## **1\. Introduction & Contexte**

Ce document définit le périmètre du projet de SAÉ pour l'année en cours (2025-2026). Le commanditaire souhaite une plateforme web pour centraliser la gestion de son parc de matériel informatique (unités centrales, écrans, périphériques). L'application sera développée par une équipe de 4 à 5 étudiants en BUT Informatique, utilisant les technologies Web standards (PHP/MySQL) et hébergée sur une architecture légère (Raspberry Pi 4).

## **2\. Énoncé des Besoins (Acteurs et Fonctionnalités)**

L'application doit gérer quatre niveaux d'accès distincts, chacun répondant à des besoins métier précis :

### **2.1 Visiteur (Accès Public)**

L'accès "Visiteur" permet une consultation passive sans authentification :

* **Présentation :** Accès à une page d'accueil contenant un texte explicatif et une vidéo de présentation de la plateforme.  
* **Transparence :** Consultation d'une vue partielle de l'inventaire.  
* **Tableau de bord :** Visualisation d’un module de statistiques sur l’état du parc.

### **2.2 Technicien (Accès Authentifié)**

Le technicien est l'opérateur principal du parc :

* **Gestion de l'inventaire :** Ajout, modification et consultation détaillée des équipements.  
* **Traitement de masse :** Ajout de machines via **import CSV** et sauvegarde de l'inventaire via **export CSV**.  
* **Cycle de vie :** Gestion du **rebut** (mise hors service logique et restauration de matériel).  
* **Intégrité :** Utilisation de listes prédéfinies (OS, Constructeurs) pour éviter les erreurs de saisie.

### **2.3 Administrateur Web (Accès Authentifié)**

L'administrateur web gère les accès humains et le paramétrage :

* **Gestion des Comptes :** Création et suppression des comptes Techniciens.  
* **Gestion des Référentiels :** Administration des listes de choix (Systèmes d'Exploitation, Constructeurs) utilisées par les techniciens.  
* **Supervision :** Droit de verrouillage sur la liste du rebut (pour figer l'état lors d'inventaires comptables ou de statistiques par exemple).

### **2.4 Administrateur Système (Accès Authentifié)**

L'administrateur système garantit la sécurité et l'audit :

* **Audit :** Accès exclusif aux **journaux d'activités (logs)** de la plateforme pour tracer les actions critiques (connexions, suppressions, modifications) et assurer la sécurité (avec la traçabilité). 

## **3\. Pré-requis Techniques**

L'architecture technique est imposée et doit respecter les contraintes suivantes :

* **Matériel :** Raspberry Pi 4\.  
* **Système :** OS Linux sur carte SD, serveur web Apache, SGBD MariaDB ou MySQL.  
* **Sécurité :**  
  * Mots de passe utilisateurs chiffrés en base de données.  
  * Protection des pages via sessions PHP.  
  * **Identifiants imposés** (Web) : `adminweb`, `sysadmin`, `tech1`.  
  * **Identifiants imposés** (SSH) : `sae2025` / `!sae2025!`.

## **4\. Priorités et Livrables**

Les priorités de développement, établies pour respecter les échéances, sont :

1. **Cœur fonctionnel :** Authentification sécurisée multi-rôles, CRUD Inventaire (Ajout/Modif/Liste), Page d'accueil publique.  
2. **Fonctionnalités avancées  :** Gestion du Rebut, Import/Export CSV, Gestion des référentiels (OS/Constructeurs).  
3. **Sécurité & Audit :** Système de logs complet (BDD) consultable par l'Admin Système.  
4. **Qualité & Documentation :** Code versionné sur Git, documentations des échéances (ajout de fonctionnalités…).

