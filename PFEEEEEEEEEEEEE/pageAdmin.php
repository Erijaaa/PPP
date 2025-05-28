<?php
require_once 'connect.php';

$connect = new ClsConnect();             // Création de l'objet ClsConnect
$pdo = $connect->getConnection();        // Récupération de la connexion PDO

// Exécution de la requête pour obtenir tous les utilisateurs
$sql = "
    SELECT 
        nom_redacteur AS nom,
        prenom_redacteur AS prenom,
        cin_redacteur AS identification_number,
        password,
        post,
        email,
        adresse,
        telephone,
        'redacteur' AS role
    FROM redacteur
    UNION
    SELECT 
        nom_valideur AS nom,
        prenom_valideur AS prenom,
        cin_valideur AS identification_number,
        password,
        post,
        email AS email,
        adresse,
        telephone,
        'valideur' AS role
    FROM valideur
";


$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
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
            <li><a href="pageAdmin.php" class="menu-item active" data-section="agents">👥 إدارة الوكلاء</a></li>
            <li><a href="listeDemAdmin.php" class="menu-item" data-section="requests">📋 قائمة المطالب</a></li>
            <li><a href="#" class="menu-item" data-section="contracts">📄 قائمة العقود</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <div class="header">
            <h1>لوحة التحكم</h1>
        </div>

        <!-- Agents Management Section -->
        <div id="agents-content" class="content-section active">
            <h2>إدارة الوكلاء</h2>

            <!-- Form -->
            <div class="form-container">
                <form id="agentForm">
                    <div class="form-group" style="display: flex; align-items: center">
                        <label for="post">عدد الصلاحية</label>
                        <select>
                            <option value="">-- --</option>
                            <option value="un">1</option>
                            <option value="deux">2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="agentName">الاسم و اللقب</label>
                        <input type="text" id="agentName" name="agentName" required>
                    </div>
                    <div class="form-group">
                        <label for="cin">رقم التعريف</label>
                        <input type="text" id="cin" name="cin" required>
                    </div>
                    <div class="form-group">
                        <label for="agentEmail">البريد الإلكتروني</label>
                        <input type="email" id="agentEmail" name="agentEmail" required>
                    </div>


                    <div class="form-group">
                        <label for="agentAdresse"> العنوان </label>
                        <input type="text" id="agentAdresse" name="agentAdresse" required>
                    </div>


                    <div class="form-group">
                        <label for="agentTele"> رقم الهاتف</label>
                        <input type="text" id="agentTele" name="agentTele" required>
                    </div>


                    <div class="form-group">
                        <label for="agentNaissance"> تاريخ الولادة</label>
                        <input type="date" id="agentNaissance" name="agentNaissance" required>
                    </div>

                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input type="text" id="password" name="password" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">إضافة</button>
                        <button type="button" class="btn btn-secondary" onclick="clearForm()">إلغاء</button>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <h2 style="text-align:center;">قائمة المستخدمين</h2>
            <?php if (!empty($users)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>الاسم و اللقب</th>
                            <th>رقم التعريف</th>
                            <th>البريد الإلكتروني</th>
                            <th>الوظيفة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?></strong></td>
                                <td><?= htmlspecialchars($user['identification_number'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td class="actions">
                                    <button class="edit-btn" onclick="showEditForm(this, <?= htmlspecialchars(json_encode($user)) ?>)">تعديل</button>
                                    <?php if (($user['id'] ?? null) != ($_SESSION['user_id'] ?? null)): ?>
                                        <a class="delete-btn" href="?delete=<?= urlencode($user['id'] ?? '') ?>" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">حذف</a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Ligne masquée contenant le formulaire -->
                            <tr class="edit-form-row" style="display: none;">
                                <td colspan="5">
                                    <form class="edit-user-form">
                                        <div class="form-group">
                                            <label>الاسم و اللقب</label>
                                            <input type="text" name="agentName">
                                        </div>
                                        <div class="form-group">
                                            <label>رقم التعريف</label>
                                            <input type="text" name="cin">
                                        </div>
                                        <div class="form-group">
                                            <label>البريد الإلكتروني</label>
                                            <input type="email" name="agentEmail">
                                        </div>
                                        <div class="form-group">
                                            <label>العنوان</label>
                                            <input type="text" name="agentAdresse">
                                        </div>
                                        <div class="form-group">
                                            <label>رقم الهاتف</label>
                                            <input type="number" name="agentTele">
                                        </div>
                                        <div class="form-group">
                                            <label>تاريخ الولادة</label>
                                            <input type="date" name="agentNaissance">
                                        </div>
                                        <div class="form-group">
                                            <label>كلمة المرور</label>
                                            <input type="text" name="password">
                                        </div>
                                        <button type="submit" class="save-btn">تم الحفظ</button>
                                        <button type="button" class="cancel-btn" onclick="hideEditForm(this)">إلغاء</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>


                </table>
            <?php else: ?>
                <p style="text-align:center;">لا يوجد مستخدمون حاليا.</p>
            <?php endif; ?>
        </div>
    </div>
        <div>
            <!-- Requests Management Section -->
            <div id="requests-content" class="content-section">
                <h2>قائمة المطالب</h2>
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
                        <?php
                        $stmt = $pdo->query("SELECT * FROM T_demandes ");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id_demande']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date_demande']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['num_recu']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['type_demande']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>


            <!-- Contracts Management Section -->
            <div id="contracts-content" class="content-section">
                <h2>قائمة العقود</h2>
                <p>هنا ستظهر قائمة بجميع العقود المسجلة في النظام...</p>
                <!-- Add your contracts content here -->
            </div>
        </div>
    </div>
    <script src="script/script.js"></script>
</body>
</html>