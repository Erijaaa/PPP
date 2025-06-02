-- Table pour les données générales
CREATE TABLE donnees_generales (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    annee_demande INTEGER,
    date_demande DATE,
    num_contrat VARCHAR(50),
    sujet_contrat TEXT
);

-- Table pour les informations du déposant
CREATE TABLE deposant (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    nom_deposant VARCHAR(100),
    prenom_deposant VARCHAR(100),
    FOREIGN KEY (id_demande) REFERENCES donnees_generales(id_demande)
);

-- Table pour les documents/pièces jointes
CREATE TABLE pieces_jointes (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    libile_pieces VARCHAR(200),
    date_document DATE,
    ref_document VARCHAR(100),
    date_ref DATE,
    code_pieces VARCHAR(50),
    FOREIGN KEY (id_demande) REFERENCES donnees_generales(id_demande)
);

-- Table pour les parties contractantes
CREATE TABLE parties_contrat (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    nom VARCHAR(100),
    prenom VARCHAR(100),
    nom_pere VARCHAR(100),
    nom_grandpere VARCHAR(100),
    qualite VARCHAR(100),
    num_identite VARCHAR(50),
    date_emission DATE,
    nationalite VARCHAR(50),
    adresse TEXT,
    profession VARCHAR(100),
    etat_civil VARCHAR(50),
    FOREIGN KEY (id_demande) REFERENCES donnees_generales(id_demande)
);

-- Table pour le sujet du contrat
CREATE TABLE sujet_contrat (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    num_ordre INTEGER,
    designation TEXT,
    qualite VARCHAR(100),
    ref_titre VARCHAR(100),
    num_droit VARCHAR(50),
    objet_contrat TEXT,
    unite VARCHAR(50),
    subdivision VARCHAR(100),
    contenu TEXT,
    valeur_prix DECIMAL(15,2),
    duree VARCHAR(50),
    beneficiaire VARCHAR(100),
    FOREIGN KEY (id_demande) REFERENCES donnees_generales(id_demande)
);

-- Table pour les charges sur la propriété
CREATE TABLE charges_propriete (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    num_droit VARCHAR(50),
    objet_contrat TEXT,
    unite VARCHAR(50),
    subdivision VARCHAR(100),
    contenu TEXT,
    prix DECIMAL(15,2),
    duree VARCHAR(50),
    excedent VARCHAR(100),
    FOREIGN KEY (id_demande) REFERENCES donnees_generales(id_demande)
);

-- Table pour les références d'inscription
CREATE TABLE references_inscription (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    date_inscription DATE,
    depot VARCHAR(100),
    volume VARCHAR(50),
    numero VARCHAR(50),
    num_subsidiaire VARCHAR(50),
    FOREIGN KEY (id_demande) REFERENCES donnees_generales(id_demande)
);

-- Table pour les termes du contrat
CREATE TABLE termes_contrat (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    contenu TEXT,
    FOREIGN KEY (id_demande) REFERENCES donnees_generales(id_demande)
);

-- Table pour les signatures
CREATE TABLE signatures (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    nom VARCHAR(100),
    nom_pere VARCHAR(100),
    nom_grandpere VARCHAR(100),
    prenom VARCHAR(100),
    qualite VARCHAR(100),
    signature TEXT,
    FOREIGN KEY (id_demande) REFERENCES donnees_generales(id_demande)
);

-- Table pour les frais d'édition
CREATE TABLE frais_edition (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    id_frais VARCHAR(50),
    partie_percue VARCHAR(100),
    montant_du DECIMAL(15,2),
    montant_percu DECIMAL(15,2),
    num_recu VARCHAR(50),
    date_perception DATE,
    FOREIGN KEY (id_demande) REFERENCES donnees_generales(id_demande)
);

-- Table pour l'enregistrement fiscal
CREATE TABLE enregistrement_fiscal (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    valeur_dinar DECIMAL(15,2),
    taux DECIMAL(5,2),
    montant DECIMAL(15,2),
    FOREIGN KEY (id_demande) REFERENCES donnees_generales(id_demande)
);

-- Table pour les frais de service
CREATE TABLE frais_service (
    id SERIAL PRIMARY KEY,
    id_demande VARCHAR(50),
    designation VARCHAR(200),
    valeur_dinar DECIMAL(15,2),
    taux DECIMAL(5,2),
    montant DECIMAL(15,2),
    FOREIGN KEY (id_demande) REFERENCES donnees_generales(id_demande)
); 