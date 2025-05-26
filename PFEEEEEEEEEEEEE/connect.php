<?php

session_start();


class ClsConnect {
    private $conn;

    public function __construct() {
        $host = "localhost";
        $port = "5432";
        $dbname = "pfe_bdd";
        $username = "postgres";
        $password = "pfe";

        try {
            $this->conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn; 
    }



    public function verifierUtilisateur($cin, $password){
        if (empty($cin) || empty($password)) {
            echo "Veuillez remplir tous les champs obligatoires.";
            return false;
        }

        $stmt = $this->conn->prepare("SELECT * FROM admin WHERE cin = :cin_admin AND password = :password");
        $stmt->bindParam(':cin_admin', $cin);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    

    public function envoyerCodeVerification($user) {
        $code_verification = mt_rand(100000, 999999);

        $_SESSION['code_verification'] = $code_verification;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'erijedridi1@gmail.com';
            //$mail->Password   = 'ibswasejbfbdbmyv';
            $mail->Password = 'gaom vvaf fpqa tpeh';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('erijedridi1@gmail.com', 'Rédacteur');
            $mail->addAddress($user['email']);

            $mail->isHTML(true);
            $mail->Subject = 'Code de vérification';
            $mail->Body    = 'Votre code de vérification est : <b>' . $code_verification . '</b>';
            $mail->AltBody = 'Votre code de vérification est : ' . $code_verification;

            $mail->send();

            header("Location: verifier_code.php");
            exit();
        } catch (Exception $e) {
            echo "Erreur d'envoi d'email : {$mail->ErrorInfo}";
        }
    }

    public function traitResult($type_demande) {
        $sql = "SELECT * FROM public.\"T_demande\" WHERE type_demande = :type_demande";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':type_demande', $type_demande, PDO::PARAM_INT);
        $stmt->execute();
        
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
    }



    public function traitContrat($etat_demande, $etat_contrat) {
        try {
            $sql = "SELECT c.id_demande, c.etat_contrat, d.etat_demande 
                    FROM public.\"contrat\" c
                    INNER JOIN public.\"T_demande\" d ON c.id_demande = d.id_demande
                    WHERE d.etat_demande = :etat_demande AND c.etat_contrat = :etat_contrat";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':etat_demande', $etat_demande, PDO::PARAM_INT);
            $stmt->bindParam(':etat_contrat', $etat_contrat, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans traitContrat : " . $e->getMessage());
            return [];
        }
    }




    public function getDemandeById($id_demande) {
        $sql = "SELECT * FROM public.\"T_demande\" WHERE id_demande = :id_demande";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_demande', $id_demande, PDO::PARAM_INT);
        $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
    }


    public function getidcontract() {
        $sql = "SELECT nextval('public.next-id-contract')";
        //return $sql;
        $stmt = $this->conn->prepare($sql);
        //$stmt->execute();
        if ($stmt->rowCount() > 0) {    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }


   
    
    function getPiecesJointesByDemande($id_demande) {
        global $conn;
        
        $id_demande = $id_demande;
        
        $sql = "SELECT * FROM pieces_jointes WHERE id_demande =".$id_demande;
        $result =$this->conn->query($sql);
        //echo $result;
        $result->execute();
        return $result;
        exit;

        if ($result->rowCount() > 0) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }



    public function getAgent($id_demande) {
        $sql = "SELECT * FROM agent WHERE id_demande = :id";
        $stmt = $this->conn->prepare($sql); 
        $stmt->bindParam(':id', $id_demande, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
  
    

    public function getDeposant($id_demande) {
        $sql = "SELECT * FROM deposant WHERE id_demande = :id";
        $stmt = $this->conn->prepare($sql); 
        $stmt->bindParam(':id', $id_demande, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }


    public function getSubject($id_demande) {
        $sql = "SELECT * FROM contrat WHERE id_demande = :id";
        $stmt = $this->conn->prepare($sql); 
        $stmt->bindParam(':id', $id_demande, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }



    function getAllDemandes() {
        $sql = "SELECT * FROM T_demande";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $demandes;
    }
    




    public function getAgents() {
        $agents = [];
        try {
            $stmtRedacteur = $this->conn->query("SELECT name, cin, email, password FROM redacteur");
            $redacteurs = $stmtRedacteur->fetchAll();
            foreach ($redacteurs as $agent) {
                $agent['role'] = 'redacteur';
                $agents[] = $agent;
            }

            $stmtValideur = $this->conn->query("SELECT name, cin, email, password FROM valideur");
            $valideurs = $stmtValideur->fetchAll();
            foreach ($valideurs as $agent) {
                $agent['role'] = 'valideur';
                $agents[] = $agent;
            }

            return json_encode($agents);
        } catch (PDOException $e) {
            return json_encode(['success' => false, 'message' => 'Erreur lors de la récupération : ' . $e->getMessage()]);
        }
    }

    public function deleteAgent($role, $email) {
        if (!in_array($role, ['redacteur', 'valideur'])) {
            return json_encode(['success' => false, 'message' => 'Rôle invalide.']);
        }

        $table = $role === 'redacteur' ? 'redacteur' : 'valideur';
        $sql = "DELETE FROM $table WHERE email = :email";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return json_encode(['success' => false, 'message' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }
    public function updateAgent($role, $oldEmail, $name, $cin, $email, $password) {
        if (!in_array($role, ['redacteur', 'valideur'])) {
            return json_encode(['success' => false, 'message' => 'Rôle invalide.']);
        }
    
        $table = $role === 'redacteur' ? 'redacteur' : 'valideur';
        $sql = "UPDATE $table SET name = :name, cin = :cin, email = :email, password = :password WHERE email = :oldEmail";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':cin', $cin);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':oldEmail', $oldEmail);
            $stmt->execute();
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            return json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
        if ($action === 'update_agent') {
            $role = isset($_POST['role']) ? $_POST['role'] : '';
            $oldEmail = isset($_POST['oldEmail']) ? $_POST['oldEmail'] : '';
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $cin = isset($_POST['cin']) ? $_POST['cin'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            echo $connect->updateAgent($role, $oldEmail, $name, $cin, $email, $password);
        }
    }

}


    /**
     * Démarre la session et stocke les infos de l’utilisateur.
     */
    /*public function demarrerSessionUtilisateur(array $utilisateur): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (! isset($_SESSION['userAuth'])) {
            $_SESSION['userAuth'] = [
                'prenom_admin' => $utilisateur['prenom_admin'] ?? '',
                'nom_admin'    => $utilisateur['nom_admin']    ?? '',
                'cin_admin'    => $utilisateur['cin_admin']    ?? '',
                'role'         => (int)($utilisateur['role']  ?? 1),
            ];
        }
    }

    /**
     * Redirige l’utilisateur selon son rôle.
     * 0 = admin, 1 = rédacteur, 2 = validateur
     */
    /*public function redirigerParRole(int $role): void {
        switch ($role) {
            case 0:
                header('Location: admin_dashboard.php');
                break;
            case 1:
                header('Location: verifier_email.php');
                break;
            case 2:
                header('Location: verifier_contrat.php');
                break;
            default:
                header('Location: login.php?error=role_invalide');
        }
        exit;
    }


        /*public function getDemandeById($id_demande) {
            $sql = "SELECT * FROM public.\"T_demande\" WHERE id_demande = :id_demande";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_demande', $id_demande, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }*/
        

        /*public function close() {
            $this->conn = null;
        }*/





class contratManager {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function enregistrerContrat($id_demande, $annee_demande, $date_demande, $id_contrat) {
        try {
            $query = "INSERT INTO contrat (id_demande, annee_demande, date_demande) 
                    VALUES (:id_demande, :annee_demande, :date_demande)";
            $annee = date('Y', strtotime($date_demande));
            $annee_demande = $annee;
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_contrat', $id_contrat);
            $stmt->bindParam(':id_demande', $id_demande);
            $stmt->bindParam(':annee_demande', $annee_demande);
            $stmt->bindParam(':date_demande', $date_demande);
            $stmt->execute();
            return true; // succès
        } catch (PDOException $e) {
            error_log("Erreur enregistrement contrat : " . $e->getMessage());
            return false; // échec
        }
    }
    function demarrerSessionUtilisateur($utilisateur) {
        session_start();
    
        if (!isset($_SESSION['userAuth'])) {
            // $prenom = $utilisateur['prenom_admin'];
            // $nom = $utilisateur['nom_admin'];
            $_SESSION['userAuth'] = [
                'prenom_admin' => isset($utilisateur['prenom_admin']) ? $utilisateur['prenom_admin'] : '',
                'nom_admin' => isset($utilisateur['nom_admin']) ? $utilisateur['nom_admin'] : '',
                'cin_admin' => isset($utilisateur['cin_admin']) ? $utilisateur['cin_admin'] : '',
            ];
        } 
    } 
}