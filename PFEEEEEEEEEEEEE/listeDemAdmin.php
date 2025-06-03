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

// Récupérer le critère de tri
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : '';

try {
    // Récupération des demandes avec tri
    $query = 'SELECT * FROM "T_demande"';
    
    // Ajouter la clause ORDER BY selon le critère
    switch($sortBy) {
        case 'date':
            $query .= ' ORDER BY date_demande DESC';
            break;
        case 'type':
            $query .= ' ORDER BY type_demande DESC';
            break;
        case 'status':
            $query .= ' ORDER BY CASE 
                        WHEN etat_demande = 1 THEN 1 
                        WHEN etat_demande = 0 THEN 1 
                        WHEN etat_demande = -1 THEN -1 
                        ELSE 1 END';
            break;
    }
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $dem = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    die();
}

// Fonction pour formater la date
function formatDate($date) {
    return date('Y-m-d', strtotime($date));
}


// Fonction pour obtenir la classe CSS de l'état
function getStatusClass($etat) {
    switch($etat) {
        case 1:
            return 'status-approved';
        case -1:
            return 'status-rejected';
        default:
            return 'status-pending';
    }
}

// Fonction pour obtenir le texte de l'état
function getStatusText($etat) {
    switch($etat) {
        case 1:
            return 'مقبول';
        case -1:
            return 'مرفوض';
        default:
            return 'في الانتظار';
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
        .btn-modifier {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-modifier:hover {
            background-color: #2980b9;
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
                <option value="date" <?php echo $sortBy == 'date' ? 'selected' : ''; ?>>التاريخ</option>
                <option value="type_demande" <?php echo $sortBy == 'type_demande' ? 'selected' : ''; ?>>نوع الطلب</option>
                <option value="status" <?php echo $sortBy == 'status' ? 'selected' : ''; ?>>الحالة</option>
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
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dem)) : ?>
                    <?php foreach ($dem as $demande) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($demande['id_demande'] ?? ''); ?></td>
                            <td><?php echo isset($demande['date_demande']) ? formatDate($demande['date_demande']) : ''; ?></td>
                            <td><?php echo htmlspecialchars($demande['num_recu'] ?? ''); ?></td>
                            <td><?php echo isset($demande['type_demande']) ? htmlspecialchars($demande['type_demande']) : ''; ?></td>
                            <td class="<?php echo getStatusClass($demande['etat_demande'] ?? 0); ?>">
                                <?php echo getStatusText($demande['etat_demande'] ?? 0); ?>
                            </td>
                            <td>
                                <button onclick="modifierDemande('<?php echo htmlspecialchars($demande['id_demande']); ?>', '<?php echo htmlspecialchars($demande['num_recu']); ?>')" class="btn-modifier">تعديل</button>
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
    window.location.href = 'listeDemAdmin.php?sort=' + value;
}

function modifierDemande(id_demande, num_recu) {
    // Rediriger vers la page de traitement avec les paramètres nécessaires
    window.location.href = 'Traitement.php?id_demande=' + id_demande + '&num_recu=' + num_recu;
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