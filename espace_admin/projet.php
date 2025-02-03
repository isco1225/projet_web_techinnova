<?php
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require_once("C:/xampp/htdocs/projet_web/config/commandes.php");

session_start();
if (isset($_SESSION['utilisateur'])) {
    $utilisateur = $_SESSION['utilisateur'];
    $id = $utilisateur['id'] ?? null;
    $nom = $utilisateur['nom'] ?? null;
    $prenom = $utilisateur['prenom'] ?? null;
    $email = $utilisateur['email'] ?? null;
    $profil = $utilisateur['profil'] ?? null;
}

$projet_id = $_GET['id'] ?? null;

if ($projet_id) {
    try {
        // Récupérer les détails du projet
        $sql = "SELECT titre, description_projet, objectifs, etat, date_fin, createur_id FROM projets WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $projet_id, PDO::PARAM_INT);
        $stmt->execute();
        $projet = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$projet) {
            echo "Le projet demandé n'existe pas.";
            exit();
        }

        // Membres du projet
        $sql_members = "SELECT u.id AS id_utilisateur, u.nom, u.prenom 
                FROM utilisateurs u
                INNER JOIN membres_projet pm ON u.id = pm.id_utilisateur
                WHERE pm.id_projet = :id";

        $stmt_members = $db->prepare($sql_members);
        $stmt_members->bindParam(':id', $projet_id, PDO::PARAM_INT);
        $stmt_members->execute();
        $membres = $stmt_members->fetchAll(PDO::FETCH_ASSOC);

        // Documents associés au projet
        $sql_documents = "SELECT nom_fichier, categorie, date_upload FROM documents WHERE id_projet = :id";
        $stmt_documents = $db->prepare($sql_documents);
        $stmt_documents->bindParam(':id', $projet_id, PDO::PARAM_INT);
        $stmt_documents->execute();
        $documents = $stmt_documents->fetchAll(PDO::FETCH_ASSOC);

        // Tâches du projet
        $sql_tasks = "SELECT t.id AS tache_id, t.titre, t.description, t.statut, t.date_debut, t.date_echeance,
                    COALESCE(u.nom, 'Aucun utilisateur') AS utilisateur_nom,
                    COALESCE(u.prenom, '') AS utilisateur_prenom
              FROM tache t
              LEFT JOIN utilisateurs u ON t.utilisateur_id = u.id
              WHERE t.projet_id = :id";
        $stmt_tasks = $db->prepare($sql_tasks);
        $stmt_tasks->bindParam(':id', $projet_id, PDO::PARAM_INT);
        $stmt_tasks->execute();
        $taches = $stmt_tasks->fetchAll(PDO::FETCH_ASSOC);

        // Notifications (type demande_participation)
        $sql_notifications = "SELECT id, id_utilisateur, type, message, date_creation
                              FROM notifications
                              WHERE id_destinateur = :id
                              AND type = 'demande_participation'";
        $stmt_notifications = $db->prepare($sql_notifications);
        $stmt_notifications->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_notifications->execute();
        $notifications = $stmt_notifications->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des détails du projet : " . $e->getMessage());
    }
} else {
    echo "ID de projet invalide.";
    exit();
}

// Traitement de l'acceptation d'une demande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accepter'])) {
    $notification_id = $_POST['notification_id'];
    $projet_id = $_POST['projet_id'];

    try {
        // Récupérer l'utilisateur lié à la notification
        $sql_get_user = "SELECT id_utilisateur FROM notifications WHERE id = :id";
        $stmt_get_user = $db->prepare($sql_get_user);
        $stmt_get_user->bindParam(':id', $notification_id, PDO::PARAM_INT);
        $stmt_get_user->execute();
        $user = $stmt_get_user->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user_id = $user['id_utilisateur'];

            // Ajouter l'utilisateur comme membre du projet
            $sql_add_member = "INSERT INTO membres_projet (id_projet, id_utilisateur) VALUES (:projet_id, :user_id)";
            $stmt_add_member = $db->prepare($sql_add_member);
            $stmt_add_member->bindParam(':projet_id', $projet_id, PDO::PARAM_INT);
            $stmt_add_member->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_add_member->execute();
            

            // Supprimer la notification une fois traitée
            $sql_delete_notification = "DELETE FROM notifications WHERE id = :id";
            $stmt_delete_notification = $db->prepare($sql_delete_notification);
            $stmt_delete_notification->bindParam(':id', $notification_id, PDO::PARAM_INT);
            $stmt_delete_notification->execute();


            echo "L'utilisateur a été ajouté au projet avec succès.";
            $notifications = new Notifications($db);
            $notifications->notifier_demande_accepter($user_id, $id, $projet['titre']);
            header("Location: projet_as.php?id=$projet_id"); // Rediriger vers le projet avec l'ID
            exit;
        } else {
            echo "Utilisateur non trouvé pour cette notification.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout de l'utilisateur au projet : " . $e->getMessage();
    }
}

// Traitement du refus d'une demande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refuser'])) {
    $notification_id = $_POST['notification_id'];
    $id_destinateur = $_POST['dest_id'];


    try {
        // Supprimer la notification une fois refusée
        $sql_delete_notification = "DELETE FROM notifications WHERE id = :id";
        $stmt_delete_notification = $db->prepare($sql_delete_notification);
        $stmt_delete_notification->bindParam(':id', $notification_id, PDO::PARAM_INT);
        $stmt_delete_notification->execute();

        echo "La demande de participation a été refusée.";
        $notificationss = new Notifications($db);
        $notificationss->notifier_demande_refuser($id, $id_destinateur, $projet['titre']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors du refus de la demande : " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
    $notification_id = $_POST['notification_id'];

    try {
        // Supprimer la notification
        $sql_delete_notification = "DELETE FROM notifications WHERE id = :id";
        $stmt_delete_notification = $db->prepare($sql_delete_notification);
        $stmt_delete_notification->bindParam(':id', $notification_id, PDO::PARAM_INT);
        $stmt_delete_notification->execute();

        echo "La notification a été supprimée avec succès.";
        header("Location: projet_as.php?id=$id_projet"); // Rediriger vers le projet avec l'ID
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de la notification : " . $e->getMessage();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inserer_tache'])) {
    // Récupérer les données du formulaire

    $nom_tache = $_POST['nom_tache'];
    $description_tache = $_POST['description_tache'];
    $etat_tache = $_POST['etat_tache'];
    $membres_tache = $_POST['membres_tache']; // Tableau des membres sélectionnés
    $date_debut = date('Y-m-d');;
    $date_echeance = $_POST['date_echeance'];
    $id_projet = $_POST['id_projet'];

    // Validation des données
    if (empty($nom_tache) || empty($description_tache) || empty($etat_tache) || empty($membres_tache)) {
        echo "Tous les champs sont obligatoires.";
        exit;
    }

    // Validation des dates (facultatif, selon vos besoins)
    if (strtotime($date_debut) > strtotime($date_echeance)) {
        echo "La date de début ne peut pas être supérieure à la date d'échéance.";
        exit;
    }

    try {
        // Insérer la tâche dans la base de données
        $sql_tache = "INSERT INTO tache (titre, description, statut, projet_id, date_debut, date_echeance) 
                      VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql_tache);
        $stmt->execute([$nom_tache, $description_tache, $etat_tache, $id_projet, $date_debut, $date_echeance]);

        // Récupérer l'ID de la tâche insérée
        $id_tache = $db->lastInsertId();

        // Insérer chaque membre sélectionné dans la table tache
        foreach ($membres_tache as $membre_id) {
            // Si vous avez une colonne utilisateur_id pour chaque membre assigné, vous devez insérer à nouveau dans la table tache.
            $sql_add_member = "UPDATE tache
            SET utilisateur_id = ?
            WHERE utilisateur_id IS NULL AND projet_id = ?";
            $stmt = $db->prepare($sql_add_member);
            $stmt->execute([$membre_id, $projet_id]);
        }

        echo "La tâche a été ajoutée avec succès!";
        $notifications = new Notifications($db);
        $notifications->notifier_nouvelle_tache($id, $membre_id, $projet['titre']);

        header("Location: projet_as.php?id=$id_projet"); // Rediriger vers le projet avec l'ID
        exit;

    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_membre'])) {
    // Récupérez les variables
    $membre_id = $_POST['membre_id'];
    $projet_id = $_POST['projet_id'];

    // Assurez-vous que les variables ne sont pas vides
    if (!empty($membre_id) && !empty($projet_id)) {
        // Supprimer le membre du projet
        $stmt = $db->prepare("DELETE FROM membres_projet WHERE id_utilisateur = :membre_id AND id_projet = :projet_id");
        $stmt->bindParam(':membre_id', $membre_id, PDO::PARAM_INT);
        $stmt->bindParam(':projet_id', $projet_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Redirection après suppression (évite de soumettre à nouveau le formulaire si la page est rafraîchie)
            header("Location: projet_as.php?id=$projet_id"); // Rediriger vers le projet avec l'ID
            exit;
        } else {
            echo "Erreur : impossible de supprimer le membre.";
        }
    } else {
        echo "Erreur : identifiants du membre ou du projet manquants.";
    }
}

// Exemple de point de terminaison API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notifications = new Notifications($db);
    $notifications->envoyerNotificationsProjets();
    echo "Notifications envoyées.";
}

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);




?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Projet - <?= htmlspecialchars($projet['titre']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/projet.css">
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
            </ul>
        </nav>
    </div>

    <main id="content" class = "main-content">
        <a href="projetr&d.php" class="back-link">Retour</a>

        <h2>Détails du Projet : <?= htmlspecialchars($projet['titre']) ?></h2>
        <div class="project-grid">
            <section class="project-details">
                <h3><?= htmlspecialchars($projet['titre']) ?> <span>(<?= htmlspecialchars($projet['etat']) ?>)</span></h3>
                <p><strong>Details:</strong><br> <?= htmlspecialchars($projet['description_projet']) ?></p>
                <p><strong>Objectifs:</strong><br> <?= htmlspecialchars($projet['objectifs']) ?></p>
                <p></p>
            </section>

            <section class="project-members">
                <h3>Membres</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Rôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($membres)): ?>
                            <?php foreach ($membres as $membre): ?>
                                <tr>
                                    <td><?= htmlspecialchars($membre['nom']) ?></td>
                                    <td><?= htmlspecialchars($membre['prenom']) ?></td>
                                    <td>
                                        <?php if ($membre['id_utilisateur'] == $projet['createur_id']): ?>
                                            <strong>Responsable du projet</strong>
                                        <?php else: ?>
                                            Membre
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Aucun membre pour ce projet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>


            
        </div>




        <section class="project-documents">
            <h3>Documents associés</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nom du fichier</th>
                        <th>Catégorie</th>
                        <th>Date d'upload</th>
                        <th>Action</th> <!-- Nouvelle colonne pour l'action de téléchargement -->
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($documents)): ?>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <td><?= htmlspecialchars($document['nom_fichier']) ?></td>
                                <td><?= htmlspecialchars($document['categorie']) ?></td>
                                <td><?= htmlspecialchars($document['date_upload']) ?></td>
                                <td>
                                    <!-- Bouton de téléchargement -->
                                    <a href="uploads/<?= urlencode($document['nom_fichier']) ?>" download>
                                        <button type="button">Télécharger</button>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Aucun document associé à ce projet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="js/script.js" defer></script>
</body>
</html>
