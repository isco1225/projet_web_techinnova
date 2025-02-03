<?php
session_start();
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require_once("C:/xampp/htdocs/projet_web/config/commandes.php");

if (isset($_SESSION['utilisateur'])) {
    $utilisateur = $_SESSION['utilisateur'];
    $id = $utilisateur['id'] ?? null;
    $nom = $utilisateur['nom'] ?? null;
    $prenom = $utilisateur['prenom'] ?? null;
    $email = $utilisateur['email'] ?? null;
    $profil = $utilisateur['profil'] ?? null;
}

// Récupérer les notifications pour l'utilisateur connecté

$sql = "SELECT message, date_creation FROM notifications WHERE id_destinateur = :user_id ORDER BY date_creation DESC";
$stmt = $db->prepare($sql);
$stmt->execute([':user_id' => $id]);
$notifications = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - TechInnova</title>
    <link rel="stylesheet" href="css/notification.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Barre de navigation -->
    <header class="navbar">
        <div class="logo">
            <h1><span>TECHIN</span>NOVA</h1>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="acceuil.php" data-page="accueil">Accueil</a></li>
                <li><a href="projetr&d.php" data-page="projets">Projets R&D</a></li>
                <li><a href="idee.php" data-page="idees">Idées innovantes</a></li>
                <li><a href="doc.php" data-page="documents">Documents</a></li>
            </ul>
        </nav>
        <div class="user-icons">
            <div class="user-icon">
                <a href="notification.php"><i class="fas fa-bell"></i></a>
            </div>
            <div class="user-icon">
                <a href="profil.php"><i class="fas fa-user-circle"></i></a>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main id="content">
        <h2>Notifications</h2>

        <!-- Liste des notifications -->
        <section class="notifications">
            <?php if (!empty($notifications)): ?>
                <ul>
                    <?php foreach ($notifications as $notif): ?>
                        <li>
                            <i class="fas fa-bell"></i>
                            <?= htmlspecialchars($notif['message']) ?>
                            <span class="timestamp"><?= htmlspecialchars($notif['date_creation']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune notification disponible.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
