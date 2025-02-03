<?php

require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require("C:/xampp/htdocs/projet_web/config/commandes.php");

$errorMessage = ''; // Variable pour stocker les messages d'erreur

if (isset($_POST['submit'])) {
    if (!isset($_POST['profil'])) {
        $errorMessage = "Veuillez sélectionner un profil.";
    } else {
        $utilisateur = new Utilisateur($db);
        $utilisateur->inscription(); // Appel à la méthode
        header("location: users.php");
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/creation_compte.css">
    <title>Page D'Inscription</title>
</head>
<body>
    <script>
        const form = document.getElementById('inscriptionForm');
        const submitBtn = document.getElementById('submitBtn');
        const radios = form.querySelectorAll('input[name="profil"]');

        // Activer le bouton si un radio est sélectionné
        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                submitBtn.disabled = false;
            });
        });

        // Désactiver le bouton si aucun radio n'est sélectionné
        form.addEventListener('reset', () => {
            submitBtn.disabled = true;
        });
    </script>
    <!-- Affichage du message d'erreur -->
    <?php if (!empty($errorMessage)): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
    <a href="users.php" class="retour">Retour</a>


    <div class="container">
        <h1>Creation de compte</h1>
        <form action="" method="POST" id="inscriptionForm">
            <input type="text" name="nom" placeholder="Nom" required><br>
            <input type="text" name="prenom" placeholder="Prénom" required><br>
            <input type="email" name="mail" placeholder="Email" required><br>

            <label>Choisissez un profil :</label><br>
            <input type="radio" name="profil" value="Administrateur"> Administrateur<br>
            <input type="radio" name="profil" value="Collaborateur"> Collaborateur<br>
            <input type="radio" name="profil" value="Chercheur"> Chercheur<br>

            <input type="password" name="password" placeholder="Mot de passe" required><br>
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required><br>

            <button type="submit" name="submit">S'inscrire</button>
        </form>
    </div>

</body>
</html>