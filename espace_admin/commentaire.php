<?php

session_start();
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require("C:/xampp/htdocs/projet_web/config/commandes.php");


if (isset($_SESSION['utilisateur'])) {
    $utilisateur = $_SESSION['utilisateur'];
    $id = $utilisateur['id'] ?? null;
    $nom = $utilisateur['nom'] ?? null;
    $prenom = $utilisateur['prenom'] ?? null;
    $email = $utilisateur['email'] ?? null;
    $profil = $utilisateur['profil'] ?? null;
}

// Requête pour récupérer les commentaires
$sql = "SELECT 
            c.id AS id_commentaire,
            c.contenu AS contenu_commentaire,
            c.date_commentaire,
            u.nom AS nom_utilisateur,
            u.prenom AS prenom_utilisateur,
            i.titre AS titre_idee
        FROM 
            commentaires c
        LEFT JOIN 
            utilisateurs u ON c.id_utilisateur = u.id
        LEFT JOIN 
            idee i ON c.id_idee = i.id
        ORDER BY 
            c.date_commentaire DESC";

$stmt = $db->prepare($sql);
$stmt->execute();
$commentaires = $stmt->fetchAll();

// Traitement du formulaire pour ajouter un nouveau commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $nouveauCommentaire = $_POST['comment'];
    $idUtilisateur = $id; // Remplacer par l'ID réel de l'utilisateur connecté
    $idIdee = $id_idee; // Remplacer par l'ID réel de l'idée correspondante

    $insertSql = "INSERT INTO commentaires (contenu, id_utilisateur, id_idee, date_commentaire) 
                  VALUES (:contenu, :id_utilisateur, :id_idee, NOW())";
    $insertStmt = $db->prepare($insertSql);
    $insertStmt->execute([
        ':contenu' => $nouveauCommentaire,
        ':id_utilisateur' => $idUtilisateur,
        ':id_idee' => $idIdee,
    ]);

    // Redirection pour éviter la soumission multiple
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires - TechInnova</title>
    <link rel="stylesheet" href="//localhost/projet_web/espace_chercheur/css/commentaire.css">
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
                <li><a href="chercheur.php">Accueil</a></li>
                <li><a href="projetr&d.php">Projets R&D</a></li>
                <li><a href="idee.php">Idées innovantes</a></li>
                <li><a href="doc.php">Documents</a></li>
                <li><a href="calendrier.php" data-page="calendrier">Calendrier</a></li>
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
        <h2>Commentaires pour l'idée "Idée Alpha"</h2>
        <section class="comments">
            <h3>Commentaires existants</h3>
            <?php if (!empty($commentaires)): ?>
                <?php foreach ($commentaires as $comment): ?>
                    <div class="comment">
                        <p><strong><?= htmlspecialchars($comment['prenom_utilisateur']) . " " . htmlspecialchars($comment['nom_utilisateur']) ?> :</strong> 
                        <?= htmlspecialchars($comment['contenu_commentaire']) ?></p>
                        <span class="timestamp">Posté le <?= htmlspecialchars($comment['date_commentaire']) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun commentaire pour le moment.</p>
            <?php endif; ?>
        </section>

        <!-- Ajouter un nouveau commentaire -->
        <section class="add-comment">
            <h3>Ajouter un commentaire</h3>
            <form action="" method="POST">
                <textarea name="comment" placeholder="Écrivez votre commentaire ici..." required></textarea>
                <button type="submit"><i class="fas fa-paper-plane"></i> Envoyer</button>
            </form>
        </section>
    </main>
</body>
</html>
