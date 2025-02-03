<?php
require_once ("C:/xampp/htdocs/projet_web/config/connexion.php");
require_once ("C:/xampp/htdocs/projet_web/config/commandes.php");

session_start();


if (isset($_SESSION['utilisateur'])){
    $utilisateur = $_SESSION['utilisateur'];
    $createur_id = $utilisateur['id'];
    $nom = $utilisateur['nom'];

    if(isset($_POST['submit'])){
        $projets = new Chercheur($db);
        $projet_id = $projets->creer_projets($createur_id); // Récupérer l'ID du projet créé
        $projets->ajouter_membres($projet_id, $createur_id); //
        header("location: add_doc.php?projet_id=" . $projet_id); // Rediriger vers add_doc.php avec l'ID du projet
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles_ajout_projet.css">
    <title>Document</title>
</head>
<body>
    <header>
        <a href="projetr&d.php" class="back-to-dashboard"><i class="fas fa-arrow-left"></i> Retour</a>
    </header>

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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', (event) => {
                    console.log("Le formulaire est soumis avec succès.");
                });
            } else {
                console.error("Formulaire introuvable.");
            }
        });
    </script>

</body>
</html>