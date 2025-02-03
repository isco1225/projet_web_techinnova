<?php
// Inclure le fichier de connexion à la base de données
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require("C:/xampp/htdocs/projet_web/config/commandes.php");

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom_tache = $_POST['nom_tache'];
    $description_tache = $_POST['description_tache'];
    $etat_tache = $_POST['etat_tache'];
    $membres_tache = $_POST['membres_tache']; // Cela sera un tableau d'IDs de membres
    $id_projet = $_POST['id_projet'];

    // Validation des données (à personnaliser selon vos besoins)
    if (empty($nom_tache) || empty($description_tache) || empty($etat_tache) || empty($membres_tache)) {
        echo "Tous les champs sont obligatoires.";
        exit;
    }

    // Insérer la tâche dans la base de données
    $sql_tache = "INSERT INTO taches (nom_tache, description_tache, etat_tache, id_projet) 
                  VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql_tache);
    $stmt->execute([$nom_tache, $description_tache, $etat_tache, $id_projet]);

    // Récupérer l'ID de la tâche insérée
    $id_tache = $db->lastInsertId();

    // Assigner les membres à la tâche
    $sql_membres = "INSERT INTO taches_membres (id_tache, id_membre) VALUES (?, ?)";
    $stmt_membres = $db->prepare($sql_membres);

    // Insérer les membres assignés
    foreach ($membres_tache as $id_membre) {
        $stmt_membres->execute([$id_tache, $id_membre]);
    }

    // Rediriger l'utilisateur vers une page de confirmation ou vers la page du projet
    header("Location: projet_as.php?id=$id_projet");
    exit;
}
?>