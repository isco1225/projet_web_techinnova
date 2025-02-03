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


if (isset($_POST['btn_demande'])) {
    $projet_id = $_POST['projet_id'];
    $createur_id = $_POST['createur_id'];

    if ($createur_id && $id) {
        try {
            // Vérifier si une demande existe déjà
            $sql_check = "SELECT COUNT(*) FROM demandes 
                          WHERE id_utilisateur = :id_utilisateur 
                          AND id_projet = :id_projet";
            $stmt_check = $db->prepare($sql_check);
            $stmt_check->bindParam(':id_utilisateur', $id, PDO::PARAM_INT);
            $stmt_check->bindParam(':id_projet', $projet_id, PDO::PARAM_INT);
            $stmt_check->execute();
            $already_requested = $stmt_check->fetchColumn();

            if ($already_requested > 0) {
                echo "<div class='demande_erreur'><p>Vous avez déjà envoyé une demande pour ce projet.</p></div>";
            } else {
                // Ajouter une demande à la table demandes
                $sql_insert = "INSERT INTO demandes (id_projet, id_utilisateur, date_creation) 
                               VALUES (:id_projet, :id_utilisateur, NOW())";
                $stmt_insert = $db->prepare($sql_insert);
                $stmt_insert->bindParam(':id_projet', $projet_id, PDO::PARAM_INT);
                $stmt_insert->bindParam(':id_utilisateur', $id, PDO::PARAM_INT);
                $stmt_insert->execute();

                echo "<div class='demande_envoyer'><p>Demande envoyée avec succès :)</p></div>";
                $notifications = new Notifications($db);
                $notifications->notifier_demande($id, $createur_id, $projet['titre']);
                $notifications->notifier_demande_self($id, $id, $projet['titre']);
            }
        } catch (PDOException $e) {
            echo "<p>Erreur lors de l'envoi de la demande : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Impossible d'envoyer la demande. Informations manquantes.</p>";
    }
}



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
    <header class="navbar">
        <div class="logo">
            <h1><span>TECHIN</span>NOVA</h1>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="acceuil.php" data-page="accueil">Accueil</a></li>
                <li><a href="projetr&d.php" data-page="projets" class="active">Projets R&D</a></li>
                <li><a href="idee.php" data-page="idees">Idées innovantes</a></li>
                <li><a href="doc.php" data-page="documents">Documents</a></li>
                <li><a href="calendrier.php" data-page="calendrier">Calendrier</a></li>
            </ul>
        </nav>
        <div class="user-icons">
            <div class="user-icon">
                <a href="notification.php"><i class="fas fa-bell"></i></a>
            </div>
            <div class="user-icon">
                <a href="profil.php"><i class="fas fa-user-circle"></i></a>
            </div>
        </div>
    </header>

    <main id="content">
        <h2>Détails du Projet : <?= htmlspecialchars($projet['titre']) ?></h2>
        <form method="POST" action="">
            <input type="hidden" name="projet_id" value="<?= htmlspecialchars($projet_id) ?>">
            <input type="hidden" name="createur_id" value="<?= htmlspecialchars($projet['createur_id']) ?>">
            <button type="submit" name="btn_demande" class="view-details" id="btn_demande">Envoyer une demande</button>
        </form>
        
        <div class="demande-envoye-erreur" style="display: none; color: red; margin-top: 10px;">
            Vous avez déjà envoyé votre demande.
        </div>

        <div class="project-grid">
            <section class="project-details">
                <a href="projetr&d.php" class="back-link">Retour</a>

                <h3>Détails</h3>
                <p><strong>Titre:</strong> <?= htmlspecialchars($projet['titre']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($projet['description_projet']) ?></p>
                <p><strong>Objectifs:</strong><br> <?= htmlspecialchars($projet['objectifs']) ?></p>
                <p><strong>État:</strong> <?= htmlspecialchars($projet['etat']) ?></p>
                <p><strong>Date de fin:</strong> <?= htmlspecialchars($projet['date_fin']) ?></p>
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
                                        <?php elseif ($membre['id_utilisateur'] == $id): ?>
                                            Membre (vous)
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
            <?php if (!empty($documents)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom du fichier</th>
                            <th>Catégorie</th>
                            <th>Date d'upload</th>
                            <th>Télécharger</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <td><?= htmlspecialchars($document['nom_fichier']) ?></td>
                                <td><?= htmlspecialchars($document['categorie']) ?></td>
                                <td><?= htmlspecialchars($document['date_upload']) ?></td>
                                <td>
                                    <a href="Téléchargements/<?= urlencode($document['nom_fichier']) ?>" download>📥 Télécharger</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun document disponible pour ce projet.</p>
            <?php endif; ?>
        </section>
    </main>



</body>
</html>
