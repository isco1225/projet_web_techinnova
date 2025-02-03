<?php

require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require("C:/xampp/htdocs/projet_web/config/commandes.php");

$errorMessage = ''; // Variable pour stocker les messages d'erreur

if (isset($_POST['submit'])) {
    $utilisateur = new Utilisateur($db);
    $utilisateur->inscription(); // Appel à la méthode
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/styles_inscript.css">
    <title>Page D'Inscription</title>
</head>
<body>

    <?php if (!empty($errorMessage)): ?>
        <h5 style="color: red;"><?php echo $errorMessage; ?></h5>
    <?php endif; ?>

    <h1 class="titre"><a href="//localhost/projet_web/index.php">TECHIN<span>NOVA</span></a></h1>
    <div class="container">
        <div class="form-container sign-up">
            <form action="" method="post">
                <h1>Créer un compte</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <input type="text" name="nom" placeholder="Nom" value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>" required><br>
                <input type="text" name="prenom" placeholder="Prénom" value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>" required><br>
                <input type="email" name="mail" placeholder="Email" value="<?php echo htmlspecialchars($_POST['mail'] ?? ''); ?>" required><br>
                <input type="password" name="password" placeholder="Mot de passe" required><br>
                <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required><br>
                <button type="submit" id="submitBtn" name="submit">S'inscrire</button>
                <hr>
                <p>Vous avez déjà un compte ? <br><a class="lien_conn" href="page_connexion.php">Se connecter</a></p>
            </form>
        </div>
    </div>

</body>
</html>