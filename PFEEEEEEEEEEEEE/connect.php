<?php

session_start();


class ClsConnect {
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "pfe_bdd"; // Changez ceci selon votre nom de base de données
    private $user = "postgres";    // Changez ceci selon votre utilisateur
    private $pass = "pfe";           // Mettez votre mot de passe ici
    private $pdo;

    public function __construct() {
        try {
            $dsn = "pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;";
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            die();
        }
    }

    public function getConnection() {
        return $this->pdo;
    }



    public function verifierUtilisateur($cin, $password){
        if (empty($cin) || empty($password)) {
            echo "Veuillez remplir tous les champs obligatoires.";
            return false;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE cin = :cin_admin AND password = :password");
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
        $stmt = $this->pdo->prepare($sql);
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
            $stmt = $this->pdo->prepare($sql);
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
        $stmt = $this->pdo->prepare($sql);
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
        $stmt = $this->pdo->prepare($sql);
        //$stmt->execute();
        if ($stmt->rowCount() > 0) {    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }


   
    
    function getPiecesJointesByDemande($id_demande) {
        global $pdo;
        
        $id_demande = $id_demande;
        
        $sql = "SELECT * FROM pieces_jointes WHERE id_demande =".$id_demande;
        $result =$pdo->query($sql);
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
        $stmt = $this->pdo->prepare($sql); 
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
        $stmt = $this->pdo->prepare($sql); 
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
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':id', $id_demande, PDO::PARAM_INT);
        //$stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }



    function getAllDemandes() {
        try {
            // Requête simple pour récupérer toutes les données
            $sql = 'SELECT * FROM "T_demande"';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug
            error_log("Nombre de résultats trouvés : " . count($result));
            
            return $result;
        } catch(PDOException $e) {
            error_log("Erreur dans getAllDemandes : " . $e->getMessage());
            throw $e; // Remonter l'erreur pour la voir dans les logs
        }
    }
    




    public function getAgents() {
        $agents = [];
        try {
            $stmtRedacteur = $this->pdo->query("SELECT name, cin, email, password FROM redacteur");
            $redacteurs = $stmtRedacteur->fetchAll();
            foreach ($redacteurs as $agent) {
                $agent['role'] = 'redacteur';
                $agents[] = $agent;
            }

            $stmtValideur = $this->pdo->query("SELECT name, cin, email, password FROM valideur");
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
            $stmt = $this->pdo->prepare($sql);
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
            $stmt = $this->pdo->prepare($sql);
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


    function getTousLesUtilisateurs($conn) {
        $sql = "
            SELECT 
                id_redacteur AS id,
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
                id_valideur AS id,
                nom_valideur AS nom,
                prenom_valideur AS prenom,
                cin_valideur AS identification_number,
                password,
                post,
                email,
                adresse,
                telephone,
                'valideur' AS role 
            FROM valideur
        ";
    
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie si les champs nécessaires sont présents
            if (isset($_POST['post'], $_POST['agentName'], $_POST['cin'], $_POST['agentEmail'])) {
        
                $post = $_POST['post']; // 1 ou 2
                $nomPrenom = trim($_POST['agentName']);
                $cin = trim($_POST['cin']);
                $email = trim($_POST['agentEmail']);
                $adresse = trim($_POST['agentAdresse']);
                $telephone = trim($_POST['agentTele']);
                $date_naissance = $_POST['agentNaissance'];
                $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        
                // Séparer le nom et prénom
                $parts = explode(' ', $nomPrenom, 2);
                $nom = $parts[0] ?? '';
                $prenom = $parts[1] ?? '';
        
                // Déterminer la table selon le post
                if ($post == 1) {
                    $table = 'redacteur';
                } elseif ($post == 2) {
                    $table = 'valideur';
                } else {
                    die("Valeur de 'post' invalide.");
                }
        
                // Requête d'insertion
                $sql = "INSERT INTO $table (nom, prenom, identification_number, email, adresse, telephone, date_naissance, password)
                        VALUES (:nom, :prenom, :cin, :email, :adresse, :telephone, :date_naissance, :password)";
                $stmt = $pdo->prepare($sql);
        
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':cin', $cin);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':adresse', $adresse);
                $stmt->bindParam(':telephone', $telephone);
                $stmt->bindParam(':date_naissance', $date_naissance);
                $stmt->bindParam(':password', $password);
        
                if ($stmt->execute()) {
                    echo "Utilisateur ajouté avec succès.";
                } else {
                    echo "Erreur lors de l'ajout.";
                }
            } else {
                echo "Champs requis manquants.";
            }
        }
    }
    


    public function getContratsForValideur() {
        try {
            $sql = "SELECT c.id_contrat, c.date_contrat, c.id_demande, 
                           d.num_recu, d.date_demande
                    FROM public.contrat c
                    INNER JOIN public.\"T_demande\" d ON c.id_demande = d.id_demande
                    WHERE d.etat_demande = 1 AND c.etat_contrat = 0
                    ORDER BY d.date_demande DESC";
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans getContratsForValideur : " . $e->getMessage());
            return [];
        }
    }

    // Méthodes pour les demandes
    public function getDemandesByType($type, $id_redacteur = null) {
        $sql = "SELECT * FROM demandes WHERE type_demande = :type";
        if ($id_redacteur) {
            $sql .= " AND id_redacteur = :id_redacteur";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':type', $type);
        if ($id_redacteur) {
            $stmt->bindParam(':id_redacteur', $id_redacteur);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateDemandeStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE demandes SET etat_demande = :status WHERE id_demande = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Méthodes pour les contrats
    public function createContrat($data) {
        $stmt = $this->pdo->prepare("INSERT INTO contrats (id_demande, date_creation, id_redacteur) VALUES (:id_demande, :date_creation, :id_redacteur)");
        return $stmt->execute($data);
    }

    public function getContratsByStatus($status, $id_redacteur = null) {
        $sql = "SELECT c.*, d.num_recu FROM contrats c 
                JOIN demandes d ON c.id_demande = d.id_demande 
                WHERE c.etat_contrat = :status";
        if ($id_redacteur) {
            $sql .= " AND c.id_redacteur = :id_redacteur";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':status', $status);
        if ($id_redacteur) {
            $stmt->bindParam(':id_redacteur', $id_redacteur);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateContratStatus($id, $status, $motif = null) {
        $sql = "UPDATE contrats SET etat_contrat = :status";
        if ($motif) {
            $sql .= ", motif_rejet = :motif";
        }
        $sql .= " WHERE id_contrat = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        if ($motif) {
            $stmt->bindParam(':motif', $motif);
        }
        return $stmt->execute();
    }

    // Méthodes pour les parties du contrat
    public function savePartieContrat($data) {
        $stmt = $this->pdo->prepare("INSERT INTO parties_contrat 
            (id_contrat, nom, prenom, cin, date_naissance, lieu_naissance, adresse, type_partie) 
            VALUES (:id_contrat, :nom, :prenom, :cin, :date_naissance, :lieu_naissance, :adresse, :type_partie)");
        return $stmt->execute($data);
    }

    // Méthodes pour les détails du contrat
    public function saveDetailsContrat($data) {
        $stmt = $this->pdo->prepare("INSERT INTO details_contrat 
            (id_contrat, montant, surface, adresse_bien, description_bien, num_titre_foncier) 
            VALUES (:id_contrat, :montant, :surface, :adresse_bien, :description_bien, :num_titre_foncier)");
        return $stmt->execute($data);
    }

    // Méthodes pour les documents
    public function saveDocument($data) {
        $stmt = $this->pdo->prepare("INSERT INTO documents 
            (id_contrat, type_document, reference, date_document) 
            VALUES (:id_contrat, :type_document, :reference, :date_document)");
        return $stmt->execute($data);
    }

    public function getContratComplet($id_contrat) {
        $contrat = [];
        
        // Récupérer les informations de base du contrat
        $stmt = $this->pdo->prepare("SELECT c.*, d.num_recu, d.date_demande 
            FROM contrats c 
            JOIN demandes d ON c.id_demande = d.id_demande 
            WHERE c.id_contrat = :id");
        $stmt->bindParam(':id', $id_contrat);
        $stmt->execute();
        $contrat['base'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer les parties du contrat
        $stmt = $this->pdo->prepare("SELECT * FROM parties_contrat WHERE id_contrat = :id");
        $stmt->bindParam(':id', $id_contrat);
        $stmt->execute();
        $contrat['parties'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les détails du contrat
        $stmt = $this->pdo->prepare("SELECT * FROM details_contrat WHERE id_contrat = :id");
        $stmt->bindParam(':id', $id_contrat);
        $stmt->execute();
        $contrat['details'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer les documents
        $stmt = $this->pdo->prepare("SELECT * FROM documents WHERE id_contrat = :id");
        $stmt->bindParam(':id', $id_contrat);
        $stmt->execute();
        $contrat['documents'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $contrat;
    }

    // Méthodes pour les contrats dans PostgreSQL
    public function saveContratPG($id_demande) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO contrat (id_demande, date_creation, etat_contrat) 
                                        VALUES (:id_demande, CURRENT_DATE, 0) 
                                        RETURNING id_contrat");
            $stmt->execute([':id_demande' => $id_demande]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur saveContrat: " . $e->getMessage());
            throw $e;
        }
    }

    public function saveDocumentPG($id_contrat, $type, $reference, $date) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO documents (id_contrat, type_document, reference, date_document) 
                                        VALUES (:id_contrat, :type, :reference, :date)");
            return $stmt->execute([
                ':id_contrat' => $id_contrat,
                ':type' => $type,
                ':reference' => $reference,
                ':date' => $date
            ]);
        } catch (PDOException $e) {
            error_log("Erreur saveDocument: " . $e->getMessage());
            throw $e;
        }
    }

    public function savePartieContratPG($id_contrat, $data) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO parties_contrat 
                (id_contrat, nom, prenom, cin, date_naissance, lieu_naissance, adresse, type_partie) 
                VALUES (:id_contrat, :nom, :prenom, :cin, :date_naissance, :lieu_naissance, :adresse, :type_partie)");
            
            return $stmt->execute([
                ':id_contrat' => $id_contrat,
                ':nom' => $data['nom'],
                ':prenom' => $data['prenom'],
                ':cin' => $data['cin'],
                ':date_naissance' => $data['date_naissance'],
                ':lieu_naissance' => $data['lieu_naissance'],
                ':adresse' => $data['adresse'],
                ':type_partie' => $data['type']
            ]);
        } catch (PDOException $e) {
            error_log("Erreur savePartieContrat: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateDemandeStatusPG($id_demande, $status) {
        try {
            $stmt = $this->pdo->prepare("UPDATE demandes SET etat_demande = :status WHERE id_demande = :id");
            return $stmt->execute([
                ':status' => $status,
                ':id' => $id_demande
            ]);
        } catch (PDOException $e) {
            error_log("Erreur updateDemandeStatus: " . $e->getMessage());
            throw $e;
        }
    }

    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    public function commit() {
        return $this->pdo->commit();
    }

    public function rollBack() {
        return $this->pdo->rollBack();
    }

    function insertContractData($pdo) {
        if (isset($_POST['submit'])) {
            // Récupérer les données du formulaire
            $nom_droit1 = $_POST['nom_droit1'];
            $sujet_contrat1 = $_POST['sujet_contrat1'];
            $unite1 = $_POST['unite1'];
            $detail_general = (int)$_POST['detail_general1']; // ✔ correct
            $contenu1 = $_POST['contenu1'];
            $valeur_prix1 = $_POST['valeur_prix1'];
            $dure1 = $_POST['dure1'];
            $surplus1 = $_POST['surplus1'];
    
            try {
                $sql = "INSERT INTO dessin_immobiler1 
                    (nom_droit1, sujet_contrat1, unite1, detail_general, contenu1, valeur_prix1, dure1, surplus1)
                    VALUES 
                    (:nom_droit1, :sujet_contrat1, :unite1, :detail_general, :contenu1, :valeur_prix1, :dure1, :surplus1)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nom_droit1' => $nom_droit1,
                    ':sujet_contrat1' => $sujet_contrat1,
                    ':unite1' => $unite1,
                    ':detail_general' => $detail_general, // ✔ corrigé ici
                    ':contenu1' => $contenu1,
                    ':valeur_prix1' => $valeur_prix1,
                    ':dure1' => $dure1,
                    ':surplus1' => $surplus1
                ]);
    
                return "✅ Les données ont été enregistrées avec succès !";
            } catch (PDOException $e) {
                return "❌ Erreur : " . $e->getMessage();
            }
        }
        return null;
    }




    function insertContractData2($pdo) {
        if (isset($_POST['submit'])) {
            $stmt = $pdo->prepare("INSERT INTO dessin_immobilers2
                (date_inscri2, lieu_inscri2, doc2, num_inscri2, num_succursale2)
                VALUES 
                (:date_inscri2, :lieu_inscri2, :doc2, :num_inscri2, :num_succursale2)");
            
            $stmt->execute([
                ':date_inscri2' => $date_inscri2,
                ':lieu_inscri2' => $lieu_inscri2,
                ':doc2' => $doc2,
                ':num_inscri2' => $num_inscri2,
                ':num_succursale2' => $num_succursale2
            ]);
        }
    
    }
    














































































}


    /**
     * Démarre la session et stocke les infos de l'utilisateur.
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
     * Redirige l'utilisateur selon son rôle.
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