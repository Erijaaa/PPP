<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connect = new ClsConnect();
    $pdo = $connect->getConnection();

    // Récupérer les données du formulaire
    $old_email = $_POST['old_email'];
    $role = $_POST['role'];
    $fullname = $_POST['agentName'];
    list($nom, $prenom) = explode(' ', $fullname, 2);
    $cin = $_POST['cin'];
    $email = $_POST['agentEmail'];
    $adresse = $_POST['agentAdresse'];
    $telephone = $_POST['agentTele'];
    $date_naissance = $_POST['agentNaissance'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    try {
        // Début de la transaction
        $pdo->beginTransaction();

        // Construire la requête de mise à jour en fonction du rôle
        if ($role === 'redacteur') {
            $sql = "UPDATE redacteur SET 
                    nom_redacteur = :nom,
                    prenom_redacteur = :prenom,
                    cin_redacteur = :cin,
                    email = :email,
                    adresse = :adresse,
                    telephone = :telephone,
                    date_naissance = :date_naissance";
            
            if ($password !== null) {
                $sql .= ", password = :password";
            }
            
            $sql .= " WHERE email = :old_email";
        } else {
            $sql = "UPDATE valideur SET 
                    nom_valideur = :nom,
                    prenom_valideur = :prenom,
                    cin_valideur = :cin,
                    email = :email,
                    adresse = :adresse,
                    telephone = :telephone,
                    date_naissance = :date_naissance";
            
            if ($password !== null) {
                $sql .= ", password = :password";
            }
            
            $sql .= " WHERE email = :old_email";
        }

        $stmt = $pdo->prepare($sql);
        
        // Lier les paramètres
        $params = [
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':cin' => $cin,
            ':email' => $email,
            ':adresse' => $adresse,
            ':telephone' => $telephone,
            ':date_naissance' => $date_naissance,
            ':old_email' => $old_email
        ];
        
        if ($password !== null) {
            $params[':password'] = $password;
        }
        
        $stmt->execute($params);

        // Valider la transaction
        $pdo->commit();

        // Rediriger avec un message de succès
        header('Location: pageAdmin.php?success=1');
        exit;

    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $pdo->rollBack();
        
        // Rediriger avec un message d'erreur
        header('Location: pageAdmin.php?error=' . urlencode($e->getMessage()));
        exit;
    }
}

// Si la méthode n'est pas POST, rediriger vers la page admin
header('Location: pageAdmin.php');
exit; 