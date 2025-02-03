<?php 

require_once 'commandes_projet.php';


class Utilisateur {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function inscription() {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['mail'];
        $mot_de_passe = $_POST['password'];
        $confirm_pass = $_POST['confirm_password'];
      
        $date_creation = date('Y-m-d H:i:s');

        if ($_POST['profil'] === NULL){
            $profil = 'collaborateur_externe';
        }else{
            $profil = $_POST['profil'];
        }

        if ($confirm_pass != $mot_de_passe) {
            echo 'Les mots de passes ne correspondent pas';
            return;
        }

        try {
            $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, profil, date_creation) 
                    VALUES (:nom, :prenom, :email, MD5(:mot_de_passe), :profil, :date_creation)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mot_de_passe', $mot_de_passe);
            $stmt->bindParam(':profil', $profil);
            $stmt->bindParam(':date_creation', $date_creation);

            $stmt->execute();

            $sql = "SELECT * FROM utilisateurs WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if (md5($mot_de_passe) === $utilisateur['mot_de_passe']) {
                session_start();
                if ($utilisateur['profil'] === 'administrateur'){
                    header("location: //localhost/projet_web/espace_admin/admin.php");
                    exit;

                }elseif ($utilisateur['profil'] === 'collaborateur_externe'){
                    header("location: //localhost/projet_web/espace_collabo/acceuil.php");
                    exit;
                }else {
                    header("location: //localhost/projet_web/espace_chercheur/chercheur.php");
                    exit;
                }
                

            }
        } catch (Exception $e) {
            echo "<script>alert('Erreur lors de l\'inscription : {$e->getMessage()}');</script>";
        }
    }



    public function connexion() {
        $email = $_POST['mail'] ?? '';
        $mot_de_passe = $_POST['password'] ?? '';
    
        if (empty($email) || empty($mot_de_passe)) {
            echo "<script>alert('Veuillez remplir tous les champs.');</script>";
            return;
        }
    
        try {
            $sql = "SELECT * FROM utilisateurs WHERE email = :email AND mot_de_passe = MD5(:mot_de_passe)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mot_de_passe', $mot_de_passe);
            $stmt->execute();
    
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($utilisateur) {
                session_start();
                $_SESSION['utilisateur'] = $utilisateur;
    
                if ($utilisateur['profil'] === 'administrateur') {
                    header("location: http://localhost/projet_web/espace_admin/admin.php");
                    exit;
                } elseif ($utilisateur['profil'] === 'chercheur') {
                    header("location: http://localhost/projet_web/espace_chercheur/chercheur.php");
                    exit;
                }else {
                    header("location: http://localhost/projet_web/espace_collabo/acceuil.php");
                    exit;
                }
            } else {
                echo "<script>alert('Email ou mot de passe incorrect.');</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('Erreur de connexion : {$e->getMessage()}');</script>";
        }
    }

}


class Chercheur {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function aff_projets($createur_id){
        $projet = new Projet($this->db);
        $projet->aff_projet($createur_id);
    }

    public function ajouter_membres($id_projet, $id_user){
        $projet = new Projet($this->db);
        $projet->ajouter_membre($id_projet, $id_user);
    }

    public function creer_projets($createur_id){
        $projet = new Projet($this->db);
        $projet->creer_projet($createur_id);
        return $this->db->lastInsertId();
    }

        // Fonction pour compter le nombre de projets
        public function compter_projets() {
            $query = "SELECT COUNT(*) as total FROM projets";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        }
    
        // Fonction pour compter le nombre d'idées
        public function compter_idees() {
            $query = "SELECT COUNT(*) as total FROM idee";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        }
    
        // Fonction pour compter le nombre de documents
        public function compter_documents() {
            $query = "SELECT COUNT(*) as total FROM documents";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        }


}



class Notifications {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function insertion_notifs($id_utilisateur, $id_destinateur, $type, $message) {
        $sql = "INSERT INTO notifications (id_utilisateur, id_destinateur, type, message) VALUES (:id_utilisateur, :id_destinateur, :type, :message)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_utilisateur' => $id_utilisateur,
            'type' => $type,
            'message' => $message
        ]);
    }

    public function envoyerNotificationsProjets() {
        // Obtenir la date actuelle
        $aujourdhui = new DateTime();
    
        // Définir les délais pour lesquels les notifications doivent être envoyées
        $delais = [7, 1]; // Notifications prévues pour 7 jours et 1 jour avant l'échéance
    
        foreach ($delais as $jours) { // Boucle sur chaque délai
            // Calculer la date cible
            $dateCible = (clone $aujourdhui)->modify("+$jours days")->format('Y-m-d');
    
            // Récupérer les projets se terminant à cette date
            $sql = "SELECT * FROM projets WHERE date_fin = :dateCible";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':dateCible' => $dateCible]);
    
            $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($projets as $projet) {
                $titre_projet = htmlspecialchars($projet['titre']);
                $createur_id = htmlspecialchars($projet['createur_id']);
    
                // Utiliser la méthode générique pour envoyer la notification
                $this->notifier_echeance_proche($createur_id, $createur_id, $titre_projet, $jours);
            }
        }
    }
    
    

    public function envoyer_notification($id_utilisateur, $id_destinateur, $type, $message) {
        try {
            $sql = "INSERT INTO notifications (id_utilisateur, id_destinateur, type, message) VALUES (:id_utilisateur, :id_destinateur, :type, :message)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_utilisateur' => $id_utilisateur,
                'id_destinateur' => $id_destinateur,
                'type' => $type,
                'message' => $message
            ]);
        } catch (PDOException $e) {
            echo "Erreur d'insertion de la notification : " . $e->getMessage();
        }
    }

    public function envoyer_notification_idee($id_utilisateur, $id_destinateur, $type, $message, $id_idee) {
        try {
            $sql = "INSERT INTO notifications (id_utilisateur, id_destinateur, type, message, id_idee) VALUES (:id_utilisateur, :id_destinateur, :type, :message, :id_idee)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_utilisateur' => $id_utilisateur,
                'id_destinateur' => $id_destinateur,
                'type' => $type,
                'message' => $message,
                'id_idee' => $id_idee
            ]);
        } catch (PDOException $e) {
            echo "Erreur d'insertion de la notification : " . $e->getMessage();
        }
    }
    

    public function notifier_nouveau_projet($id_createur, $id_destinateur, $titre_projet) {
        $message = "Un nouveau projet intitulé '$titre_projet' a été créé. ";
        $this->envoyer_notification($id_createur, $id_destinateur, 'nouveau_projet', $message);
    }

    public function notifier_demande_accepter($id_createur, $id_destinateur, $titre_projet) {
        $message = "Votre demande de participation au projet '$titre_projet' a été acceptée. ";
        $this->envoyer_notification($id_createur, $id_destinateur, 'demande_participation', $message);
    }
    public function notifier_demande_refuser($id_createur, $id_destinateur, $titre_projet) {
        $message = "Votre demande de participation au projet '$titre_projet' a été rejété. ";
        $this->envoyer_notification($id_createur, $id_destinateur, 'demande_participation', $message);
    }

    public function notifier_nouvelle_tache($id_createur, $id_destinateur, $titre_projet) {
        $message = "Une nouvelle tache vous a été assigné sur le projet '$titre_projet'";
        $this->envoyer_notification($id_createur, $id_destinateur, 'nouvelle_tache', $message);
    }
    public function notifier_nouvelle_idee($id_createur, $id_destinateur, $titre_idee, $id_idee) {
        $message = "Une nouvelle idée intitulée '$titre_idee' a été soumise. ";
        $this->envoyer_notification_idee($id_createur,$id_destinateur, 'nouvelle_idee', $message, $id_idee);
    }
    public function idee_acceptee($id_createur, $id_destinateur, $titre_idee) {
        $message = "Votre idée intitulé '$titre_idee' a été acceptée. ";
        $this->envoyer_notification($id_createur,$id_destinateur, 'idee_soumise', $message);
    }

    public function idee_refusee($id_createur, $id_destinateur, $titre_idee) {
        $message = "Votre idée intitulé '$titre_idee' a été refusée. ";
        $this->envoyer_notification($id_createur,$id_destinateur, 'nouvelle_idee', $message);
    }

    public function notifier_tache_terminee($id_utilisateur,$id_destinateur, $titre_tache) {
        $message = "La tâche '$titre_tache' a été marquée comme terminée. ";
        $this->envoyer_notification($id_utilisateur,$id_destinateur, 'tache_terminee', $message);
    }

    public function notifier_echeance_proche($id_utilisateur, $id_destinateur, $titre_projet, $jours_restants) {
        $message = "Le projet '$titre_projet' approche de sa date d'échéance qui est dans $jours_restants jour" . ($jours_restants > 1 ? "s" : "") . ".";
        $this->envoyer_notification($id_utilisateur, $id_destinateur, 'echeance_proche', $message);
    }
    public function notifier_demande($id_utilisateur,$id_destinateur, $titre_projet) {
        $message = "Un utilisateur souhaite participer au projet : " . $titre_projet;
        $this->envoyer_notification($id_utilisateur, $id_destinateur, 'demande_participation', $message);
    }

    public function notifier_demande_self($id_utilisateur,$id_destinateur, $titre_projet) {
        $message = "Votre demande de participation au projet : " . $titre_projet . "a été envoyé";
        $this->envoyer_notification($id_utilisateur, $id_destinateur, 'demande_participation', $message);
    }
    


    public function afficher_notifications($id_utilisateur) {
        try {
            // Récupérer les notifications de l'utilisateur
            $sql = "SELECT * FROM notifications WHERE id_utilisateur = :id_utilisateur AND id_destinateur = :id_utilisateur ORDER BY id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_utilisateur' => $id_utilisateur]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier s'il y a des notifications
            if (empty($notifications)) {
                echo "<p>Aucune notification disponible.</p>";
                return;
            }

            // Afficher les notifications
            foreach ($notifications as $notif) {
                $classe = $notif['status'] === 'unread' ? 'unread' : 'read';
                echo "<div class='notification $classe'>";
                echo "<p>" . htmlspecialchars($notif['message']) . "</p>";

                // Ajouter un bouton pour marquer comme lu si non lu
                if ($notif['status'] === 'unread') {
                    echo "
                    <form method='POST' action='notification.php'>
                        <input type='hidden' name='notification_id' value='" . $notif['id'] . "'>
                        <button type='submit' name='mark_as_read'>Marquer comme lu</button>
                    </form>";
                }

                echo "</div>";
            }
        } catch (PDOException $e) {
            echo "<p>Erreur lors de la récupération des notifications : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    // Méthode pour marquer une notification comme lue
    public function marquer_comme_lue($id_notification) {
        try {
            $sql = "UPDATE notifications SET status = 'read' WHERE id = :id_notification";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_notification' => $id_notification]);
        } catch (PDOException $e) {
            echo "<p>Erreur lors de la mise à jour de la notification : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    public function aff_all_notifs() {
        try {
            // Requête SQL avec jointure pour récupérer le nom et prénom de l'utilisateur
            $sql = "
                SELECT
                    notifications.*, 
                    utilisateurs.nom AS utilisateur_nom, 
                    utilisateurs.prenom AS utilisateur_prenom 
                FROM notifications 
                JOIN utilisateurs ON notifications.id_utilisateur = utilisateurs.id 
                ORDER BY notifications.id DESC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $notifications;
    
        } catch (PDOException $e) {
            echo "<p>Erreur lors de la récupération des notifications : " . htmlspecialchars($e->getMessage()) . "</p>";
            return [];
        }
    }
    

}




class Admin {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }


    
    //
    // gestions des utilisateurs
    //
    public function aff_users(){
        try {
            $sql = "SELECT * FROM utilisateurs";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $membres = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $membres;
        }catch (PDOException $e) {
            die("Erreur lors de la récupération des membres : " . $e->getMessage());
        }
    }


    public function supp_user($id) {
        $query = "DELETE FROM utilisateurs WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo "Utilisateur supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression de l'utilisateur.";
        }
    }

    public function modi_user($id, $nom, $prenom, $email, $profil) {
        try {
            // Préparer la requête SQL pour mettre à jour les champs spécifiques
            $sql = "UPDATE utilisateurs 
                    SET nom = :nom, 
                        prenom = :prenom, 
                        email = :email, 
                        profil = :profil 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':profil', $profil, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
            // Exécuter la requête
            $stmt->execute();
    
            return true; // Succès
        } catch (PDOException $e) {
            // Gestion des erreurs
            echo "Erreur lors de la modification de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }
    

    //
    // gestions des projets
    //
    public function ajout_projet(){
        $sql = "SELECT * FROM clients";
    }

    //
    //

}

?>