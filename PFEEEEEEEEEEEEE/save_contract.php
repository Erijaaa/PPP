<?php
require_once("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new ClsConnect();
    $pdo = $db->getConnection();
    
    try {
        // Création des tables si elles n'existent pas
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS contrats (
                id SERIAL PRIMARY KEY,
                id_demande VARCHAR(50),
                num_contrat VARCHAR(50),
                date_contrat DATE,
                type_contrat VARCHAR(50),
                num_recu VARCHAR(50),
                montant_total NUMERIC(15,3)
            );

            CREATE TABLE IF NOT EXISTS demandeurs (
                id SERIAL PRIMARY KEY,
                contrat_id INTEGER REFERENCES contrats(id),
                nom VARCHAR(100),
                prenom VARCHAR(100),
                cin VARCHAR(20)
            );

            CREATE TABLE IF NOT EXISTS redacteurs (
                id SERIAL PRIMARY KEY,
                contrat_id INTEGER REFERENCES contrats(id),
                nom VARCHAR(100),
                prenom VARCHAR(100),
                cin VARCHAR(20),
                qualite VARCHAR(100)
            );

            CREATE TABLE IF NOT EXISTS documents_contrat (
                id SERIAL PRIMARY KEY,
                contrat_id INTEGER REFERENCES contrats(id),
                num_ordre INTEGER,
                type_document VARCHAR(100),
                date_document DATE,
                ref_enregistrement VARCHAR(50),
                date_enregistrement DATE,
                num_pages INTEGER
            );

            CREATE TABLE IF NOT EXISTS parties_contrat (
                id SERIAL PRIMARY KEY,
                contrat_id INTEGER REFERENCES contrats(id),
                nom VARCHAR(100),
                prenom VARCHAR(100),
                nom_pere VARCHAR(100),
                nom_grandpere VARCHAR(100),
                nationalite VARCHAR(50),
                qualite VARCHAR(100),
                cin VARCHAR(20),
                date_emission_cin DATE,
                lieu_emission_cin VARCHAR(100),
                adresse TEXT
            );

            CREATE TABLE IF NOT EXISTS details_transaction (
                id SERIAL PRIMARY KEY,
                contrat_id INTEGER REFERENCES contrats(id),
                objet_contrat TEXT,
                montant NUMERIC(15,3),
                description TEXT,
                ref_propriete VARCHAR(100)
            );

            CREATE TABLE IF NOT EXISTS frais_contrat (
                id SERIAL PRIMARY KEY,
                contrat_id INTEGER REFERENCES contrats(id),
                date_frais DATE,
                num_recu VARCHAR(50),
                montant_du NUMERIC(10,3),
                montant_paye NUMERIC(10,3),
                beneficiaire VARCHAR(100)
            );
        ");

        // Début de la transaction
        $pdo->beginTransaction();
        
        // Récupération et validation des données POST
        $id_demande = isset($_POST['id_demande']) ? $_POST['id_demande'] : '';
        $num_contrat = isset($_POST['num_contrat']) ? $_POST['num_contrat'] : '';
        $num_recu = isset($_POST['num_recu']) ? $_POST['num_recu'] : '';
        $montant_total = isset($_POST['montant_total']) ? $_POST['montant_total'] : 0;

        // Vérification des données obligatoires
        if (empty($id_demande) || empty($num_contrat)) {
            throw new Exception("Les champs id_demande et num_contrat sont obligatoires");
        }
        
        // Sauvegarde des données générales du contrat
        $stmt = $pdo->prepare("INSERT INTO contrats (
            id_demande,
            num_contrat,
            date_contrat,
            type_contrat,
            num_recu,
            montant_total
        ) VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $id_demande,
            $num_contrat,
            date('Y-m-d'),
            'وعد بيع',
            $num_recu,
            $montant_total
        ]);
        
        $contrat_id = $pdo->lastInsertId();
        
        // Sauvegarde des informations du demandeur si présentes
        if (isset($_POST['nom_demandeur']) && isset($_POST['prenom_demandeur'])) {
            $stmt = $pdo->prepare("INSERT INTO demandeurs (
                contrat_id,
                nom,
                prenom,
                cin
            ) VALUES (?, ?, ?, ?)");
            
            $stmt->execute([
                $contrat_id,
                $_POST['nom_demandeur'],
                $_POST['prenom_demandeur'],
                $_POST['cin_demandeur'] ?? ''
            ]);
        }
        
        // Sauvegarde des parties du contrat si présentes
        if (isset($_POST['parties']) && is_array($_POST['parties'])) {
            $stmt = $pdo->prepare("INSERT INTO parties_contrat (
                contrat_id,
                nom,
                prenom,
                nom_pere,
                nom_grandpere,
                nationalite,
                qualite,
                cin
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            foreach ($_POST['parties'] as $partie) {
                $stmt->execute([
                    $contrat_id,
                    $partie['nom'] ?? '',
                    $partie['prenom'] ?? '',
                    $partie['nom_pere'] ?? '',
                    $partie['nom_grandpere'] ?? '',
                    $partie['nationalite'] ?? '',
                    $partie['qualite'] ?? '',
                    $partie['cin'] ?? ''
                ]);
            }
        }
        
        // Validation de la transaction
        $pdo->commit();
        
        // Redirection vers la génération du PDF
        header("Location: generate_pdf.php?contrat_id=" . $contrat_id . 
               "&id_demande=" . $id_demande . 
               "&num_recu=" . $num_recu . 
               "&num_contrat=" . $num_contrat);
        exit();
        
    } catch (Exception $e) {
        // En cas d'erreur, annulation de la transaction
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()]);
    }
}
?> 