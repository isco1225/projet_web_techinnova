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
}

try {
    // Récupérer les documents depuis la table "documents"
    $sql = "
        SELECT
            d.id AS document_id,
            d.nom_fichier,
            d.categorie,
            d.chemin,
            p.titre,
            p.description_projet
        FROM documents d
        INNER JOIN projets p ON d.id_projet = p.id
        ORDER BY d.categorie, p.titre
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupérer les résultats sous forme de tableau associatif
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents - TechInnova</title>
    <link rel="stylesheet" href="css/doc.css">
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
                <li><a href="projetr&d.php" data-page="projets" class="active">Projets R&D</a></li>
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
        <h2>Documents Partagés</h2>
        <p class="intro">Consultez et téléchargez les documents liés aux projets collaboratifs. Voici la liste complète des documents disponibles :</p>

        <!-- Liste des documents -->
        <section class="categories">
            <?php
            // Afficher les documents un par un
            foreach ($documents as $doc) {
                echo "<div class='category'>"; // Conteneur de catégorie
                echo "<h3>" . htmlspecialchars($doc['categorie']) . "</h3>"; // Titre de catégorie

                echo "<div class='document'>"; // Conteneur de document
                echo "<h4>" . htmlspecialchars($doc['nom_fichier']) . "</h4>"; // Nom du fichier
                echo "<p><strong>Projet :</strong> " . htmlspecialchars($doc['titre']) . "</p>"; // Titre du projet
                echo "<p><strong>Description :</strong> " . htmlspecialchars($doc['description_projet']) . "</p>"; // Description du projet
                echo "<a href='//localhost/projet_web/espace_chercheur/uploads/". htmlspecialchars($doc['nom_fichier']) . "' download class='download-button'>";
                echo "<i class='fas fa-download'></i> Télécharger</a>"; // Bouton de téléchargement
                echo "</div>"; // Fin du conteneur de document

                echo "</div>"; // Fin du conteneur de catégorie
            }
            ?>
        </section>


        <!-- Message de restriction -->
        <div class="restriction">
            <p><i class="fas fa-info-circle"></i> Vous ne pouvez ni modifier ni supprimer les documents partagés.</p>
        </div>
    </main>
</body>
</html>

