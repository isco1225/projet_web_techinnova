<?php
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['utilisateur'])) {
    $utilisateur = $_SESSION['utilisateur'];
    $id_utilisateur_connecte = $utilisateur['id'] ?? null;
}

// Fonction pour formater l'heure du message
function formatTime($date_envoi) {
    $timestamp = strtotime($date_envoi);
    $now = time();
    $diff = $now - $timestamp;

    if ($diff < 86400) { // Moins de 24h, on affiche l'heure
        return date('H:i', $timestamp);
    } else { // Plus de 24h, on affiche le nombre de jours
        return floor($diff / 86400) . 'j';
    }
}

// Vérifier si 'id_projet' et 'id_createur' sont présents dans l'URL
if (isset($_GET['id_projet']) && isset($_GET['id_createur'])) {
    $id_projet = intval($_GET['id_projet']); // Récupérer l'id du projet
    $id_createur = intval($_GET['id_createur']); // Récupérer l'id du créateur
} else {
    echo "Erreur : id_projet ou id_createur non défini.";
    exit; // Arrêter le script si les paramètres manquent
}

// Récupérer les messages du projet
$sql = "SELECT m.id, m.message, m.date_envoi, m.id_utilisateur, u.nom, u.prenom
        FROM messages m
        INNER JOIN utilisateurs u ON m.id_utilisateur = u.id
        WHERE m.id_projet = :id_projet
        ORDER BY m.date_envoi ASC";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Affichage des messages
foreach ($messages as $msg) {
    $formattedTime = formatTime($msg['date_envoi']);
    $isUserMessage = ($msg['id_utilisateur'] == $id_utilisateur_connecte) ? 'message-user' : 'message-other';

    echo '<div class="message ' . $isUserMessage . '" data-id="' . $msg['id'] . '">';
    
    // Si ce n'est pas l'utilisateur connecté, on affiche le nom de l'utilisateur
    if ($msg['id_utilisateur'] != $id_utilisateur_connecte) {
        // Si le message est envoyé par le créateur du projet
        if ($msg['id_utilisateur'] == $id_createur) {
            // Afficher (Respo) à côté du nom du créateur
            echo '<strong>' . htmlspecialchars($msg['nom'] . ' ' . $msg['prenom']) . ' </strong><span>(Respo)</span><br>';
        } else {
            // Afficher normalement le nom de l'utilisateur
            echo '<strong>' . htmlspecialchars($msg['nom'] . ' ' . $msg['prenom']) . '</strong><br>';
        }
    }
    // Affichage du message
    echo htmlspecialchars($msg['message']) . ' <small>(' . $formattedTime . ')</small>';
    echo '</div>';
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<style>
    .messages-container {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    max-width: max-content;
}

.message {
    padding: 0px 5px 5px;
    font-size: 20px;
    border-radius: 10px;
    margin: 5px 0;
    max-width: 60%;
    word-wrap: break-word;
    position: relative;
    cursor: default;
    max-width: max-content;

}
.message:hover{
    box-shadow: 0px 0px 5px #0000005D;
    margin-left: 5px;
}
.message-user {
    background-color: #d4edda;
    color: black;
    align-self: flex-end;
}

.message-other {
    background-color: #f8d7da;
    color: black;
    align-self: flex-start;
}


#context-menu button {
    background-color: #d13b22;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);
    border-radius: 5px;
    cursor: default;
    border: none;
    padding: 5px;
    width: 100%;
    text-align: left;
    transition: all 0.1s ease-in-out;
}
#context-menu a{
    cursor: default;
}
#context-menu button:hover {
    background-color: #FB4322FF;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.51);
}

.message strong{
    font-size: 10px;
    font-weight: 900;
    transform: translateY(-50%);

}
.message span{
    font-size: 9px;
}
    p {
        margin: 0;
    }
</style>
</body>
</html>