<?php
require __DIR__ . '/PHPMailer-6.8.1/src/Exception.php';
require __DIR__ . '/PHPMailer-6.8.1/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-6.8.1/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$host = "localhost";
$port = "5432";
$dbname = "pfe_bdd";
$username = "postgres";
$password_db = "pfe";

$cin_admin = $_POST['cin_admin'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($cin_admin) || empty($password)) {
    echo "Veuillez remplir tous les champs obligatoires.";
    exit();
}

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $conn = new PDO($dsn, $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM public.admin WHERE cin_admin = :cin_admin AND password = :password");
    $stmt->bindParam(':cin_admin', $cin_admin);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!isset($user['email']) || empty($user['email'])) {
            echo "Erreur : Aucune adresse e-mail trouvée pour cet utilisateur.";
            exit();
        }

        $code_verification = mt_rand(100000, 999999);

        $_SESSION['code_verification'] = $code_verification;
        $_SESSION['user_id'] = $user['nom_admin'];
        $_SESSION['userAuth'] = $user;

        // Envoi de l'e-mail avec PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'erijedridi1@gmail.com';
            $mail->Password = 'drdn ilsm mvac miam';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('erijedridi1@gmail.com', 'Vérifier votre connexion');
            $mail->addAddress($user['email']);
            $mail->isHTML(true);
            $mail->Subject = 'Code de vérification';
            $mail->Body = 'Votre code de vérification est : <b>' . $code_verification . '</b>';
            $mail->AltBody = 'Votre code de vérification est : ' . $code_verification;

            if ($mail->send()) {
                $_SESSION['code'] = $code_verification;
                $_SESSION['email'] = $user['email'];
                header("Location: verifier_code.php");
                exit();
            } else {
                echo "Erreur lors de l'envoi du mail.";
            }
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'e-mail : " . $mail->ErrorInfo;
        }
    } else {
        echo "CIN ou mot de passe invalide.";
    }
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}


$conn = null;
?>