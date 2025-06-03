<?php
require_once("connect.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create database connection
$db = new ClsConnect();
$pdo = $db->getConnection();

try {
    // Start transaction
    $pdo->beginTransaction();

    // 1. Save General Data
    $stmt = $pdo->prepare("INSERT INTO donnees_generales (id_demande, annee_demande, date_demande, num_contrat, sujet_contrat) 
                          VALUES (:id_demande, :annee_demande, :date_demande, :num_contrat, :sujet_contrat)");
    
    $stmt->execute([
        ':id_demande' => $_POST['id_demande'],
        ':annee_demande' => $_POST['annee_demande'],
        ':date_demande' => $_POST['date_demande'],
        ':num_contrat' => $_POST['id_contrat'],
        ':sujet_contrat' => $_POST['sujet_contrat']
    ]);

    // 2. Save Deposant Information
    $stmt = $pdo->prepare("INSERT INTO deposant (id_demande, nom_deposant, prenom_deposant) 
                          VALUES (:id_demande, :nom_deposant, :prenom_deposant)");
    
    $stmt->execute([
        ':id_demande' => $_POST['id_demande'],
        ':nom_deposant' => $_POST['nom_deposant'],
        ':prenom_deposant' => $_POST['prenom_deposant']
    ]);

    // 3. Save Documents/Pieces Jointes
    if (isset($_POST['libile_pieces']) && is_array($_POST['libile_pieces'])) {
        $stmt = $pdo->prepare("INSERT INTO pieces_jointes (id_demande, libile_pieces, date_document, ref_document, date_ref, code_pieces) 
                              VALUES (:id_demande, :libile_pieces, :date_document, :ref_document, :date_ref, :code_pieces)");
        
        foreach ($_POST['libile_pieces'] as $key => $libile) {
            $stmt->execute([
                ':id_demande' => $_POST['id_demande'],
                ':libile_pieces' => $libile,
                ':date_document' => $_POST['date_document'][$key],
                ':ref_document' => $_POST['ref_document'][$key],
                ':date_ref' => $_POST['date_ref'][$key],
                ':code_pieces' => $_POST['code_pieces'][$key]
            ]);
        }
    }

    // 4. Save Contract Parties
    if (isset($_POST['nom']) && is_array($_POST['nom'])) {
        $stmt = $pdo->prepare("INSERT INTO parties_contrat (id_demande, nom, prenom, nom_pere, nom_grandpere, qualite, 
                              num_identite, date_emission, nationalite, adresse, profession, etat_civil) 
                              VALUES (:id_demande, :nom, :prenom, :nom_pere, :nom_grandpere, :qualite, 
                              :num_identite, :date_emission, :nationalite, :adresse, :profession, :etat_civil)");
        
        foreach ($_POST['nom'] as $key => $nom) {
            $stmt->execute([
                ':id_demande' => $_POST['id_demande'],
                ':nom' => $nom,
                ':prenom' => $_POST['prenom'][$key],
                ':nom_pere' => $_POST['nom_pere'][$key],
                ':nom_grandpere' => $_POST['nom_grandpere'][$key],
                ':qualite' => $_POST['qualite'][$key],
                ':num_identite' => $_POST['num_identite'][$key],
                ':date_emission' => $_POST['date_emission'][$key],
                ':nationalite' => $_POST['nationalite'][$key],
                ':adresse' => $_POST['adresse'][$key],
                ':profession' => $_POST['profession'][$key],
                ':etat_civil' => $_POST['etat_civil'][$key]
            ]);
        }
    }

    // 5. Save Contract Subject
    if (isset($_POST['designation'])) {
        $stmt = $pdo->prepare("INSERT INTO sujet_contrat (id_demande, num_ordre, designation, qualite, ref_titre, 
                              num_droit, objet_contrat, unite, subdivision, contenu, valeur_prix, duree, beneficiaire) 
                              VALUES (:id_demande, :num_ordre, :designation, :qualite, :ref_titre, 
                              :num_droit, :objet_contrat, :unite, :subdivision, :contenu, :valeur_prix, :duree, :beneficiaire)");
        
        $stmt->execute([
            ':id_demande' => $_POST['id_demande'],
            ':num_ordre' => $_POST['num_ordre'],
            ':designation' => $_POST['designation'],
            ':qualite' => $_POST['qualite'],
            ':ref_titre' => $_POST['ref_titre'],
            ':num_droit' => $_POST['num_droit'],
            ':objet_contrat' => $_POST['objet_contrat'],
            ':unite' => $_POST['unite'],
            ':subdivision' => $_POST['subdivision'],
            ':contenu' => $_POST['contenu'],
            ':valeur_prix' => $_POST['valeur_prix'],
            ':duree' => $_POST['duree'],
            ':beneficiaire' => $_POST['beneficiaire']
        ]);
    }

    // 6. Save Property Charges
    if (isset($_POST['charges_num_droit'])) {
        $stmt = $pdo->prepare("INSERT INTO charges_propriete (id_demande, num_droit, objet_contrat, unite, 
                              subdivision, contenu, prix, duree, excedent) 
                              VALUES (:id_demande, :num_droit, :objet_contrat, :unite, 
                              :subdivision, :contenu, :prix, :duree, :excedent)");
        
        $stmt->execute([
            ':id_demande' => $_POST['id_demande'],
            ':num_droit' => $_POST['charges_num_droit'],
            ':objet_contrat' => $_POST['charges_objet_contrat'],
            ':unite' => $_POST['charges_unite'],
            ':subdivision' => $_POST['charges_subdivision'],
            ':contenu' => $_POST['charges_contenu'],
            ':prix' => $_POST['charges_prix'],
            ':duree' => $_POST['charges_duree'],
            ':excedent' => $_POST['charges_excedent']
        ]);
    }

    // 7. Save Contract Terms
    if (isset($_POST['termes_contenu'])) {
        $stmt = $pdo->prepare("INSERT INTO termes_contrat (id_demande, contenu) VALUES (:id_demande, :contenu)");
        
        $stmt->execute([
            ':id_demande' => $_POST['id_demande'],
            ':contenu' => $_POST['termes_contenu']
        ]);
    }

    // 8. Save Registration References (مراجع انجرار الترسيم)
    if (isset($_POST['date_inscri2'])) {
        $stmt = $pdo->prepare("INSERT INTO references_inscription (id_demande, date_inscription, lieu_depot, volume, numero, numero_subsidiaire) 
                              VALUES (:id_demande, :date_inscription, :lieu_depot, :volume, :numero, :numero_subsidiaire)");
        
        $stmt->execute([
            ':id_demande' => $_POST['id_demande'],
            ':date_inscription' => $_POST['date_inscri2'],
            ':lieu_depot' => $_POST['lieu_inscri2'],
            ':volume' => $_POST['doc2'],
            ':numero' => $_POST['num_inscri2'],
            ':numero_subsidiaire' => $_POST['num_succursale2']
        ]);
    }

    // Commit transaction
    $pdo->commit();
    
    // Redirect back to Traitement.php with success message
    header("Location: Traitement.php?id_demande=" . $_POST['id_demande'] . "&success=1");
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    
    // Redirect back with error
    header("Location: Traitement.php?id_demande=" . $_POST['id_demande'] . "&error=" . urlencode($e->getMessage()));
    exit();
}
?> 