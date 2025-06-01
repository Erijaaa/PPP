<?php
session_start();
require_once("connect.php");

$db = new ClsConnect();
$pdo = $db->getConnection();

// Vérifier si l'utilisateur est connecté et est un valideur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'valideur') {
    header('Location: index.php');
    exit;
}

$id_contrat = $_GET['id_contrat'] ?? null;
$id_demande = $_GET['id_demande'] ?? null;

if (!$id_contrat || !$id_demande) {
    die("Paramètres manquants");
}

// Traitement de la validation/rejet
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'valider') {
            // Mettre à jour le statut du contrat à validé (1)
            $db->updateContratStatus($id_contrat, 1);
            header('Location: listeContratValideur.php?success=1');
            exit;
        } elseif ($_POST['action'] === 'rejeter') {
            $motif = $_POST['motif_rejet'] ?? '';
            if (empty($motif)) {
                $error = "Le motif de rejet est obligatoire";
            } else {
                // Mettre à jour le statut du contrat à rejeté (-1) avec le motif
                $db->updateContratStatus($id_contrat, -1, $motif);
                header('Location: listeContratValideur.php?success=2');
                exit;
            }
        }
    }
}

// Récupérer les données du contrat
$contrat = $db->getContratComplet($id_contrat);
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تأكيد العقد</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .contract-preview {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .actions {
            margin: 20px;
            text-align: center;
        }
        .btn-valider {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .btn-rejeter {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .motif-rejet {
            margin: 20px;
            display: none;
        }
        .error {
            color: red;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>تأكيد العقد</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="contract-preview">
            <h2>معاينة العقد</h2>
            <iframe src="generatePDF.php?id_contrat=<?php echo $id_contrat; ?>&id_demande=<?php echo $id_demande; ?>" 
                    width="100%" height="600px" style="border: none;"></iframe>
        </div>

        <div class="actions">
            <form method="POST" id="validationForm">
                <button type="submit" name="action" value="valider" class="btn-valider">طباعة العقد</button>
                <button type="button" onclick="showRejetForm()" class="btn-rejeter">اعتراض</button>
                
                <div id="motifRejet" class="motif-rejet">
                    <textarea name="motif_rejet" rows="4" cols="50" placeholder="أدخل سبب الرفض"></textarea>
                    <br>
                    <button type="submit" name="action" value="rejeter" class="btn-rejeter">تأكيد الرفض</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejetForm() {
            document.getElementById('motifRejet').style.display = 'block';
        }
    </script>
</body>
</html> 