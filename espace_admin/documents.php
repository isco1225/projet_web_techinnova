<?php
require_once("C:/xampp/htdocs/projet_web/config/connexion.php"); // Connexion à la base de données

// Récupérer les documents depuis la base de données
try {
    $sql = "SELECT id, nom_fichier, chemin, categorie FROM documents ORDER BY date_upload DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des documents : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents - TechInnova</title>
    <link rel="stylesheet" href="css/documents.css">
    <!-- Lien vers Font Awesome pour les icônes -->
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
                <li><a href="users.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
                <li><a href="statistiques.php"><i class="fas fa-chart-bar"></i> Statistiques</a></li>
                <li><a href="calendrier.php"><i class="fas fa-calendar-alt"></i> Calendrier</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <header>
            <h1>Documents</h1>
            <section class="actions">
                <button id="addDocumentBtn">Ajouter un Document</button>
            </section>
            <div id="header-icons">
                <a href="notification.php" id="notificationsBtn"><i class="fas fa-bell"></i></a>
                <a href="profil.php" id="profilBtn"><i class="fas fa-user"></i></a>
            </div>
        </header>

        <section class="documents-list">
            <?php if (!empty($documents)): ?>
                <?php foreach ($documents as $document): ?>
                    <div class="document">
                        <h3><i class="fas fa-file-alt"></i> <?= htmlspecialchars($document['nom_fichier']) ?></h3>
                        <p><strong>Catégorie :</strong> <?= htmlspecialchars($document['categorie']) ?></p>
                        <a href="<?= htmlspecialchars($document['chemin']) ?>" class="downloadBtn" download>
                            <i class="fas fa-download"></i> Télécharger
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun document disponible pour le moment.</p>
            <?php endif; ?>
        </section>



        <!-- Modale pour ajouter un document -->
        <div id="addDocumentModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Ajouter un Document</h2>
                <form id="addDocumentForm" method="POST" enctype="multipart/form-data">
                    <label for="documentTitle">Titre :</label>
                    <input type="text" id="documentTitle" name="documentTitle" required>

                    <label for="documentFile">Fichier :</label>
                    <input type="file" id="documentFile" name="documentFile" required>

                    <button type="submit" name="submit">Ajouter le Document</button>
                </form>
            </div>
        </div>
    </div>

    <script src="js/script.js" defer></script>

</body>
</html>
