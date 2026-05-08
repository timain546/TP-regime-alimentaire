CREATE DATABASE IF NOT EXISTS regim CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE regim;

DROP TABLE IF EXISTS Transactions;
DROP TABLE IF EXISTS SouscriptionRegime;
DROP TABLE IF EXISTS CodeRecharge;
DROP TABLE IF EXISTS ParametreGlobal;
DROP TABLE IF EXISTS DureeActivite;
DROP TABLE IF EXISTS ActiviteSportive;
DROP TABLE IF EXISTS PrixRegime;
DROP TABLE IF EXISTS DureeRegime;
DROP TABLE IF EXISTS RegimeFille;
DROP TABLE IF EXISTS Aliment;
DROP TABLE IF EXISTS RegimeMere;
DROP TABLE IF EXISTS Sante;
DROP TABLE IF EXISTS Client;

CREATE TABLE Client (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    genre ENUM('Homme', 'Femme') NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    is_gold TINYINT(1) NOT NULL DEFAULT 0,
    role ENUM('client', 'admin') NOT NULL DEFAULT 'client',
    objectif ENUM('augmenter', 'reduire', 'imc_ideal') NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Sante (
    id_sante INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    date DATE NOT NULL,
    taille DECIMAL(5,2) NOT NULL,
    poids DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (id_client) REFERENCES Client(id_client) ON DELETE CASCADE
);

CREATE TABLE RegimeMere (
    id_regime INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(120) NOT NULL,
    objectif ENUM('augmenter', 'reduire', 'imc_ideal') NOT NULL DEFAULT 'imc_ideal',
    description TEXT NULL
);

CREATE TABLE Aliment (
    id_aliment INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(80) NOT NULL UNIQUE
);

CREATE TABLE RegimeFille (
    id_regime_fille INT AUTO_INCREMENT PRIMARY KEY,
    id_regime INT NOT NULL,
    id_aliment INT NOT NULL,
    pourcentage DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (id_regime) REFERENCES RegimeMere(id_regime) ON DELETE CASCADE,
    FOREIGN KEY (id_aliment) REFERENCES Aliment(id_aliment) ON DELETE CASCADE,
    CONSTRAINT chk_regime_fille_pourcentage CHECK (pourcentage >= 0 AND pourcentage <= 100)
);

CREATE TABLE DureeRegime (
    id_duree_regime INT AUTO_INCREMENT PRIMARY KEY,
    id_regime INT NOT NULL,
    variation_poids DECIMAL(5,2) NOT NULL,
    nb_jours INT NOT NULL,
    FOREIGN KEY (id_regime) REFERENCES RegimeMere(id_regime) ON DELETE CASCADE
);

CREATE TABLE PrixRegime (
    id_prix_regime INT AUTO_INCREMENT PRIMARY KEY,
    id_regime INT NOT NULL,
    duree_jours INT NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_regime) REFERENCES RegimeMere(id_regime) ON DELETE CASCADE
);

CREATE TABLE ActiviteSportive (
    id_activite INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(120) NOT NULL,
    description TEXT NULL,
    duree_minutes INT NOT NULL DEFAULT 30,
    objectif ENUM('augmenter', 'reduire', 'imc_ideal') NOT NULL DEFAULT 'imc_ideal',
    intensite ENUM('faible', 'moyenne', 'forte') NOT NULL DEFAULT 'moyenne'
);

CREATE TABLE DureeActivite (
    id_duree_activite INT AUTO_INCREMENT PRIMARY KEY,
    id_activite INT NOT NULL,
    variation_poids DECIMAL(5,2) NOT NULL,
    nb_jours INT NOT NULL,
    FOREIGN KEY (id_activite) REFERENCES ActiviteSportive(id_activite) ON DELETE CASCADE
);

CREATE TABLE CodeRecharge (
    id_code_recharge INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(40) NOT NULL UNIQUE,
    valeur DECIMAL(10,2) NOT NULL,
    is_valide TINYINT(1) NOT NULL DEFAULT 1,
    is_utilise TINYINT(1) NOT NULL DEFAULT 0,
    id_client INT NULL,
    date_utilisation DATETIME NULL,
    FOREIGN KEY (id_client) REFERENCES Client(id_client) ON DELETE SET NULL
);

CREATE TABLE Transactions (
    id_transaction INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    type ENUM('debit', 'credit') NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES Client(id_client) ON DELETE CASCADE
);

CREATE TABLE Programme (
    id_programme INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT,
    objectif ENUM('augmenter', 'reduire', 'imc_ideal') NOT NULL,
    poids_depart DECIMAL(5,2) NOT NULL,
    poids_cible DECIMAL(5,2) NOT NULL,
    variation_voulue DECIMAL(5,2) NOT NULL,
    date_creation DATETIME,
    duree_estimee_jours INT,
    cout_total_estime DECIMAL(10,2),
);
CREATE TABLE ProgrammeElement (
    id_element INT AUTO_INCREMENT PRIMARY KEY,
    id_programme INT NOT NULL,
    type_element ENUM('regime', 'sport') NOT NULL,
    id_regime INT,
    id_activite INT,
    jour_debut INT,
    jour_fin INT,
    variation_par_jour DECIMAL(6,3),
    cout_par_jour DECIMAL(10,2) DEFAULT 0,
);
CREATE TABLE ParametreGlobal (
    id_parametre INT AUTO_INCREMENT PRIMARY KEY,
    cle VARCHAR(80) NOT NULL UNIQUE,
    valeur VARCHAR(160) NOT NULL,
    description VARCHAR(255) NULL
);

INSERT INTO Client (nom, email, genre, mot_de_passe, is_gold, role, objectif, created_at) VALUES
('Admin Regim', 'admin@regim.test', 'Homme', 'admin123', 1, 'admin', 'imc_ideal', '2026-05-01 08:00:00'),
('Miora', 'miora@test.mg', 'Femme', 'client123', 0, 'client', 'imc_ideal', '2026-05-02 08:00:00'),
('Toky', 'toky@test.mg', 'Homme', 'client123', 0, 'client', 'reduire', '2026-05-03 08:00:00'),
('Sariaka', 'sariaka@test.mg', 'Femme', 'client123', 1, 'client', 'augmenter', '2026-05-04 08:00:00'),
('Hery', 'hery@test.mg', 'Homme', 'client123', 0, 'client', 'reduire', '2026-05-05 08:00:00');

INSERT INTO Sante (id_client, date, taille, poids) VALUES
(1, '2026-05-08', 175, 72),
(2, '2026-05-08', 162, 58),
(3, '2026-05-08', 180, 95),
(4, '2026-05-08', 168, 49),
(5, '2026-05-08', 172, 78);

INSERT INTO RegimeMere (libelle, objectif, description) VALUES
('Equilibre Minceur', 'reduire', 'Menu hypocalorique progressif'),
('Seche Active', 'reduire', 'Repas proteines et glucides controles'),
('Masse Saine', 'augmenter', 'Menu riche en calories utiles'),
('Force Plus', 'augmenter', 'Prise de poids avec apport proteique eleve'),
('IMC Ideal', 'imc_ideal', 'Programme de stabilisation vers un IMC normal');

INSERT INTO Aliment (nom) VALUES
('Viande'),
('Poisson'),
('Volaille');

INSERT INTO RegimeFille (id_regime, id_aliment, pourcentage) VALUES
(1, 1, 30), (1, 2, 45), (1, 3, 25),
(2, 1, 35), (2, 2, 40), (2, 3, 25),
(3, 1, 40), (3, 2, 25), (3, 3, 35),
(4, 1, 45), (4, 2, 20), (4, 3, 35),
(5, 1, 34), (5, 2, 33), (5, 3, 33);

INSERT INTO DureeRegime (id_regime, variation_poids, nb_jours) VALUES
(1, -3.00, 30),
(2, -5.00, 45),
(3, 4.00, 30),
(4, 6.00, 60),
(5, 2.00, 40);

INSERT INTO PrixRegime (id_regime, duree_jours, prix) VALUES
(1, 15, 50000), (1, 30, 90000), (1, 45, 130000),
(2, 30, 95000), (2, 45, 135000), (2, 60, 170000),
(3, 15, 65000), (3, 30, 110000), (3, 45, 160000),
(4, 30, 120000), (4, 60, 210000), (4, 90, 300000),
(5, 20, 70000), (5, 40, 125000), (5, 60, 175000);

INSERT INTO ActiviteSportive (libelle, description, duree_minutes, objectif, intensite) VALUES
('Marche rapide', 'Cardio doux quotidien', 45, 'reduire', 'moyenne'),
('HIIT debutant', 'Circuit court pour bruler des calories', 25, 'reduire', 'forte'),
('Musculation full body', 'Renforcement pour prise de masse', 50, 'augmenter', 'forte'),
('Yoga mobilite', 'Souplesse et recuperation', 35, 'imc_ideal', 'faible'),
('Natation endurance', 'Cardio complet et progressif', 40, 'imc_ideal', 'moyenne');

INSERT INTO DureeActivite (id_activite, variation_poids, nb_jours) VALUES
(1, -2.00, 30),
(2, -4.00, 30),
(3, 3.00, 30),
(4, 0.00, 30),
(5, -1.50, 30);

INSERT INTO CodeRecharge (code, valeur, is_valide) VALUES
('REGIM-001', 10000, 1),
('REGIM-002', 15000, 1),
('REGIM-003', 20000, 1),
('REGIM-004', 25000, 1),
('REGIM-005', 30000, 1),
('REGIM-006', 35000, 1),
('REGIM-007', 40000, 1),
('REGIM-008', 45000, 1),
('REGIM-009', 50000, 1),
('REGIM-010', 55000, 1),
('REGIM-011', 60000, 1),
('REGIM-012', 65000, 1),
('REGIM-013', 70000, 1),
('REGIM-014', 75000, 1),
('REGIM-015', 80000, 1);

INSERT INTO Transactions (id_client, montant, type, date) VALUES
(2, 20000, 'credit', '2026-05-08 08:00:00'),
(3, 10000, 'credit', '2026-05-08 08:10:00'),
(4, 93500, 'debit', '2026-05-08 08:20:00');

INSERT INTO SouscriptionRegime (id_client, id_regime, id_activite, duree_jours, prix_total, date_souscription) VALUES
(2, 5, 4, 40, 125000, '2026-05-08 08:30:00'),
(3, 1, 1, 30, 90000, '2026-05-08 08:40:00'),
(4, 3, 3, 30, 93500, '2026-05-08 08:50:00');

INSERT INTO ParametreGlobal (cle, valeur, description) VALUES
('prix_gold', '50000', 'Prix unique de l option Gold'),
('imc_min_normal', '18.5', 'Borne basse IMC normal'),
('imc_max_normal', '24.9', 'Borne haute IMC normal'),
('remise_gold', '15', 'Pourcentage de remise Gold');
