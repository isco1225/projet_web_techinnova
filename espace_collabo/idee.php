<?php
session_start();
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require("C:/xampp/htdocs/projet_web/config/commandes.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    die("Vous devez être connecté pour accéder à cette page.");
}

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['utilisateur']['id'];

// Requête pour récupérer les idées
$sql = "SELECT id, titre, description, votes FROM idee ORDER BY votes DESC";
$stmt = $db->prepare($sql);
$stmt->execute();
$idees = $stmt->fetchAll();

// Gestion du vote ou de l'annulation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote_id'])) {
    $idIdee = intval($_POST['vote_id']);

    // Vérifier si l'utilisateur a déjà voté pour cette idée
    $checkVoteSql = "SELECT * FROM user_votes WHERE user_id = :user_id AND idee_id = :idee_id";
    $checkVoteStmt = $db->prepare($checkVoteSql);
    $checkVoteStmt->execute([
        ':user_id' => $userId,
        ':idee_id' => $idIdee
    ]);
    $voteExists = $checkVoteStmt->fetch();

    if ($voteExists) {
        // Annuler le vote
        $deleteVoteSql = "DELETE FROM user_votes WHERE user_id = :user_id AND idee_id = :idee_id";
        $deleteVoteStmt = $db->prepare($deleteVoteSql);
        $deleteVoteStmt->execute([
            ':user_id' => $userId,
            ':idee_id' => $idIdee
        ]);

        // Réduire le nombre de votes pour l'idée
        $updateVoteSql = "UPDATE idee SET votes = votes - 1 WHERE id = :id";
        $updateVoteStmt = $db->prepare($updateVoteSql);
        $updateVoteStmt->execute([':id' => $idIdee]);
    } else {
        // Ajouter un vote
        $insertVoteSql = "INSERT INTO user_votes (user_id, idee_id) VALUES (:user_id, :idee_id)";
        $insertVoteStmt = $db->prepare($insertVoteSql);
        $insertVoteStmt->execute([
            ':user_id' => $userId,
            ':idee_id' => $idIdee
        ]);

        // Augmenter le nombre de votes pour l'idée
        $updateVoteSql = "UPDATE idee SET votes = votes + 1 WHERE id = :id";
        $updateVoteStmt = $db->prepare($updateVoteSql);
        $updateVoteStmt->execute([':id' => $idIdee]);
    }

    // Redirection pour éviter une double soumission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idées Innovantes - TechInnova</title>
    <link rel="stylesheet" href="css/idee.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
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

    <main id="content">
        <h2>Idées Innovantes</h2>
        <p class="intro">Explorez les idées ouvertes à la collaboration.</p>

        <div class="ideas-container">
            <?php if (!empty($idees)): ?>
                <?php foreach ($idees as $idee): ?>
                    <?php
                    // Vérifier si l'utilisateur a voté pour cette idée
                    $checkVoteSql = "SELECT * FROM user_votes WHERE user_id = :user_id AND idee_id = :idee_id";
                    $checkVoteStmt = $db->prepare($checkVoteSql);
                    $checkVoteStmt->execute([
                        ':user_id' => $userId,
                        ':idee_id' => $idee['id']
                    ]);
                    $hasVoted = $checkVoteStmt->fetch();
                    ?>
                    <div class="idea">
                        <h3><?= htmlspecialchars($idee['titre']) ?></h3>
                        <p><strong>Description :</strong> <?= htmlspecialchars($idee['description']) ?></p>
                        <a href="commentaire.php?id=<?= $idee['id'] ?>" class="comment-button">
                            <i class="fas fa-comment-alt"></i> Commenter
                        </a>
                        <form action="" method="POST" style="display: inline;">
                            <input type="hidden" name="vote_id" value="<?= $idee['id'] ?>">
                            <button type="submit" class="vote-button">
                                <i class="fas fa-thumbs-up"></i> <?= $hasVoted ? "Annuler le vote" : "Voter" ?>
                            </button>
                        </form>
                        <span class="vote-count">Votes : <?= htmlspecialchars($idee['votes']) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune idée disponible pour le moment.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
