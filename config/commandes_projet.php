<?php
require_once 'commandes.php';

class Projet {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function aff_projet($createur_id){
        try {
            $sql = "SELECT * FROM projets WHERE createur_id = :createur_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':createur_id', $createur_id);
            $stmt->execute();
            return $stmt;
        }catch (PDOException $e) {
            die("Erreur lors de la récupération des membres : " . $e->getMessage());
        }
    }

    public function ajouter_membre( $id_projet, $id_user){
        try {
            $sql = "INSERT INTO membres_projet (id_projet, id_utilisateur) VALUES (:id_projet, :id_utilisateur)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_projet', $id_projet);
            $stmt->bindParam(':id_utilisateur', $id_user);
            $stmt->execute();
        }catch (PDOException $e) {
            die("Erreur lors de la récupération des membres : " . $e->getMessage());
        }
        
    }

    public function retirer_membre(){
        try {
            $id_projet = $_POST['$id_projet'];
            $id_user = $_POST['$id_user'];
            $sql = "DELETE FROM projets_membres WHERE id_projet = :id_projet AND id_utilisateur = :id_utilisateur";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_projet', $id_projet);
            $stmt->bindParam(':id_utilisateur', $id_user);
            $stmt->execute();
        }catch (PDOException $e) {
            die("Erreur lors de la récupération des membres : " . $e->getMessage());
        }
        
    }

    public function aff_membre_projet(){
        try {
            $id_projet = $_POST['$id_projet'];
            $sql = "SELECT u.id, u.nom, u.email, u.role
                    FROM utilisateurs u
                    INNER JOIN projets_membres pm ON u.id = pm.id_utilisateur
                    WHERE pm.id_projet = :id_projet";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_projet', $id_projet);
            $stmt->execute();

            $membres = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $membres;
        }catch (PDOException $e) {
            die("Erreur lors de la récupération des membres : " . $e->getMessage());
        }
    }

    public function creer_projet($createur_id){
        try {

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $objectifs = $_POST['objectifs'];
            $etat = 'en_cours';
            $date_fin = $_POST['date_fin'];


            $sql = "INSERT INTO projets (titre, description_projet, objectifs, etat, createur_id, date_fin) VALUES (:titre, :description_projet, :objectifs, :etat, :createur_id, :date_fin)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description_projet', $description);
            $stmt->bindParam(':objectifs', $objectifs);
            $stmt->bindParam(':etat', $etat);
            $stmt->bindParam(':createur_id', $createur_id);
            $stmt->bindParam(':date_fin', $date_fin);
            $stmt->execute();

            return $this->db->lastInsertId();

            $notifs = new Notifications($this->db);
            $notifs->notifier_nouveau_projet($createur_id, $titre);

            


        }catch (PDOException $e) {
            die("Erreur lors de la récupération des membres : " . $e->getMessage());
        }
    }

    public function compterProjets() {
        try {
            // Requête pour compter les projets
            $sql = "SELECT COUNT(*) AS total_projets FROM projets WHERE etat = 'en cours'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retourner le total des projets
            return $result['total_projets'] ?? 0;
    
        } catch (PDOException $e) {
            echo "<p>Erreur lors du comptage des projets : " . htmlspecialchars($e->getMessage()) . "</p>";
            return 0; // Retourne 0 en cas d'erreur
        }
    }
    
}
?>