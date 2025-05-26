<?php
// Démarrer la session
session_start();

// Vérifier si le code a été soumis
if (isset($_POST['code'])) {
    $code_soumis = $_POST['code'];
    
    // Vérifier si le code correspond à celui stocké en session
    if (isset($_SESSION['code_verification']) && $code_soumis == $_SESSION['code_verification']) {
        // Code correct, marquer l'utilisateur comme vérifié
        $_SESSION['verified'] = true;
        // Rediriger vers la page d'accueil ou le tableau de bord
        header("Location: pageRedacteur.php");
        exit();
    } else {
        $error_message = "Code de vérification incorrect. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Vérification du code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .verification-form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
        }
        .form-title {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-verify {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="verification-form">
        <h1 class="form-title">تحقق من الرمز</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <p>تم إرسال رمز التحقق إلى بريدك الإلكتروني. يرجى إدخاله أدناه:</p>
        
        <form action="verifier_code.php" method="post">
            <div class="form-group">
                <label class="form-label">رمز التحقق</label>
                <input type="text" name="code" class="form-input" required>
            </div>
            
            <button type="submit" class="btn-verify">تحقق</button>
        </form>
    </div>
</body>
</html>