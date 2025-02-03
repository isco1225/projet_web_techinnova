<?php
session_start();
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require("C:/xampp/htdocs/projet_web/config/commandes.php");


if (isset($_POST['btn_save'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $profil = $_POST['profil'];

    $user = new Admin($db);
    $user->modi_user($id, $nom, $prenom, $email, $profil);
}

if (isset($_POST['creer_compte'])){
    header("location: creation_compte.php");
    exit;
}

if (isset($_POST['delete_user'])) {
    $id = $_POST['user_id'];
    $user = new Admin($db);
    $user->supp_user($id);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;

}

$user = new Admin($db);
$users = $user->aff_users();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Gestion des utilisateurs</title>
</head>
<body>
    <div class="sidebar">
        <h2><span>TechIn</span>nova</h2>
        <nav>
            <ul>
                <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                <li><a href="projetr&d.php"><i class="fas fa-project-diagram"></i> Projets R&D</a></li>
                <li><a href="idees.php"><i class="fas fa-lightbulb"></i> Idées Innovantes</a></li>
                <li><a href="documents.php"><i class="fas fa-file-alt"></i> Documents</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
                <li><a href="statistiques.php"><i class="fas fa-chart-bar"></i> Statistiques</a></li>
                <li><a href="calendrier.php"><i class="fas fa-calendar-alt"></i> Calendrier</a></li>
                <li><a href="partenariat.php"><i class="fas fa-calendar-alt"></i> Partenariat</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="top">
            <h1>Gestion des utilisateurs</h1>
            <button class="btn btn_creercompte" id="btn_creercompte" name="creer_compte">+ Crée compte</button>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr data-user-id="<?= $user['id']; ?>">
                    <td data-editable="nom"><?= htmlspecialchars($user['nom']); ?></td>
                    <td data-editable="prenom"><?= htmlspecialchars($user['prenom']); ?></td>
                    <td data-editable="email"><?= htmlspecialchars($user['email']); ?></td>
                    <td data-editable="profil"><?= htmlspecialchars($user['profil']); ?></td>
                    <td><?= htmlspecialchars($user['date_creation']); ?></td>
                    <td class="action-buttons">
                        <button class="btn btn-edit" onclick="editRow(this)">Modifier</button>
                        <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <button type="submit" class="btn btn-delete" name="delete_user">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="js/script.js" defer></script>
</body>
</html>
