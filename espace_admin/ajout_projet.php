<?php
require_once ("C:/xampp/htdocs/projet_web/config/connexion.php");
require_once ("C:/xampp/htdocs/projet_web/config/commandes.php");

session_start();

if (isset($_SESSION['utilisateur'])){
    $utilisateur = $_SESSION['utilisateur'];
    $createur_id = $utilisateur['id'];
    $nom = $utilisateur['nom'];

    if(isset($_POST['submit'])){
        $projet = new Chercheur($db);
        $projet->creer_projets($createur_id);
        header("Location: projetr&d.php");
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/ajout_projet.css">
    <title>Document</title>
</head>
<body>
    
    <div class="form-container">
        <h2>Ajouter un Nouveau Projet</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="titre">Titre du projet :</label>
                <input type="text" id="titre" name="titre" placeholder="Entrez le titre du projet" required>
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" rows="4" placeholder="Entrez une description du projet" required></textarea>
            </div>

            <div class="form-group">
                <label for="objectifs">Objectifs du projet :</label>
                <textarea id="objectifs" name="objectifs" rows="4" placeholder="Entrez les objectifs du projet" required></textarea>
            </div>

            <div class="form-group">
                <label for="echeance">Date de fin :</label>
                <input type="date" id="echeance" name="date_fin" required>
            </div>

            <div class="form-group">
                <button type="submit" name="submit" class="btn">Ajouter le projet</button>
            </div>
        </form>
    </div>

</body>
</html>