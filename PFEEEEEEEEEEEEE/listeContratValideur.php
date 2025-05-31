<?php
require_once 'connect.php';

$connect = new ClsConnect();
$pdo = $connect->getConnection();

// Récupérer les contrats à valider
try {
    $contrats = $connect->getContratsForValideur();
} catch (Exception $e) {
    error_log("Erreur dans listeContratValideur : " . $e->getMessage());
    $contrats = [];
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة العقود للتحقق - الموثق</title>
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

        <?php if (empty($contrats)): ?>
            <div class="no-contracts">
                لا توجد عقود في انتظار التحقق
            </div>
        <?php else: ?>
            <table class="contracts-table">
                <thead>
                    <tr>
                        <th>رقم العقد</th>
                        <th>رقم مطلب التحرير</th>
                        <th>تاريخ المطلب</th>
                        <th>رقم الوصل</th>
                        <th>تاريخ العقد</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contrats as $contrat): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contrat['id_contrat']); ?></td>
                            <td><?php echo htmlspecialchars($contrat['id_demande']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($contrat['date_demande']))); ?></td>
                            <td><?php echo htmlspecialchars($contrat['num_recu']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($contrat['date_contrat']))); ?></td>
                            <td class="status-pending">في انتظار التحقق</td>
                            <td>
                                <a href="valideurContrat.php?id_demande=<?php echo urlencode($contrat['id_demande']); ?>&id_contrat=<?php echo urlencode($contrat['id_contrat']); ?>" 
                                   class="validate-btn">
                                    تحقق من العقد
                                </a>
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