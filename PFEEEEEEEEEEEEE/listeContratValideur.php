<?php
session_start();
require_once("connect.php");

// Vérifier si l'utilisateur est connecté et est un valideur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'valideur') {
    header('Location: index.php');
    exit;
}

$db = new ClsConnect();
$pdo = $db->getConnection();

// Récupérer les filtres
$date_debut = $_GET['date_debut'] ?? '';
$date_fin = $_GET['date_fin'] ?? '';
$etat = $_GET['etat'] ?? '0'; // Par défaut, afficher les contrats en attente

// Construire la requête SQL avec les filtres
$sql = "SELECT c.*, d.num_recu, d.date_demande, u.username as redacteur_nom 
        FROM contrats c 
        JOIN demandes d ON c.id_demande = d.id_demande 
        JOIN users u ON c.id_redacteur = u.id 
        WHERE d.etat_demande = 1";

if ($etat !== '') {
    $sql .= " AND c.etat_contrat = :etat";
}
if ($date_debut) {
    $sql .= " AND d.date_demande >= :date_debut";
}
if ($date_fin) {
    $sql .= " AND d.date_demande <= :date_fin";
}

$sql .= " ORDER BY d.date_demande DESC";

$stmt = $pdo->prepare($sql);

if ($etat !== '') {
    $stmt->bindParam(':etat', $etat);
}
if ($date_debut) {
    $stmt->bindParam(':date_debut', $date_debut);
}
if ($date_fin) {
    $stmt->bindParam(':date_fin', $date_fin);
}

$stmt->execute();
$contrats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة العقود للتحقق - الموثق</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }

        .logo img {
            height: 60px;
        }

        .contracts-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .contracts-table th,
        .contracts-table td {
            padding: 12px 15px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }

        .contracts-table th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: bold;
        }

        .contracts-table tr:hover {
            background-color: #f5f5f5;
        }

        .validate-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .validate-btn:hover {
            background-color: #218838;
        }

        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }

        .no-contracts {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-size: 18px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .contracts-table tr {
            animation: fadeIn 0.3s ease-out forwards;
        }

        .filters {
            margin: 20px;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
        .filters form {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn-action {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }
        .btn-valider {
            background-color: #4CAF50;
        }
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>قائمة العقود في انتظار التحقق</h1>
            <div class="logo">
                <img src="media/logo.png" alt="ONPFF Logo">
            </div>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <?php 
                if ($_GET['success'] == 1) {
                    echo "تم تأكيد العقد بنجاح";
                } elseif ($_GET['success'] == 2) {
                    echo "تم رفض العقد بنجاح";
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="filters">
            <form method="GET">
                <div>
                    <label for="date_debut">من تاريخ:</label>
                    <input type="date" id="date_debut" name="date_debut" value="<?php echo $date_debut; ?>">
                </div>
                <div>
                    <label for="date_fin">إلى تاريخ:</label>
                    <input type="date" id="date_fin" name="date_fin" value="<?php echo $date_fin; ?>">
                </div>
                <div>
                    <label for="etat">الحالة:</label>
                    <select id="etat" name="etat">
                        <option value="0" <?php echo $etat === '0' ? 'selected' : ''; ?>>في انتظار التأكيد</option>
                        <option value="1" <?php echo $etat === '1' ? 'selected' : ''; ?>>مؤكد</option>
                        <option value="-1" <?php echo $etat === '-1' ? 'selected' : ''; ?>>مرفوض</option>
                    </select>
                </div>
                <button type="submit">تصفية</button>
            </form>
        </div>

        <?php if (empty($contrats)): ?>
            <div class="no-contracts">
                لا توجد عقود في انتظار التحقق
            </div>
        <?php else: ?>
            <table class="contracts-table">
                <thead>
                    <tr>
                        <th>رقم العقد</th>
                        <th>رقم الوصل</th>
                        <th>تاريخ الطلب</th>
                        <th>المحرر</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contrats as $contrat): ?>
                        <tr>
                            <td><?php echo $contrat['id_contrat']; ?></td>
                            <td><?php echo $contrat['num_recu']; ?></td>
                            <td><?php echo $contrat['date_demande']; ?></td>
                            <td><?php echo $contrat['redacteur_nom']; ?></td>
                            <td>
                                <?php
                                switch ($contrat['etat_contrat']) {
                                    case 0:
                                        echo "في انتظار التأكيد";
                                        break;
                                    case 1:
                                        echo "مؤكد";
                                        break;
                                    case -1:
                                        echo "مرفوض";
                                        break;
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($contrat['etat_contrat'] == 0): ?>
                                    <a href="validerContrat.php?id_contrat=<?php echo $contrat['id_contrat']; ?>&id_demande=<?php echo $contrat['id_demande']; ?>" 
                                       class="btn-action btn-valider">تأكيد</a>
                                <?php else: ?>
                                    <a href="generatePDF.php?id_contrat=<?php echo $contrat['id_contrat']; ?>&id_demande=<?php echo $contrat['id_demande']; ?>" 
                                       target="_blank" class="btn-action">معاينة</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        // Animation pour les nouvelles lignes
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.contracts-table tr');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html> 