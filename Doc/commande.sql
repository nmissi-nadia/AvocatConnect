-- ---------------------------------
-- 1. Création de la base de données
-- ---------------------------------
CREATE DATABASE avocat;
USE avocat;

-- -------------------------
-- 2. Création des tables
-- -------------------------

-- Table Utilisateur
CREATE TABLE utilisateur (
    us_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(500) NOT NULL,
    role ENUM('Client', 'Avocat') NOT NULL
);

-- Table Informations (détails des avocats)
CREATE TABLE infos (
    info_id INT PRIMARY KEY AUTO_INCREMENT,
    avocat_id INT NOT NULL,
    specialite VARCHAR(50),
    biography TEXT,
    annee_experience VARCHAR(50),
    picture varchar(50),
    location VARCHAR(150),
    FOREIGN KEY (avocat_id) REFERENCES utilisateur(us_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Table Disponibilite
CREATE TABLE disponibilite (
    dispo_id INT PRIMARY KEY AUTO_INCREMENT,
    avocat_id INT NOT NULL,
    dispo_date DATE NOT NULL,
    statut ENUM('disponible', 'occupe') DEFAULT 'disponible',
    FOREIGN KEY (avocat_id) REFERENCES utilisateur(us_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Table Reservation
CREATE TABLE reservations (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    avocat_id INT NOT NULL,
    client_id INT NOT NULL,
    dispo_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    FOREIGN KEY (avocat_id) REFERENCES utilisateur(us_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (client_id) REFERENCES utilisateur(us_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (dispo_id) REFERENCES disponibilite(dispo_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
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
