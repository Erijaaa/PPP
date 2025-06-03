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
    UNION
    SELECT 
        nom_prenom_user AS nom,
        '' AS prenom,
        cin_user AS identification_number,
        password_user AS password,
        post,
        email_user AS email,
        adresse_user AS adresse,
        telephone_user AS telephone,
        'user' AS role
    FROM users
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
$nouv_user = $connect->ajouterUser($pdo);
if ($nouv_user) {
    $message = $nouv_user;
}
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

        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            <form method="POST" action="">
                <form id="agentForm">
                <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                    <label for="post">عدد الصلاحية</label>
                    <select name="post" id="post" required>
                        <option value="">-- اختر --</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>

                    <div class="form-group">
                        <label>الاسم و اللقب</label>
                        <input type="text" name="nom_prenom_user" required>
                    </div>
                    <div class="form-group">
                        <label>رقم التعريف</label>
                        <input type="text" name="cin_user" required>
                    </div>
                    <div class="form-group">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email_user" required>
                    </div>


                    <div class="form-group">
                        <label> العنوان </label>
                        <input type="text" name="adresse_user" required>
                    </div>


                    <div class="form-group">
                        <label> رقم الهاتف</label>
                        <input type="text" name="telephone_user" required>
                    </div>


                    <div class="form-group">
                        <label> تاريخ الولادة</label>
                        <input type="date" name="date_naissance_user" required>
                    </div>

                    <div class="form-group">
                        <label>كلمة المرور</label>
                        <input type="text" name="password_user" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="submit" class="btn btn-primary">إضافة</button>
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
                                        <a class="delete-btn" href="?delete=<?= urlencode($user['email'] ?? '') ?>&role=<?= urlencode($user['role'] ?? '') ?>" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">حذف</a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Ligne masquée contenant le formulaire -->
                            <tr class="edit-form-row" style="display: none;">
                                <td colspan="5">
                                    <form class="edit-user-form" method="POST" action="update_user.php">
                                        <input type="hidden" name="old_email" value="<?= htmlspecialchars($user['email']) ?>">
                                        <input type="hidden" name="role" value="<?= htmlspecialchars($user['role']) ?>">
                                        <div class="form-group">
                                            <label>الاسم و اللقب</label>
                                            <input type="text" name="agentName" required>
                                        </div>
                                        <div class="form-group">
                                            <label>رقم التعريف</label>
                                            <input type="text" name="cin" required>
                                        </div>
                                        <div class="form-group">
                                            <label>البريد الإلكتروني</label>
                                            <input type="email" name="agentEmail" required>
                                        </div>
                                        <div class="form-group">
                                            <label>العنوان</label>
                                            <input type="text" name="agentAdresse" required>
                                        </div>
                                        <div class="form-group">
                                            <label>رقم الهاتف</label>
                                            <input type="text" name="agentTele" required>
                                        </div>
                                        <div class="form-group">
                                            <label>تاريخ الولادة</label>
                                            <input type="date" name="agentNaissance" required>
                                        </div>
                                        <div class="form-group">
                                            <label>كلمة المرور</label>
                                            <input type="password" name="password" placeholder="اتركه فارغًا إذا لم تكن تريد تغييره">
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="save-btn">حفظ</button>
                                            <button type="button" class="cancel-btn" onclick="hideEditForm(this)">إلغاء</button>
                                        </div>
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
    <script>
    function showEditForm(button, userData) {
        // Masquer tous les autres formulaires
        document.querySelectorAll('.edit-form-row').forEach(row => {
            row.style.display = 'none';
        });

        // Afficher le formulaire de la ligne actuelle
        const row = button.closest('tr');
        const formRow = row.nextElementSibling;
        formRow.style.display = 'table-row';

        // Pré-remplir le formulaire avec les données de l'utilisateur
        const form = formRow.querySelector('form');
        const [nom, prenom] = (userData.nom + ' ' + userData.prenom).split(' ');
        form.agentName.value = nom + ' ' + prenom;
        form.cin.value = userData.identification_number || '';
        form.agentEmail.value = userData.email || '';
        form.agentAdresse.value = userData.adresse || '';
        form.agentTele.value = userData.telephone || '';
        form.agentNaissance.value = userData.date_naissance || '';
        form.password.value = ''; // Ne pas pré-remplir le mot de passe pour des raisons de sécurité
    }

    function hideEditForm(button) {
        const formRow = button.closest('.edit-form-row');
        formRow.style.display = 'none';
    }

    function clearForm() {
        document.getElementById('agentForm').reset();
    }

    // Function to refresh the page after successful form submission
    document.querySelector('form[method="POST"]').addEventListener('submit', function(e) {
        // Form will submit normally, and page will refresh to show new user
        // The PHP code above will handle displaying the success/error message
    });
    </script>
</body>
</html>