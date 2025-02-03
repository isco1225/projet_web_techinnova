<?php
session_start();
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");

if (isset($_SESSION['utilisateur'])) {
    $utilisateur = $_SESSION['utilisateur'];
    $id = $utilisateur['id'] ?? null;
    $nom = $utilisateur['nom'] ?? null;
    $prenom = $utilisateur['prenom'] ?? null;
    $email = $utilisateur['email'] ?? null;
    $profil = $utilisateur['profil'] ?? null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_projet'], $_POST['message'])) {
    $id_projet = intval($_POST['id_projet']);
    $id_utilisateur = $id; // Supposons que l'utilisateur est connecté
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $sql = "INSERT INTO messages (id_projet, id_utilisateur, message) VALUES (:id_projet, :id_utilisateur, :message)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->execute();

        // Récupérer le nom de l'utilisateur
        $queryUser = "SELECT nom, prenom FROM utilisateurs WHERE id = :id_utilisateur";
        $stmtUser = $db->prepare($queryUser);
        $stmtUser->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmtUser->execute();
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        // Affichage du message envoyé instantanément
        echo '<p><strong>' . htmlspecialchars($user['nom'] . ' ' . $user['prenom']) . ':</strong> ' . htmlspecialchars($message) . ' <small>(Maintenant)</small></p>';
    }
}
?>
