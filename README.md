# AvocatConnect-Plateforme-de-R-servation-de-Consultations-Juridiques
Creation d'un site avec des fonctionnalités multi-rôles pour les utilisateurs et les avocats, une gestion de réservations, et un design moderne et responsive en PHP.


# Projet Professionnel : Gestion des Consultations Juridiques

## Contexte du Projet
Ce projet vise à développer une plateforme web intuitive pour un cabinet d'avocats spécialisé. La plateforme offre des fonctionnalités avancées pour gérer les réservations de consultations, permettant une interaction fluide entre les clients et les avocats.

## Fonctionnalités Principales

### Clients :
- **Consultation des profils d'avocats** : Accès à une liste d'avocats avec leurs spécialités, années d'expérience, et autres informations.
- **Réservation de consultations** : Possibilité de choisir une date et une heure disponibles pour une consultation.
- **Gestion des réservations** : Historique des réservations, modification ou annulation des rendez-vous.
- **Mise à jour des informations personnelles** : Modification du profil utilisateur.

### Avocats :
- **Gestion des réservations** : Accepter ou refuser les demandes de consultations.
- **Mise à jour des disponibilités** : Gestion des créneaux horaires pour les consultations.
- **Gestion du profil** : Modification des spécialités, biographie, photo et autres informations professionnelles.

### Fonctionnalités Techniques :
- **Calendrier Dynamique** : Visualisation des disponibilités des avocats en temps réel.
- **Modals Dynamiques** : Gestion des réservations et consultation des détails dans des fenêtres modales.
- **SweetAlerts** : Alertes visuelles élégantes pour les actions importantes (confirmation, annulation, etc.).
- **Validation Avancée des Formulaires** : Utilisation d'expressions régulières pour valider les entrées des utilisateurs.

## Technologies Utilisées
- **Frontend** :
  - HTML5, Tailwind CSS pour un design moderne et responsive.
  - JavaScript pour l'interactivité (gestion des calendriers, modals, et validations).
- **Backend** :
  - PHP pour le traitement des requêtes et la gestion des sessions.
  - MySQL pour la base de données relationnelle.
- **Serveur Local** : Laragon.

## Structure de la Base de Données

### Tables Principales :
- **`utilisateur`** : Contient les informations de base des utilisateurs (clients et avocats).
- **`infos`** : Informations supplémentaires pour les avocats (spécialités, biographie, etc.).
- **`disponibilite`** : Gestion des créneaux horaires disponibles pour les consultations.
- **`reservations`** : Suivi des réservations entre clients et avocats.

### Exemple de Schéma :
```sql
CREATE TABLE utilisateur (
    us_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(500) NOT NULL,
    role ENUM('Client', 'Avocat') NOT NULL
);
```

## Installation

### Prérequis :
- PHP 7.4+
- MySQL 5.7+
- Laragon ou tout autre serveur local.

### Étapes :
1. Clonez le dépôt Git :
   ```bash
   git clone https://github.com/nmissi-nadia/AvocatConnect.git
   ```
2. Importez le fichier `database.sql` dans votre base de données MySQL.
3. Configurez les paramètres de connexion à la base de données dans `db_connect.php` :
   ```php
   $host = 'localhost';
   $username = 'root';
   $password = '';
   $dbname = 'avocat';
   ```
4. Démarrez le serveur local et accédez au projet via `http://localhost/AvocatConnect`.

## Utilisation

### Connexion et Inscription :
- Les clients et avocats peuvent s'inscrire via les formulaires prévus à cet effet.
- Une fois connecté, chaque utilisateur est redirigé vers son tableau de bord respectif.

### Fonctionnalités :
1. **Clients** :
   - Cliquez sur "Prendre RDV" pour réserver une consultation.
   - Consultez l'historique de vos rendez-vous depuis la section "Mes réservations".
2. **Avocats** :
   - Consultez les demandes de réservation et gérez vos disponibilités depuis le tableau de bord.

## Sécurité
- Hashage des mots de passe avec `password_hash()`.
- Protection contre les injections SQL via des requêtes préparées.
- Validation côté client et serveur pour toutes les entrées.

## Fonctionnalités Futures
- Notifications par email pour les confirmations et rappels de rendez-vous.
- Tableau de bord avec statistiques avancées pour les avocats.
- Intégration d'une API de paiement pour les consultations payantes.

## Auteurs
- **Nmissi Nadia** : Développeur FULL-Stack


