<?php

require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require("C:/xampp/htdocs/projet_web/config/commandes.php");

$errorMessage = ''; // Variable pour stocker les messages d'erreur

if (isset($_POST['submit'])) {
    $utilisateur = new Utilisateur($db);
    $utilisateur->connexion(); // Appel à la méthode
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles_conn.css">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
        <input type="email" name="mail" placeholder="mail">
        <input type="password" name="password" placeholder="mot de passe">
        <button type="submit" name="submit">Se connecter</button>
    </form>

</body>
</html>