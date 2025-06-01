-- Structure de la base de données pour la gestion des contrats immobiliers

-- Table des utilisateurs
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'redacteur', 'valideur') NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des demandes
CREATE TABLE demandes (
    id_demande INT PRIMARY KEY AUTO_INCREMENT,
    type_demande INT NOT NULL,
    date_demande DATE NOT NULL,
    etat_demande INT DEFAULT 0, -- 0: en attente, 1: validée, -1: rejetée
    num_recu VARCHAR(50),
    id_redacteur INT,
    FOREIGN KEY (id_redacteur) REFERENCES users(id)
);

-- Table des contrats
CREATE TABLE contrats (
    id_contrat INT PRIMARY KEY AUTO_INCREMENT,
    id_demande INT,
    date_creation DATE NOT NULL,
    etat_contrat INT DEFAULT 0, -- 0: en attente, 1: validé, -1: rejeté
    motif_rejet TEXT,
    id_redacteur INT,
    id_valideur INT,
    FOREIGN KEY (id_demande) REFERENCES demandes(id_demande),
    FOREIGN KEY (id_redacteur) REFERENCES users(id),
    FOREIGN KEY (id_valideur) REFERENCES users(id)
);

-- Table des parties du contrat
CREATE TABLE parties_contrat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_contrat INT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    cin VARCHAR(20),
    date_naissance DATE,
    lieu_naissance VARCHAR(100),
    adresse VARCHAR(255),
    type_partie ENUM('vendeur', 'acheteur') NOT NULL,
    FOREIGN KEY (id_contrat) REFERENCES contrats(id_contrat)
);

-- Table des détails du contrat
CREATE TABLE details_contrat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_contrat INT,
    montant DECIMAL(15,3) NOT NULL,
    surface DECIMAL(10,2),
    adresse_bien VARCHAR(255),
    description_bien TEXT,
    num_titre_foncier VARCHAR(50),
    FOREIGN KEY (id_contrat) REFERENCES contrats(id_contrat)
);

-- Table des documents joints
CREATE TABLE documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_contrat INT,
    type_document VARCHAR(50),
    reference VARCHAR(100),
    date_document DATE,
    FOREIGN KEY (id_contrat) REFERENCES contrats(id_contrat)
); 