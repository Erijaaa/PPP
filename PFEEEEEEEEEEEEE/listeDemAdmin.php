<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once 'connect.php';

try {
    $connect = new ClsConnect();           
    $pdo = $connect->getConnection();        

    $sql = "SELECT * FROM T_demande";
    $stmt = $pdo->prepare($sql);
    //$stmt->execute(); 
    $dem = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $dem = []; 
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
        .actions a {
            margin: 0 5px;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            color: white;
        }
        .edit-btn {
            background-color: #f39c12;
        }
        .delete-btn {
            background-color: #e74c3c;
        }
        .edit-btn {
        background-color: #ffc107;
        border: none;
        color: white;
        padding: 5px 12px;
        cursor: pointer;
        border-radius: 5px;
        }

        .save-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 6px 12px;
        margin-right: 10px;
        border-radius: 5px;
        }

        .cancel-btn {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 5px;
        }

        .edit-user-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        }
        .edit-user-form .form-group {
        display: flex;
        flex-direction: column;
        flex: 1 1 200px;
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
            <h1> قائمة المطالب</h1>
        </div>
            <!-- Form -->
            <div class="form-container">
                <!-- Requests Management Section -->
                <div id="requests-content" class="content-section">
                    <h2>قائمة المطالب</h2>
                    <label for="post">  : اختر حسب </label>
                        <select>
                            <option value="">-- --</option>
                            <option type="date" value="date">التاريخ</option>
                            <option value="type_demande">نوع الطلب</option>
                            <option value="etat">حالة الطلب</option>
                        </select>
                    <table border="1" style="width: 100%; text-align: center;">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>تاريخ الطلب</th>
                                <th>رقم الوصل</th>
                                <th>نوع الطلب</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($dem)) { ?>
                                <?php foreach ($dem as $dem) { ?>
                                    <tr>
                                    <td><?php echo htmlspecialchars($dem['id_demande']); ?></td>
                                    <td><?php echo htmlspecialchars($dem['date_demande']); ?></td>
                                    <td><?php echo htmlspecialchars($dem['num_recu']); ?></td>
                                    <td><?php echo htmlspecialchars($dem['type_demande']); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4">لا توجد مطالب متاحة</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>    
        </div>
    </div>    
    <script src="script/script.js"></script>
</body>
</html>