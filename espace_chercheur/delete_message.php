<?php
session_start();
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_message'])) {
    $id_message = intval($_POST['id_message']);
    $id_utilisateur = $_SESSION['utilisateur']['id'] ?? null;

    if (!$id_utilisateur) {
        echo json_encode(["success" => false, "error" => "Utilisateur non connecté"]);
        exit;
    }

    // Vérifier si le message appartient à l'utilisateur connecté
    $stmt = $db->prepare("SELECT * FROM messages WHERE id = ? AND id_utilisateur = ?");
    $stmt->execute([$id_message, $id_utilisateur]);

    if ($stmt->rowCount() > 0) {
        // Supprimer le message
        $deleteStmt = $db->prepare("DELETE FROM messages WHERE id = ?");
        $deleteStmt->execute([$id_message]);

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Message introuvable ou non autorisé"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Requête invalide"]);
}
?>
