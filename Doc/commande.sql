-- -------------------------
-- 1. Création de la base de données
-- -------------------------
CREATE DATABASE AvocatConnect;
USE AvocatConnect;

-- -------------------------
-- 2. Création des tables
-- -------------------------

-- Table User
CREATE TABLE User (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(15),
    photo_profil VARCHAR(255),
    specialite INT DEFAULT NULL,
    biographie TEXT DEFAULT NULL,
    role ENUM('Client', 'Avocat') NOT NULL,
    FOREIGN KEY (specialite) REFERENCES Specialite(id_spe) ON DELETE SET NULL
);

-- Table Specialite
CREATE TABLE Specialite (
    id_spe INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(100) NOT NULL
);

-- Table Reservation
CREATE TABLE Reservation (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,
    date_reservation DATETIME NOT NULL,
    id_client INT NOT NULL,
    id_avocat INT NOT NULL,
    statut ENUM('En attente', 'Acceptée', 'Refusée') DEFAULT 'En attente',
    FOREIGN KEY (id_client) REFERENCES User(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_avocat) REFERENCES User(id_user) ON DELETE CASCADE
);

-- Table Disponibilite
CREATE TABLE Disponibilite (
    id_disponibilite INT AUTO_INCREMENT PRIMARY KEY,
    id_avocat INT NOT NULL,
    date_disponible DATE NOT NULL,
    statut BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_avocat) REFERENCES User(id_user) ON DELETE CASCADE
);

-- -------------------------
-- 3. Insertion des données initiales
-- -------------------------

-- Données pour la table Specialite
INSERT INTO Specialite (label) VALUES 
('Droit du Travail'),
('Droit Pénal'),
('Droit Civil'),
('Droit des Affaires'),
('Droit de la Famille');

-- Données pour la table User (Clients et Avocats)
INSERT INTO User (nom, prenom, email, mot_de_passe, telephone, photo_profil, specialite, biographie, role) VALUES 
('Dupont', 'Jean', 'jean.dupont@gmail.com', 'motdepassehashé1', '0612345678', NULL, NULL, NULL, 'Client'),
('Martin', 'Lucie', 'lucie.martin@gmail.com', 'motdepassehashé2', '0698765432', NULL, NULL, NULL, 'Client'),
('Morel', 'Paul', 'paul.morel@gmail.com', 'motdepassehashé3', '0687654321', NULL, NULL, NULL, 'Client'),
('Durand', 'Sophie', 'sophie.durand@gmail.com', 'motdepassehashé4', '0623456789', NULL, NULL, NULL, 'Client'),
('Bernard', 'Alice', 'alice.bernard@gmail.com', 'motdepassehashé5', '0671234567', NULL, NULL, NULL, 'Client'),
('Lambert', 'Thomas', 'thomas.lambert@gmail.com', 'motdepassehashé6', '0611122233', 'images/profil1.jpg', 1, 'Avocat spécialisé en droit du travail', 'Avocat'),
('Giraud', 'Elisa', 'elisa.giraud@gmail.com', 'motdepassehashé7', '0692233445', 'images/profil2.jpg', 2, 'Avocate spécialisée en droit pénal', 'Avocat'),
('Roux', 'Marc', 'marc.roux@gmail.com', 'motdepassehashé8', '0622334455', 'images/profil3.jpg', 3, 'Avocat expérimenté en droit civil', 'Avocat'),
('Petit', 'Camille', 'camille.petit@gmail.com', 'motdepassehashé9', '0655443322', 'images/profil4.jpg', 4, 'Avocate en droit des affaires', 'Avocat'),
('Blanc', 'Emma', 'emma.blanc@gmail.com', 'motdepassehashé10', '0699887766', 'images/profil5.jpg', 5, 'Avocate en droit de la famille', 'Avocat');

-- Données pour la table Disponibilite
INSERT INTO Disponibilite (id_avocat, date_disponible, statut) VALUES 
(6, '2024-12-20', TRUE),
(6, '2024-12-21', FALSE),
(7, '2024-12-22', TRUE),
(8, '2024-12-23', TRUE),
(9, '2024-12-24', FALSE),
(10, '2024-12-25', TRUE);

-- Données pour la table Reservation
INSERT INTO Reservation (date_reservation, id_client, id_avocat, statut) VALUES 
('2024-12-20 10:00:00', 1, 6, 'En attente'),
('2024-12-21 15:00:00', 2, 7, 'Acceptée'),
('2024-12-22 11:30:00', 3, 8, 'Refusée'),
('2024-12-23 09:00:00', 4, 9, 'En attente'),
('2024-12-24 14:00:00', 5, 10, 'Acceptée');

-- -------------------------
-- 4. Requêtes SQL courantes
-- -------------------------

-- Lister tous les avocats avec leurs spécialités
SELECT U.nom, U.prenom, S.label 
FROM User U 
JOIN Specialite S ON U.specialite = S.id_spe 
WHERE U.role = 'Avocat';

-- Voir toutes les réservations d'un client donné
SELECT R.id_reservation, R.date_reservation, U.nom AS 'Nom Avocat', R.statut 
FROM Reservation R 
JOIN User U ON R.id_avocat = U.id_user 
WHERE R.id_client = 1;

-- Voir les disponibilités d'un avocat spécifique
SELECT D.date_disponible, D.statut 
FROM Disponibilite D 
WHERE D.id_avocat = 6;

-- Rechercher les avocats par spécialité
SELECT U.nom, U.prenom, S.label 
FROM User U 
JOIN Specialite S ON U.specialite = S.id_spe 
WHERE S.label = 'Droit du Travail';

-- Modifier le statut d'une réservation
UPDATE Reservation 
SET statut = 'Acceptée' 
WHERE id_reservation = 1;
