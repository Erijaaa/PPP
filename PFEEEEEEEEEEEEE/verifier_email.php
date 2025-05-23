<?php
// ini retourne les erreur lié au code php8 
require __DIR__ . '/PHPMailer-6.8.1/src/Exception.php';
require __DIR__ . '/PHPMailer-6.8.1/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-6.8.1/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Connexion à la base de données PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "pfe_bdd";
$username = "postgres";
$password_db = "erij";

// Récupérer les données du formulaire



$cin_admin = $_POST['cin_admin'] ?? '';
$nom = $_POST['nom_admin'] ?? ''; // Champ pas utilisé ici 
$password = $_POST['password'] ?? '';
$profil = $_POST['profil'] ?? '';

//debug suivre le code step by step 

// Vérifier les champs
if (empty($cin_admin) || empty($password)) {
    echo "Veuillez remplir tous les champs obligatoires.";
    exit();
}

//refaire le code est accéder à la classe connect


try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $conn = new PDO($dsn, $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête utilisateur
    $stmt = $conn->prepare("SELECT * FROM public.admin WHERE cin_admin = :cin_admin AND password = :password");
    $stmt->bindParam(':cin_admin', $cin_admin);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        print_r($user);
        // Générer un code de vérification
        $code_verification = mt_rand(100000, 999999);

        // Stocker les données dans la session
        $_SESSION['code_verification'] = $code_verification;
        $_SESSION['user_id'] = $user['nom_admin'];
        $_SESSION['userAuth'] = $user;


       // $_SESSION['user_email'] = $user['email'];

        // Envoi de l'e-mail avec PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'erijedridi1@gmail.com';
            $mail->Password   = 'ibsw asej bfbd bmyv'; // ⚠️ À sécuriser avec un mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom('erijedridi1@gmail.com', 'Vérifier');
            $mail->addAddress('erijedridi1@gmail.com');//$user['email']
            $mail->isHTML(true);
            $mail->Subject = 'Code de vérification';
            $mail->Body    = 'Votre code de vérification est : <b>' . $code_verification . '</b>';
            $mail->AltBody = 'Votre code de vérification est : ' . $code_verification;

            $mail->send();

            header("Location: verifier_code.php");
            exit();
        } catch (Exception $e) {
            echo "Erreur d'envoi de l'e-mail : {$mail->ErrorInfo}";
        }
    } else {
        echo "Numéro d'identification ou mot de passe incorrect.";
    }
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}

$conn = null;
?>