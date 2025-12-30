# **FICHE D'ANALYSE (OAA & Modélisation)**

## **1\. Dictionnaire des Données (Entités Métiers)**

Cette section recense les objets manipulés par l'application, déduits de l'analyse du sujet et des besoins de normalisation.

| Entité (Objet) | Description & Attributs Clés | Justification / Source |
| :---- | :---- | :---- |
| **Machine** | Élément central du parc (UC ou Écran). *Attributs :* ID, Nom, Serial, Date Achat, **Ref\_OS**, **Ref\_Constructeur**, Statut (Actif/Rebut). | Sujet (p.2) : Distinction UC/Écran. Nécessité de lier à des référentiels pour éviter la saisie libre. |
| **Utilisateur** | Compte de connexion, pourra accéder à certaines fonctionnalités selon le rôle. *Attributs :* Login, Mot de passe (hashé), Rôle (AdminWeb, SysAdmin, Tech). | Sujet (p.2) : 4 types d'utilisateurs définis. |
| **OS** | Système d'exploitation. *Attributs :* ID, Nom. | Sujet (p.2) : L'Admin Web crée des "informations réutilisables" comme le nom des OS. |
| **Constructeur** | Fabricant du matériel. *Attributs :* ID, Nom. | Sujet (p.2) : Idem, information réutilisable gérée par l'Admin Web. |
| **Rebut** | État ou table d'historique du matériel sorti. *Attributs :* Date de mise au rebut, Motif (optionnel). | Sujet (p.2) : Liste spécifique pouvant être "bloquée". |
| **Log (Journal)** | Trace d'audit inaltérable. *Attributs :* Timestamp, Acteur, Action, Cible. | Sujet (p.1) : "Un fichier de log inhérent à toutes les actions réalisées est créé". |
| **Fichier CSV** | Format d'échange de données. | Sujet (p.2) : Utilisé pour l'import et l'export. |

## 

## **2\. Matrice Objets \- Acteurs \- Actions (OAA)**

Ce tableau croise les acteurs (Qui ?) avec les objets (Quoi ?) pour définir les actions autorisées (Comment ?).

| Objet Métier | Visiteur | Technicien | Admin Web | Admin Sys |
| :---- | :---- | :---- | :---- | :---- |
| **Inventaire** | Consulter (Partiel) | Consulter (Complet), Exporter CSV | \- | \- |
| **Machine** | \- | Ajouter, Modifier, Importer CSV | \- | \- |
| **Rebut** | \- | Mettre au rebut, Consulter, Restaurer | Consulter, **Bloquer** | \- |
| **Utilisateur** | \- | \- | Créer, Supprimer | \- |
| **Référentiel**  *(OS, Constructeur)* | \- | Utiliser (Liste déroulante) | Créer (Ajouter au référentiel) | \- |
| **Logs** | \- | \- | \- | **Consulter** |
| **Statistiques** | **Consulter** | \- | \- | \- |

## 

## 

## 

## 

## 

## **3\. Analyse des Règles de Gestion et Ambiguïtés**

Cette section explicite les choix de conception faits face aux zones d'ombre du sujet.

### **3.1 Gestion de la "Mise au Rebut"**

* **Analyse :** Le sujet demande de "supprimer une machine de l'inventaire pour la placer dans une liste dite du rebut” et permet de "changer le statut s' il est remis en service".  
* **Décision :** Il ne s'agit pas d'un DELETE SQL définitif, mais d'une **suppression logique** (Soft Delete). Une machine au rebut reste en base de données avec un flag est\_au\_rebut \= 1 ou déplacée dans une table d'archive, afin de permettre sa restauration et son audit.

  ### **3.2 Le Verrouillage du Rebut**

* **Analyse :** L'Admin Web peut "bloquer la liste du rebut pour une future exportation".  
* **Décision :** Création d'un "flag" global de configuration. Lorsque ce flag est actif, les fonctionnalités "Mettre au rebut" et "Restaurer" sont désactivées pour les techniciens. Cela garantit l'intégrité des données lors d'un audit comptable.

  ### **3.3 Normalisation des Données (OS & Constructeurs)**

* **Analyse :** Le sujet spécifie que l'Admin Web crée des infos réutilisables.  
* **Décision :** Création de deux tables dédiées (OS, Constructeurs).  
  * *Avantage :* Évite les doublons (ex: "Windows 10", "Win10", "W10") dans l'inventaire.  
  * *Contrainte :* Le formulaire d'ajout de machine devra utiliser des \<select\> dynamiques alimentés par ces tables.

  ### **3.4 Sécurité et Confidentialité (Visiteur)**

* **Analyse :** Le visiteur voit une "partie de l'inventaire".  
* **Décision :** Masquage strict des données sensibles (Numéros de série, Adresses IP, Dates d'achat) sur l'interface publique pour éviter les failles de sécurité par ingénierie sociale.

