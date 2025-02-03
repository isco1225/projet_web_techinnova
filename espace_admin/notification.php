<?php

require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require_once("C:/xampp/htdocs/projet_web/config/commandes.php");

session_start();

if (isset($_SESSION['utilisateur'])) {
    $utilisateur = $_SESSION['utilisateur']; // Récupère l'ensemble des données stockées
    $id = $utilisateur['id'] ?? null; // Récupère l'ID (si présent)
    $nom = $utilisateur['nom'] ?? null; // Récupère le nom (si présent)
    $prenom = $utilisateur['prenom'] ?? null; // Récupère le nom (si présent)
    $email = $utilisateur['email'] ?? null; // Récupère l'email (si présent)
    $profil = $utilisateur['profil'] ?? null; // Récupère le rôle (si présent)

} else {
    echo "Aucune donnée utilisateur dans la session.";
}



$notifications = new Notifications($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_read'], $_POST['notification_id'])) {
    $id_notification = $_POST['notification_id'];
    $notifications->marquer_comme_lue($id_notification);
    header("Location: notification.php");
    exit;
}


/*
// Vérifie si une notification doit être marquée comme lue
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_read'])) {
    $id_notification = $_POST['notification_id'] ?? null;
    if ($id_notification) {
        try {
            $sql = "UPDATE notifications SET status = 'read' WHERE id = :id AND id_utilisateur = :id_utilisateur";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id' => $id_notification,
                'id_utilisateur' => $id_utilisateur
            ]);
        } catch (PDOException $e) {
            $error_message = "Erreur lors de la mise à jour de la notification : " . $e->getMessage();
        }
    }
}

// Récupération des notifications depuis la base de données
$notifications = [];
try {
    $sql = "SELECT * FROM notifications WHERE id_utilisateur = :id_utilisateur ORDER BY id DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id_utilisateur' => $id_utilisateur]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Erreur lors de la récupération des notifications : " . $e->getMessage();
}
*/
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - TechInnova</title>
    <link rel="stylesheet" href="css/notifications.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <li><a href="notification.php" class="active"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="statistiques.php"><i class="fas fa-chart-bar"></i> Statistiques</a></li>
                <li><a href="calendrier.php"><i class="fas fa-calendar-alt"></i> Calendrier</a></li>
                <li><a href="partenariat.php"><i class="fas fa-calendar-alt"></i> Partenariat</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <header>
            <h1>Notifications</h1>
        </header>

        <section class="notifications-list">
        <?php
            if (isset($id)) {
                $notifications->afficher_notifications($id);
            } else {
                echo "<p>Veuillez vous connecter pour voir vos notifications.</p>";
            }
            ?>
        </section>

    </div>
</body>
</html>
