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
$sql = "SELECT id, titre, description, votes FROM idee WHERE etat = :etat ORDER BY date_soumission DESC";
$stmt = $db->prepare($sql);
$stmt->execute([
    'etat' => 'Acceptée'
]);
$idees = $stmt->fetchAll();


if (isset($_POST['add_idee'])) {
    $titre = trim($_POST['idee_titre']);
    $description = trim($_POST['idee_description']);

    try {
        // Vérifier si une idée avec le même titre existe déjà
        $checkSql = "SELECT COUNT(*) FROM idee WHERE titre = :titre";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->execute([':titre' => $titre]);
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            echo "<div class='erreur'><p>Une idée avec ce titre existe déjà !</p></div>";
        } else {
            // Préparer la requête d'insertion
            $sql = "INSERT INTO idee (titre, description, votes, etat, id_utilisateur) 
                    VALUES (:titre, :description, 0, 'en attente', :id_utilisateur)";
            $stmt = $db->prepare($sql);

            // Exécuter la requête
            $stmt->execute([
                ':titre' => htmlspecialchars($titre),
                ':description' => htmlspecialchars($description),
                ':id_utilisateur' => $userId
            ]);

            $id_idee = $db->lastInsertId();

            $id_destinateur = '1';
            $notifier_idees = new Notifications($db);
            $notifier_idees->notifier_nouvelle_idee($userId, $id_destinateur, $titre, $id_idee);

            echo "<div class='demande_envoyer'><p>L'idée a été soumise à l'administrateur :)</p></div>";
        }
    } catch (PDOException $e) {
        die("Erreur lors de l'ajout de l'idée : " . $e->getMessage());
    }
}


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
                <li><a href="chercheur.php" data-page="accueil">Accueil</a></li>
                <li><a href="projetr&d.php" data-page="projets">Projets R&D</a></li>
                <li><a href="idee.php" data-page="idees">Idées innovantes</a></li>
                <li><a href="doc.php" data-page="documents">Documents</a></li>
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

    <div class="main-content">
        <header>
            <h1>Idées Innovantes</h1>
            <div class="search-container">
                <input type="text" id="searchIdea" placeholder="Rechercher une idée...">
                <button id="searchBtn"><i class="fas fa-search"></i></button>
        </header>

        <main id="content">
            <h2>Idées Innovantes</h2>
            <p class="intro">Explorez les idées ouvertes à la collaboration.</p>
            <section class="actions">
                <button id="addIdeaBtn">Ajouter une Nouvelle Idée</button>
            </section>

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
                                    <i class="fas fa-thumbs-up"></i> <?= $hasVoted ? "Annuler" : "Voter" ?>
                                </button>
                            </form>
                            <span class="vote-count">Votes : <?= htmlspecialchars($idee['votes']) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune idée disponible pour le moment.</p>
                <?php endif; ?>
            </div>

            <!-- Modale pour ajouter une idée -->
            <div id="addIdeaModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Ajouter une Nouvelle Idée</h2>
                    <form action="" method="post" id="addIdeaForm">
                        <label for="ideaTitle">Titre :</label>
                        <input type="text" id="ideaTitle" name="idee_titre" required>

                        <label for="ideaDescription">Description :</label>
                        <textarea id="ideaDescription" name="idee_description" required></textarea>

                    <button type="submit" name="add_idee">Ajouter l'Idée</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="js/idee.js" defer>
        
    </script>
</body>
</html>
