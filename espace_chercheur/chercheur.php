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

function getNombreProjets($userId, $db) {
    $sql = "SELECT COUNT(*) FROM projets WHERE createur_id = :userId";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function getNombreTaches($userId, $db) {
    $sql = "SELECT COUNT(*) FROM taches WHERE projet_id IN (SELECT id FROM projets WHERE createur_id = :userId)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function getNombreDocuments($userId, $db) {
    $sql = "SELECT COUNT(*) FROM documents WHERE id_projet IN (SELECT id FROM projets WHERE createur_id = :userId)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}


$total_projets = getNombreProjets($id, $db);
$total_taches = getNombreTaches($id, $db);
$total_documents = getNombreDocuments($id, $db);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Chercheur - TechInnova</title>
    <link rel="stylesheet" href="css/chercheur.css">
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
        <section class="welcome-section">
            <h2 id="welcome-text">Bienvenue sur votre tableau de bord, Chercheur !</h2>
        </section>

        <section class="stats">
            <div class="card">
                <h3><i class="fas fa-project-diagram"></i> Projets en cours</h3>
                <p id="projets-en-cours"><?php echo $total_projets; ?></p>
            </div>
            <div class="card">
                <h3><i class="fas fa-lightbulb"></i> Tâches soumises</h3>
                <p id="idees-soumises"><?php echo $total_taches; ?></p>
            </div>
            <div class="card">
                <h3><i class="fas fa-file-alt"></i> Documents ajoutés</h3>
                <p id="documents-ajoutes"><?php echo $total_documents; ?></p>
            </div>
        </section>

        <section class="actions">
            <button id="addProjectBtn">Ajouter un Nouveau Projet</button>
        </section>

        <!-- Modale pour ajouter un projet -->
        <div id="addProjectModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Ajouter un Nouveau Projet</h2>
                <form id="addProjectForm">
                    <label for="projectTitle">Titre :</label>
                    <input type="text" id="projectTitle" name="projectTitle" required>

                    <label for="projectDescription">Description :</label>
                    <textarea id="projectDescription" name="projectDescription" required></textarea>

                    <label for="projectDeadline">Date Limite :</label>
                    <input type="date" id="projectDeadline" name="projectDeadline" required>

                    <button type="submit">Ajouter le Projet</button>
                </form>
            </div>
        </div>

        <!-- Interface Profil -->
        <div id="profileInterface" class="profile-interface">
            <div class="profile-info">
                <img src="profile.jpg" alt="Image de Profil" class="profile-img">
                <div class="profile-details">
                    <h3>Nom: Jean Dupont</h3>
                    <p>Poste: Chercheur Principal</p>
                    <p>Email: jean.dupont@techinnova.com</p>
                    <p>Département: R&D</p>
                    <button id="logoutBtn">Se Déconnecter</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/JavaScrip/dashboard.js" defer></script>
</body>
</html>
