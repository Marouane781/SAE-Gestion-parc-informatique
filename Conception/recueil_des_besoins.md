# **RECUEIL DES BESOINS DÉTAILLÉ**

## **CHAPITRE 1 : Cadrage du projet**

### **1.1 Objectif**

Le projet vise à fournir une application web intranet complète hébergée sur Raspberry Pi 4 pour la gestion centralisée et sécurisée d'un parc informatique. L'application doit couvrir le suivi du matériel, l'administration des accès et l'analyse statistique de l'inventaire.

### **1.2 Portée du système**

* **INCLUS (In Scope) :**  
  * Authentification sécurisée multi-rôles.  
  * Gestion complète de l'inventaire (CRUD) et du cycle de vie (Rebut).  
  * Gestion des référentiels de données (OS, Constructeurs) pour garantir la qualité et l’integrité.  
  * Fonctions d'import/export de masse (CSV).  
  * Module de statistiques publiques (Statistiques/Probabilités).  
  * Traçabilité complète (Logs).  
* **EXCLU (Out of Scope) :**  
  * Scan réseau automatique (SNMP) pour la découverte de matériel.  
  * Gestion de tickets d'incidents ou de prise en main à distance.  
  * Gestion financière complexe (amortissements comptables).

## **CHAPITRE 2 : Glossaire Technique**

* **Niveau Utilisateur (🌊) :** Cas d'utilisation métier apportant une valeur directe à l'acteur (l’acteur cherche à réaliser un but en soi).  
* **Niveau Sous-fonction (🐟) :** Fonctionnalité technique de soutien (ex: Login).  
* **Référentiel :** Liste de données administrée (ex: Liste des OS) servant de référence pour éviter les erreurs de saisie libre.  
* **Rebut :** Zone de stockage logique pour le matériel sorti du parc actif (ex : matériel obsolète).  
* **Soft Delete :** Suppression logique (changement d'état) plutôt que suppression physique en base de données (permet une récupération).

## **CHAPITRE 3 : Cas d'Utilisation (CU)**

### **3.1 Identification des Acteurs**

Avant de détailler les fonctionnalités, voici les acteurs interagissant avec le système :

* **Visiteur :** Utilisateur non authentifié accédant au portail public.  
* **Technicien :** Opérateur authentifié gérant le parc et le matériel.  
* **Administrateur Web :** Gestionnaire authentifié responsable des comptes techniciens et des référentiels.  
* **Administrateur Système :** Auditeur technique authentifié consultant les traces (logs).


**3.2 Liste des cas d’utilisation**

### **3.2.1 Authentification et Accès**

**Accéder à l’application**

* **Acteur principal :** Utilisateur  
* **Portée :** Application web Parc Info (boîte noire) \-\> Portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. L’utilisateur ouvre un navigateur web.  
  2. Il saisit l’URL du serveur (http://rpi14).  
  3. Le système affiche la page d’accueil index.php.

**Se connecter**

* **Acteur principal :** Utilisateur  
* **Portée :** Application web Parc Info (boîte noire) \-\> Portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. L’utilisateur accède à la page login.php.  
  2. Il saisit son login et son mot de passe.  
  3. Il valide le formulaire.  
  4. Le système vérifie les identifiants en base.  
  5. Si correct :  
     * Création de session  
     * Redirection selon rôle (adminweb, sysadmin, tech)  
  6. Sinon : affichage d’un message d’erreur.

**Se déconnecter**

* **Acteur principal :** Utilisateur  
* **Portée :** Application web Parc Info (boîte noire) \-\> Portée système  
* **Niveau :** Utilisateur  
* **Scénario nominal :**  
  1. L’utilisateur clique sur “Déconnexion”.  
  2. Le système supprime la session active.  
  3. Le système redirige vers login.php.  
  4. La page de connexion s’affiche.

     ### **3.2.2 Administration (Admin Web)**

**Créer un technicien**

* **Acteur principal :** Admin web  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. L’admin web accède à la page create\_tech.php depuis son espace.  
  2. Il saisit le login et le mot de passe du nouveau technicien.  
  3. Il valide le formulaire de création.  
  4. Le système vérifie que le login n’existe pas déjà.  
  5. Le système enregistre le nouveau compte avec le rôle tech en base de données.  
  6. Le système affiche un message de confirmation.

**Gérer les systèmes d’exploitation**

* **Acteur principal :** Admin web  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. L’admin web accède à la page manage\_os.php.  
  2. Le système affiche la liste des systèmes d’exploitation existants.  
  3. L’admin saisit le nom d’un nouveau système d’exploitation dans le formulaire.  
  4. Il valide le formulaire.  
  5. Le système enregistre le nouvel OS dans la table os.  
  6. Le système affiche la liste mise à jour des systèmes d’exploitation.

**Gérer les constructeurs**

* **Acteur principal :** Admin web  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. L’admin web accède à la page manage\_constructeur.php.  
  2. Le système affiche la liste des constructeurs existants.  
  3. L’admin saisit le nom d’un nouveau constructeur dans le formulaire.  
  4. Il valide le formulaire.  
  5. Le système enregistre le nouveau constructeur dans la table constructeur.  
  6. Le système affiche la liste mise à jour des constructeurs.

**Bloquer la liste du rebut**

* **Acteur principal :** Admin web  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. L’admin web accède à la page de gestion du rebut.  
  2. Il clique sur le bouton "Verrouiller le rebut".  
  3. Le système met à jour la configuration globale.  
  4. Le système désactive les boutons d'ajout et de restauration pour les techniciens.  
  5. Le système affiche un message de confirmation "Inventaire verrouillé".

     ### **3.2.3 Gestion du Parc (Technicien)**

**Consulter le parc informatique**

* **Acteur principal :** Technicien  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. Le technicien accède à la page list\_machines.php depuis son espace.  
  2. Le système récupère la liste des machines en base de données (machine, os, constructeur).  
  3. Le système affiche les machines par pages de 10 lignes avec leurs principales caractéristiques (nom, modèle, OS, constructeur, bâtiment, salle, état).  
  4. Le technicien peut changer de page via la pagination pour consulter l’ensemble du parc.

**Ajouter une machine**

* **Acteur :** Technicien  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. Le technicien accède à la page add\_machine.php.  
  2. Le système affiche un formulaire de saisie et charge les listes d’OS et de constructeurs disponibles.  
  3. Le technicien renseigne les informations de la machine (nom, modèle, CPU, RAM, OS, constructeur, bâtiment, salle, état).  
  4. Il valide le formulaire d’ajout.  
  5. Le système vérifie les champs obligatoires (nom, modèle).  
  6. Le système enregistre la nouvelle machine dans la table machine.  
  7. Le système affiche un message de confirmation.

**Modifier une machine**

* **Acteur :** Technicien  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. Le technicien accède à la page list\_machines.php.  
  2. Il sélectionne une machine à modifier en cliquant sur le lien "Modifier".  
  3. Le système charge les informations de la machine et les affiche dans le formulaire edit\_machine.php.  
  4. Le technicien modifie les champs souhaités (ex. OS, constructeur, salle, état).  
  5. Il valide le formulaire de modification.  
  6. Le système vérifie les champs obligatoires (nom, modèle).  
  7. Le système met à jour la machine dans la base de données.  
  8. Le système affiche un message de confirmation et retourne à la liste des machines.

**Importer des machines (CSV)**

* **Acteur principal :** Technicien  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. Le technicien accède à la page import\_csv.php.  
  2. Il sélectionne un fichier CSV depuis son poste.  
  3. Il valide l'importation.  
  4. Le système lit le fichier et vérifie les doublons.  
  5. Le système insère les nouvelles machines dans la table machine.  
  6. Le système affiche le nombre de machines importées avec succès.

**Exporter l’inventaire**

* **Acteur principal :** Technicien  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. Le technicien clique sur le bouton "Exporter CSV" depuis la liste.  
  2. Le système génère un fichier .csv contenant toutes les machines actives.  
  3. Le système déclenche le téléchargement du fichier sur le navigateur.

**Mettre au rebut**

* **Acteur principal :** Technicien  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. Le technicien sélectionne une machine dans la liste.  
  2. Il clique sur le bouton "Mettre au rebut".  
  3. Le système demande une confirmation.  
  4. Le système change le statut de la machine à "Hors Service".  
  5. La machine disparaît de la liste principale.

**Consulter et restaurer le rebut**

* **Acteur principal :** Technicien  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. Le technicien accède à la page rebut.php.  
  2. Le système affiche la liste des machines ayant le statut "Hors Service".  
  3. Le technicien clique sur "Restaurer" pour une machine.  
  4. Le système change le statut de la machine à "Actif".  
  5. La machine réapparaît dans l'inventaire principal.

     ### **3.4 Statistiques, Logs et Public**

**Consulter les statistiques**

* **Acteur principal :** Tout utilisateur (connecté ou non)  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. L’utilisateur accède à index.php.  
  2. Il clique sur le bouton "Statistiques".  
  3. Le système lit les fichiers CSV (ou la BDD).  
  4. Le système calcule les statistiques et les probabilités.  
  5. Le système génère les graphiques et affiche les résultats.

**Consulter les journaux d’activités**

* **Acteur principal :** Administrateur système (sysadmin)  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. Le sysadmin se connecte.  
  2. Il accède à sysadmin.php puis au bouton "Voir le journal d’activité".  
  3. Le système lit /var/log/apache2/access.log (et les logs applicatifs).  
  4. Le système affiche les entrées du journal.

**Consulter l'inventaire partiel**

* **Acteur principal :** Utilisateur non connecté  
* **Portée :** Application web Parc Info (boîte noire) \- portée système  
* **Niveau :** Utilisateur (au niveau de la mer)  
* **Scénario nominal :**  
  1. Le visiteur accède à la page public\_list.php.  
  2. Le système récupère la liste des machines (sauf champs sensibles : IP, Serial).  
  3. Le système affiche un tableau simplifié (Nom, Type, Salle).  
  4. Le visiteur visualise l'état global du parc sans pouvoir modifier.

## 

## 

## **CHAPITRE 4 : Environnement Technique**

* **Serveur :** Raspberry Pi 4\.  
* **OS :** Linux (Raspberry Pi OS).  
* **Web Server :** Apache 2\.  
* **Langage :** PHP (principalement)   
* **SGBD :** MariaDB / MySQL.  
* **Versionning :** Git (GitHub).

## **CHAPITRE 5 : Exigences Non-Fonctionnelles**

* **Sécurité :**  
  * Mots de passe stockés sous forme de hash.   
  * Protection contre les injections SQL.  
  * Protection XSS (Échappement des sorties `htmlspecialchars`).  
* **Performance :** Affichage de l'inventaire \< 2 secondes sur le réseau local.  
* **Ergonomie :** Interface intuitive et ergonomique, via CSS  
* **Traçabilité :** Intégrité des logs (ne peuvent être effacés via l'interface web).

## **CHAPITRE 6: Exigences Fonctionnelles**

### Authentification
- L’utilisateur doit pouvoir se connecter à l’application avec un identifiant et un mot de passe.
- Le système doit gérer plusieurs rôles : administrateur, administrateur système et technicien.

### Administrateur
- L’administrateur doit pouvoir créer un technicien dans la base de données.
- L’administrateur doit pouvoir ajouter et gérer les systèmes d’exploitation.
- L’administrateur doit pouvoir ajouter et gérer les constructeurs.
- L’administrateur doit pouvoir consulter la liste des machines.

### Technicien
- Le technicien doit pouvoir consulter le parc informatique.
- Le technicien doit pouvoir ajouter une machine via un formulaire.
- Le technicien doit pouvoir modifier les informations d’une machine.

### Administrateur système
- L’administrateur système doit pouvoir consulter les journaux d’activité (logs) du serveur.
- L’administrateur système doit pouvoir surveiller l’activité de la plateforme.

### Statistiques
- Les utilisateurs doivent pouvoir consulter des statistiques sur le parc informatique.
- Le système doit afficher des graphiques et indicateurs à partir des données CSV.
   
