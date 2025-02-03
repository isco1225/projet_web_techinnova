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

// Vérifiez si le titre est transmis via GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_projet = $_GET['id']; // Récupère le titre depuis l'URL

    try {
        // Préparer la requête pour récupérer les données du projet
        $sql = "SELECT * FROM projets WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id_projet, PDO::PARAM_STR); // Associe la variable :titre à $titre
        $stmt->execute(); // Exécute la requête

        // Récupérer les données du projet
        $projet = $stmt->fetch(PDO::FETCH_ASSOC);
        $createur_id = $projet['createur_id'];
        $titre = $projet['titre'];
        $description = $projet['description_projet'];
        $date_fin = $projet['date_fin'];
        $etat = $projet['etat'];
    } catch (PDOException $e) {
        // Gérer les erreurs liées à la base de données
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Titre non spécifié ou vide.";
}


if (isset($_POST['btn_demande'])) {
    try {
        $sql = "INSERT INTO notifications (id_utilisateur, id_destinateur, type, message) VALUES (:id_utilisateur, :id_destinateur, :type, :message)";
        $stmt = $db->prepare($sql);
        $message = "Un utilisateur souhaite participer au projet : " . $titre;
        $type = "demande_participation";
        $stmt->bindParam(':id_utilisateur', $id);
        $stmt->bindParam(':id_destinateur', $createur_id);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':message', $message);
        $stmt->execute();

        // Vérifier si l'insertion a réussi
        if ($stmt->rowCount() > 0) {
            echo "Notification envoyée avec succès.";
        } else {
            echo "Échec de l'envoi de la notification.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Projet - TechInnova</title>
    <link rel="stylesheet" href="css/detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Barre de navigation -->
    <header class="navbar">
        <div class="logo">
            <h1><span>TECHIN</span>NOVA</h1>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="acceuil.php">Accueil</a></li>
                <li><a href="projetr&d.php">Projets R&D</a></li>
                <li><a href="idee.php">Idées innovantes</a></li>
                <li><a href="doc.php">Documents</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenu principal -->
    <main id="content">
        <h2>Détails du Projet : <?= $titre ?></h2>
        <section class="project-details">
            <h3>Description</h3>
            <p> <?= $description ?> </p>

            <h3>Avancement</h3>
            <p><?= $etat ?></p>

            <h3>Membres du projets</h3>
            <p>Jean Dupont, Claire Fontaine, Marc Durand</p>

            <h3>Échéance</h3>
            <p><?= $date_fin ?></p>

            <h3>Voulez vous participer à ce projet ?</h3>

        </section>

        <div class="overlay"></div>

        <div class="boite">
            <h3>Demande envoyée</h3>
        </div>

        <div class="action-buttons">
            <form action="" method="post">
                <button type="submit" id="btn_demande" name="btn_demande">Envoyer ma demande</button>
            </form>
            <a href="projetr&d.php"><button>Retour</button></a>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnDemande = document.getElementById('btn_demande');
            const boite = document.querySelector('.boite');
            const overlay = document.querySelector('.overlay');

            // Affiche la boîte et l'overlay au clic sur le bouton
            btnDemande.addEventListener('click', () => {
                boite.style.display = 'block';
                overlay.style.display = 'block';
            });

            // Cacher la boîte et l'overlay en cliquant sur l'overlay
            overlay.addEventListener('click', () => {
                boite.style.display = 'none';
                overlay.style.display = 'none';
            });
        });
    </script>
</body>
</html>
