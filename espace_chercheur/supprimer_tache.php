<?php
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");

if (isset($_GET['id_tache']) && is_numeric($_GET['id_tache'])) {
    $id_tache = intval($_GET['id_tache']); // Assurer que c'est un entier

    // Vérifier si la tâche existe
    $stmt = $db->prepare("SELECT id FROM tache WHERE id = :id_tache");
    $stmt->bindParam(":id_tache", $id_tache, PDO::PARAM_INT);
    $stmt->execute();
    $tache = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($tache) {
        // Supprimer la tâche
        $delete_stmt = $db->prepare("DELETE FROM tache WHERE id = :id_tache");
        $delete_stmt->bindParam(":id_tache", $id_tache, PDO::PARAM_INT);

        if ($delete_stmt->execute()) {
            echo "<script>alert('Tâche supprimée avec succès.'); window.location.href='liste_taches.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de la suppression.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Tâche introuvable.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('ID invalide.'); window.history.back();</script>";
}
?>
