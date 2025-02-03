<?php
session_start();
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require("C:/xampp/htdocs/projet_web/config/commandes.php");

// Récupérer les informations de l'utilisateur connecté
if (isset($_SESSION['utilisateur'])) {
    $utilisateur = $_SESSION['utilisateur'];
    $id = $utilisateur['id'] ?? null;
    $nom = $utilisateur['nom'] ?? null;
    $prenom = $utilisateur['prenom'] ?? null;
    $email = $utilisateur['email'] ?? null;
    $profil = $utilisateur['profil'] ?? null;
} else {
    header("Location: http://localhost/projet_web/index.php");
    exit;
}

$userId = $id;
$sql = "SELECT nom, email, profil FROM utilisateurs WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch();

// Gérer la modification du mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current-password'];
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($newPassword === $confirmPassword) {
        // Vérifier le mot de passe actuel
        $checkSql = "SELECT mot_de_passe FROM utilisateurs WHERE id = :id";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->execute([':id' => $userId]);
        $userPassword = $checkStmt->fetchColumn();

        if (password_verify($currentPassword, $userPassword)) {
            // Mettre à jour le mot de passe
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSql = "UPDATE utilisateurs SET mot_de_passe = :new_password WHERE id = :id";
            $updateStmt = $db->prepare($updateSql);
            $updateStmt->execute([
                ':new_password' => $hashedPassword,
                ':id' => $userId,
            ]);
            $successMessage = "Mot de passe mis à jour avec succès.";
        } else {
            $errorMessage = "Mot de passe actuel incorrect.";
        }
    } else {
        $errorMessage = "Les nouveaux mots de passe ne correspondent pas.";
    }
}

// Déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: http://localhost/projet_web/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur - TechInnova</title>
    <link rel="stylesheet" href="css/profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-content">
        <header>
            <h1>Mon Profil</h1>
            <a href="acceuil.php" class="back-to-dashboard"><i class="fas fa-arrow-left"></i> Retour au Tableau de bord</a>
        </header>

        <!-- Consultation du profil -->
        <section class="profile-info">
            <h2>Informations Personnelles</h2>
            <div class="info-group">
                <label>Nom :</label>
                <p><?= htmlspecialchars($nom) ?></p>
            </div>
            <div class="info-group">
                <label>Email :</label>
                <p><?= htmlspecialchars($email) ?></p>
            </div>
            <div class="info-group">
                <label>Rôle :</label>
                <p><?= htmlspecialchars($profil) ?></p>
            </div>
        </section>

        <!-- Afficher les messages de succès ou d'erreur -->
        <?php if (isset($successMessage)): ?>
            <p class="success-message"><?= htmlspecialchars($successMessage) ?></p>
        <?php elseif (isset($errorMessage)): ?>
            <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <!-- Modification des informations -->
        <section class="profile-edit">
            <h2>Modifier mon Mot de Passe</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="current-password">Mot de passe actuel :</label>
                    <input type="password" id="current-password" name="current-password" required>
                </div>
                <div class="form-group">
                    <label for="new-password">Nouveau mot de passe :</label>
                    <input type="password" id="new-password" name="new-password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirmer le nouveau mot de passe :</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <button type="submit" class="save-btn"><i class="fas fa-save"></i> Enregistrer</button>
                <a href="?action=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>

            </form>
        </section>
    </div>
</body>
</html>
