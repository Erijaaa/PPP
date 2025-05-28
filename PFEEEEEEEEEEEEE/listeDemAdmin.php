<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'connect.php';
$connect = new ClsConnect();
$pdo = $connect->getConnection();

// Debug de la connexion
try {
    $test = $pdo->query('SELECT 1');
    error_log("Connexion à la base de données réussie");
} catch (PDOException $e) {
    error_log("Erreur de connexion : " . $e->getMessage());
}

// Test de la table T_demande
try {
    $test = $pdo->query('SELECT COUNT(*) FROM "T_demande"');
    $count = $test->fetchColumn();
    error_log("Nombre de demandes dans la table : " . $count);
} catch (PDOException $e) {
    error_log("Erreur lors de l'accès à la table T_demande : " . $e->getMessage());
}

try {
    // Récupération des demandes
    $dem = $connect->getAllDemandes();
    
    // Debug - Afficher les données récupérées
    echo "<!-- Debug: Nombre de demandes = " . count($dem) . " -->";
    echo "<!-- Debug: Données = " . print_r($dem, true) . " -->";
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    die();
}

// Fonction pour formater la date
function formatDate($date) {
    return date('Y-m-d', strtotime($date));
}

// Fonction pour traduire le type de demande
function getTypeDemande($type) {
    switch($type) {
        case 1:
            return "عقد بيع";
        case 2:
            return "عقد كراء";
        default:
            return "غير محدد";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة المطالب</title>
    <link rel="stylesheet" href="css/pageAdmin.css">
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
            direction: rtl;
        }
        th, td {
            border: 1px solid #666;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
        }
        .status-pending {
            color: #f39c12;
        }
        .status-approved {
            color: #27ae60;
        }
        .status-rejected {
            color: #e74c3c;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>لوحة التحكم</h2>
        <ul class="sidebar-menu">
            <li><a href="pageAdmin.php" class="menu-item" data-section="agents">👥 إدارة الوكلاء</a></li>
            <li><a href="listeDemAdmin.php" class="menu-item active" data-section="requests">📋 قائمة المطالب</a></li>
            <li><a href="#" class="menu-item" data-section="contracts">📄 قائمة العقود</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>قائمة المطالب</h1>
        </div>
        
        <!-- Filtres -->
        <div class="filters" style="margin: 20px;">
            <label for="filter">قائمة المطالب  حسب:</label>
            <select id="filter" onchange="filterDemandes(this.value)">
                <option value="">الكل</option>
                <option value="date">التاريخ</option>
                <option value="type">نوع الطلب</option>
                <option value="status">الحالة</option>
            </select>
        </div>

        <!-- Table des demandes -->
        <table>
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>تاريخ الطلب</th>
                    <th>رقم الوصل</th>
                    <th>نوع الطلب</th>
                    <th>الحالة</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dem)) : ?>
                    <?php foreach ($dem as $demande) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($demande['id_demande'] ?? ''); ?></td>
                            <td><?php echo isset($demande['date_demande']) ? date('Y-m-d', strtotime($demande['date_demande'])) : ''; ?></td>
                            <td><?php echo htmlspecialchars($demande['num_recu'] ?? ''); ?></td>
                            <td><?p($demande['type_demande'] ?? 0); ?></td>
                            <td class="<?php 
                                $etat = isset($demande['etat_demande']) ? (int)$demande['etat_demande'] : 0;
                                echo 'status-' . ($etat == 1 ? 'approved' : ($etat == 2 ? 'rejected' : 'pending')); 
                            ?>">
                                <?php 
                                    $etat = isset($demande['etat_demande']) ? (int)$demande['etat_demande'] : 0;
                                    echo $etat == 1 ? 'مقبول' : 
                                         ($etat == 2 ? 'مرفوض' : 'في الانتظار');
                                ?>
                            </td>
                            <td>
                                <a href="Traitement.php?id_demande=<?php echo $demande['id_demande']; ?>&num_recu=<?php echo $demande['num_recu']; ?>" 
                                   class="edit-btn">معالجة</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="no-data">لا توجد مطالب متاحة</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterDemandes(value) {
    // Implémentez ici la logique de filtrage
    console.log("Filtering by: " + value);
}

// Afficher un message si aucune donnée n'est trouvée
window.onload = function() {
    <?php if (empty($dem)) : ?>
    console.log("Aucune demande trouvée dans la base de données");
    <?php endif; ?>
}
</script>

</body>
</html>