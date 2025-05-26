<?php
require_once 'connect.php';
$connect = new ClsConnect();
$pdo = $connect->getConnection();

session_start();
// Vérification simple de session admin, adapte selon ta session
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Récupérer les paramètres
$id = $_GET['id'] ?? null;
$role = $_GET['role'] ?? null;

if (!$id || !in_array($role, ['redacteur', 'valideur'])) {
    echo "Paramètres invalides.";
    exit;
}

// Déterminer la table et les champs selon le rôle
if ($role === 'redacteur') {
    $table = 'redacteur';
    $idField = 'id_redacteur';
    $nomField = 'nom_redacteur';
    $prenomField = 'prenom_redacteur';
    $cinField = 'cin_redacteur';
} else {
    $table = 'valideur';
    $idField = 'id_valideur';
    $nomField = 'nom_valideur';
    $prenomField = 'prenom_valideur';
    $cinField = 'cin_valideur';
}

// Récupérer les données utilisateur
$stmt = $pdo->prepare("SELECT * FROM $table WHERE $idField = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Utilisateur introuvable.";
    exit;
}

// Traitement du formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $cin = $_POST['cin'] ?? '';
    $email = $_POST['email'] ?? '';
    $post = $_POST['post'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $password = $_POST['password'] ?? '';

    // Update la base
    $updateSql = "UPDATE $table SET
        $nomField = ?,
        $prenomField = ?,
        $cinField = ?,
        email = ?,
        post = ?,
        adresse = ?,
        telephone = ?,
        password = ?
        WHERE $idField = ?";

    $stmt = $pdo->prepare($updateSql);
    $stmt->execute([$nom, $prenom, $cin, $email, $post, $adresse, $telephone, $password, $id]);

    header("Location: pageAdmin.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title>تعديل المستخدم</title>
</head>
<body>
    <h2>تعديل المستخدم (<?= htmlspecialchars($role) ?>)</h2>
    <form method="post">
        <label>الاسم:</label><br>
        <input type="text" name="nom" value="<?= htmlspecialchars($user[$nomField]) ?>" required><br><br>

        <label>اللقب:</label><br>
        <input type="text" name="prenom" value="<?= htmlspecialchars($user[$prenomField]) ?>" required><br><br>

        <label>رقم التعريف:</label><br>
        <input type="text" name="cin" value="<?= htmlspecialchars($user[$cinField]) ?>" required><br><br>

        <label>البريد الإلكتروني:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

        <label>الوظيفة:</label><br>
        <input type="text" name="post" value="<?= htmlspecialchars($user['post']) ?>"><br><br>

        <label>العنوان:</label><br>
        <input type="text" name="adresse" value="<?= htmlspecialchars($user['adresse']) ?>"><br><br>

        <label>الهاتف:</label><br>
        <input type="text" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>"><br><br>

        <label>كلمة المرور:</label><br>
        <input type="text" name="password" value="<?= htmlspecialchars($user['password']) ?>"><br><br>

        <button type="submit">حفظ التعديلات</button>
        <a href="pageAdmin.php">إلغاء</a>
    </form>
</body>
</html>
