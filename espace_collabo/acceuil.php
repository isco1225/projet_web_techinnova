<?php
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require_once("C:/xampp/htdocs/projet_web/config/commandes.php");

session_start();


// projets
$sql = "SELECT * FROM projets";
$stmt = $db->prepare($sql);
$stmt->execute();
$projets = $stmt->fetchAll();

// idees


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - TechInnova</title>
    <link rel="stylesheet" href="css/acceuil.css">
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
        <section class="hero">
            <h2>Accueil</h2>
            <p>Bienvenue dans votre espace collaboratif. Retrouvez vos projets et les dernières notifications.</p>
        </section>


        <!-- Résumé des projets -->
        <section class="project-summary">
            <h3>Projets en ligne</h3>
            <div class="projects">
                <?php if (!empty($projets)) : ?>
                    <?php foreach ($projets as $projet) : ?>
                        <div class="project">
                            <h4><?= htmlspecialchars($projet['titre']); ?></h4>
                            <p><strong>Description :</strong> <?= htmlspecialchars($projet['description_projet']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucun projet disponible pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>

    </main>
</body>
</html>