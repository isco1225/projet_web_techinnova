<?php
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require_once("C:/xampp/htdocs/projet_web/config/commandes.php");

session_start();

if (isset($_SESSION['utilisateur'])) {
    $utilisateur = $_SESSION['utilisateur'];
    $id = $utilisateur['id'] ?? null;
    $nom = $utilisateur['nom'] ?? null;
    $prenom = $utilisateur['prenom'] ?? null;
    $email = $utilisateur['email'] ?? null;
    $profil = $utilisateur['profil'] ?? null;
} else {
    echo "Aucune donnée utilisateur dans la session.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accepter'])) {
    $notification_id = $_POST['notification_id'];

    try {
        // Récupérer l'utilisateur lié à la notification
        $sql_get_user = "SELECT id_utilisateur, id_idee FROM notifications WHERE id = :id ";
        $stmt_get_user = $db->prepare($sql_get_user);
        $stmt_get_user->bindParam(':id', $notification_id, PDO::PARAM_INT);
        $stmt_get_user->execute();
        $user = $stmt_get_user->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user_id = $user['id_utilisateur'];
            $id_idee = $user['id_idee'];
        
            // Récupérer l'idée associée à l'utilisateur
            $sql_get_idee = "SELECT titre FROM idee WHERE id_utilisateur = :id_utilisateur AND id = :id_idee";
            $stmt_get_idee = $db->prepare($sql_get_idee);
            $stmt_get_idee->bindParam(':id_utilisateur', $user_id, PDO::PARAM_INT);
            $stmt_get_idee->bindParam(':id_idee', $id_idee, PDO::PARAM_INT);
            $stmt_get_idee->execute();
            $idee = $stmt_get_idee->fetch(PDO::FETCH_ASSOC);
        
            // Modifier l'état de l'idée à 'Acceptée'
            $sql_modif_etat = "UPDATE idee SET etat = 'Acceptée' WHERE id_utilisateur = :id_utilisateur AND id = :id_idee";
            $stmt_modif_etat = $db->prepare($sql_modif_etat);
            $stmt_modif_etat->bindParam(':id_utilisateur', $user_id, PDO::PARAM_INT);
            $stmt_modif_etat->bindParam(':id_idee', $id_idee, PDO::PARAM_INT);
            $stmt_modif_etat->execute();
        
            // Modifier le type de notification
            $sql_modif_type = "UPDATE notifications SET type = 'idee_soumise' WHERE id = :notification_id";
            $stmt_modif_type = $db->prepare($sql_modif_type);
            $stmt_modif_type->bindParam(':notification_id', $notification_id, PDO::PARAM_INT);
            $stmt_modif_type->execute();
        
            // Supprimer la notification
            $sql_delete_notification = "DELETE FROM notifications WHERE id = :id";
            $stmt_delete_notification = $db->prepare($sql_delete_notification);
            $stmt_delete_notification->bindParam(':id', $notification_id, PDO::PARAM_INT);
            $stmt_delete_notification->execute();
        
            // Envoyer une notification d'acceptation
            $notifications = new Notifications($db);
            $notifications->idee_acceptee($user_id, $user_id, $idee['titre']);
        
            header("Location: admin.php");
            exit;
        } else {
            echo "Utilisateur non trouvé pour cette notification.";
        }
        
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout de l'utilisateur au projet : " . $e->getMessage();
    }
}


// Traitement du refus d'une demande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refuser'])) {
    $notification_id = $_POST['notification_id'];

    try {

        $user_id = $user['id_utilisateur'];

        // Récupérer l'idée associée à l'utilisateur (ajoutez cette ligne avant la modification de l'état)
        $sql_get_idee = "SELECT titre FROM idee WHERE id_utilisateur = :id_utilisateur";
        $stmt_get_idee = $db->prepare($sql_get_idee);
        $stmt_get_idee->bindParam(':id_utilisateur', $user_id);
        $stmt_get_idee->execute();
        $idee = $stmt_get_idee->fetch(PDO::FETCH_ASSOC);

        // Supprimer la notification une fois refusée
        $sql_delete_notification = "DELETE FROM notifications WHERE id = :id";
        $stmt_delete_notification = $db->prepare($sql_delete_notification);
        $stmt_delete_notification->bindParam(':id', $notification_id, PDO::PARAM_INT);
        $stmt_delete_notification->execute();

        echo "La demande de participation a été refusée.";
        $notificationss = new Notifications($db);
        $notificationss->idee_refusee($user_id, $user_id, $idee['titre']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors du refus de la demande : " . $e->getMessage();
    }
}

// Charger les notifications
$notifications = new Notifications($db);
$all_notifications = $notifications->aff_all_notifs(); // Assurez-vous que cette méthode retourne un tableau
// Charger les données statistiques
$chercheur = new Chercheur($db);
$total_projets = $chercheur->compter_projets();
$total_idees = $chercheur->compter_idees();
$total_documents = $chercheur->compter_documents();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Interface Administrateur</title>
</head>
<body>
    <div class="sidebar">
        <h2><span>TechIn</span>nova</h2>
        <nav>
            <ul>
                <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                <li><a href="projetr&d.php"><i class="fas fa-project-diagram"></i> Projets R&D</a></li>
                <li><a href="idees.php"><i class="fas fa-lightbulb"></i> Idées Innovantes</a></li>
                <li><a href="documents.php"><i class="fas fa-file-alt"></i> Documents</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
                <li><a href="statistiques.php"><i class="fas fa-chart-bar"></i> Statistiques</a></li>
                <li><a href="calendrier.php"><i class="fas fa-calendar-alt"></i> Calendrier</a></li>
                <li><a href="partenariat.php"><i class="fas fa-calendar-alt"></i> Partenariat</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <header>
            <h1>Tableau de bord</h1>
            <div id="header-icons">
                <a href="notification.php" id="notificationsBtn"><i class="fas fa-bell"></i></a>
                <a href="profil.php" id="profilBtn"><i class="fas fa-user"></i></a>
            </div>
        </header>

        <!-- Section Statistiques -->
        <section class="stats">
            <div class="card">
                <h3>Projets en cours</h3>
                <p><?php echo $total_projets; ?></p>
            </div>
            <div class="card">
                <h3>Idées soumises</h3>
                <p><?php echo $total_idees; ?></p>
            </div>
            <div class="card">
                <h3>Documents téléchargés</h3>
                <p><?php echo $total_documents; ?></p>
            </div>
        </section>

        <!-- Section Activités Récentes -->
        <section class="recent-activities">
            <h2>Activités récentes</h2>
            <ul>
                <?php if (!empty($all_notifications)) : ?>
                    <?php foreach ($all_notifications as $notification) : ?>
                        <?php if ($notification['type'] === 'nouvelle_idee'): ?>
                            <form method="POST" action="">
                                <input type="hidden" name="notification_id" value="<?= htmlspecialchars($notification['id']) ?>">
                                <button type="submit" name="accepter">Accepter</button>
                                <button type="submit" name="refuser">Refuser</button>
                            </form>
                        <?php endif; ?>
                        <li>
                            <strong>Type :</strong> <?= htmlspecialchars($notification['type']) ?><br>
                            <strong>Message :</strong> <?= htmlspecialchars($notification['message']) ?><br>
                            <strong>Ajouté par :</strong>
                            <?= htmlspecialchars($notification['utilisateur_prenom']) ?> 
                            <?= htmlspecialchars($notification['utilisateur_nom']) ?>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li>Aucune activité récente.</li>
                <?php endif; ?>
            </ul>
        </section>
    </div>

    <section class="nav_droite">
        <a href="ajout_projet.php">
            <div class="card_projet"><p>+ Nouveau projet</p></div>
        </a>
        <a href="#">
            <div class="card_idee"><p>+ Nouvelle idée</p></div>
        </a>
    </section>

    <script src="js/script.js" defer></script>
</body>
</html>
