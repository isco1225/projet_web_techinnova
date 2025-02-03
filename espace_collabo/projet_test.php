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

        $createur_id = $projet['createur_id'];

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
                              AND type = 'nouvelle_tache'";
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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
    $notification_id = $_POST['notification_id'];

    try {
        // Supprimer la notification
        $sql_delete_notification = "DELETE FROM notifications WHERE id = :id";
        $stmt_delete_notification = $db->prepare($sql_delete_notification);
        $stmt_delete_notification->bindParam(':id', $notification_id, PDO::PARAM_INT);
        $stmt_delete_notification->execute();

        echo "La notification a été supprimée avec succès.";
        header("Location: projet_test.php?id=$id_projet"); // Rediriger vers le projet avec l'ID
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de la notification : " . $e->getMessage();
    }
}



// Exemple de point de terminaison API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notifications = new Notifications($db);
    $notifications->envoyerNotificationsProjets();
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
    <link rel="stylesheet" href="css/projet_test.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <h1><span>TECHIN</span>NOVA</h1>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="acceuil.php" data-page="accueil">Accueil</a></li>
                <li><a href="projetr&d.php" data-page="projets">Projets R&D</a></li>
                <li><a href="idee.php" data-page="idees">Idées innovantes</a></li>
                <li><a href="doc.php" data-page="documents">Documents</a></li>
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
        

        <div class="principal">
            <nav>
                <ul>
                    <li><a href="#">Details</a></li>
                    <li><a href="#">Notifications</a></li>
                    <li><a href="#">Discussions</a></li>
                    <li><a href="#">Tâches</a></li>
                    <li><a href="#">Membres</a></li>
                    <li><a href="#">Documents</a></li>
                </ul>
            </nav>

            <section class="project-details">
                <a href="projetr&d.php" class="back-link">Retour</a>
                <h3>Détails</h3>
                <p><strong>Titre:</strong> <?= htmlspecialchars($projet['titre']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($projet['description_projet']) ?></p>
                <p><strong>Objectifs:</strong><br> <?= htmlspecialchars($projet['objectifs']) ?></p>
                <p><strong>État:</strong> <?= htmlspecialchars($projet['etat']) ?></p>
                <p><strong>Date de fin:</strong> <?= htmlspecialchars($projet['date_fin']) ?></p>
            </section>

            <section class="project-notifications">
                <?php foreach ($notifications as $notification): ?>
                    <div class="ticket">
                        <p><strong><?= htmlspecialchars($notification['date_creation']) ?>:</strong> <?= htmlspecialchars($notification['message']) ?></p>
                        <form method="POST" action="">
                            <input type="hidden" name="notification_id" value="<?= htmlspecialchars($notification['id']) ?>">
                            <?php if ($notification['type'] === 'demande_participation'): ?>
                                <input type="hidden" name="dest_id" value="<?= htmlspecialchars($notification['id_destinateur']) ?>">
                                <input type="hidden" name="projet_id" value="<?= htmlspecialchars($projet_id) ?>">
                                <button type="submit" name="accepter">Accepter</button>
                                <button type="submit" name="refuser">Refuser</button>
                            <?php else: ?>
                                <button type="submit" name="supprimer" class="btn_supp">Supprimer</button>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($notifications)): ?>
                    <p>Aucune notification de participation.</p>
                <?php endif; ?>
            </section>


            <section class="project-membres">
                <h3>Membres</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Rôle</th>
                            <th>Action</th>
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
                                    <td>
                                        <?php if ($membre['id_utilisateur'] != $projet['createur_id']): ?>
                                            <form method="POST" action="">
                                                <input type="hidden" name="membre_id" value="<?= htmlspecialchars($membre['id_utilisateur']) ?>">
                                                <input type="hidden" name="projet_id" value="<?= htmlspecialchars($projet_id) ?>">
                                            </form>
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

            <section class="project-commentaires">
                <div class="chat-container">
                    <h2>Discussion du Projet</h2>
                    <div class="chat-box" id="chat-box">
                        <!-- Les messages seront chargés ici -->
                    </div>

                    <!-- Menu contextuel (initialement caché) -->
                    <div id="context-menu" style="display: none; position: absolute;">
                        <ul style="list-style: none; margin: 0; padding: 0;">
                            <li><a href="#" id="delete-message" style="color: red;"><button>Supprimer</button></a></li>
                        </ul>
                    </div>

                    <form id="chat-form" method="post">
                        <input type="hidden" name="id_projet" value="<?= htmlspecialchars($id_projet) ?>">
                        <textarea name="message" id="message" placeholder="Écrivez votre message..." required></textarea>
                        <button type="submit" name="envoyer_message">Envoyer</button>
                    </form>
                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function () {
                        function loadMessages() {
                            let chatBox = $("#chat-box");
                            let scrollPos = chatBox.scrollTop(); // Enregistrer la position actuelle

                            chatBox.load("http://localhost/projet_web/espace_chercheur/load_messages.php?id_projet=<?= $projet_id ?>&id_createur=<?= $createur_id?>?>", function () {
                                chatBox.scrollTop(scrollPos); // Restaurer la position après chargement
                            });
                        }

                        // Charger les messages au démarrage
                        loadMessages();

                        // Rafraîchir les messages toutes les 5 secondes sans déplacer la position
                        setInterval(loadMessages, 5000);

                        // Envoi du message en AJAX
                        $("#chat-form").submit(function (e) {
                            e.preventDefault();
                            let message = $("#message").val().trim();

                            if (message !== "") {
                                $.post("http://localhost/projet_web/espace_chercheur/send_message.php", {
                                    id_projet: <?= $projet_id ?>,
                                    message: message
                                }, function (data) {
                                    $("#chat-box").append(data);  // Ajouter le message immédiatement
                                    $("#message").val("");  // Vider le champ de texte
                                    
                                    // Faire défiler uniquement si l'utilisateur est déjà en bas
                                    let chatBox = $("#chat-box");
                                    if (chatBox.scrollTop() + chatBox.innerHeight() >= chatBox[0].scrollHeight - 10) {
                                        chatBox.scrollTop(chatBox[0].scrollHeight);
                                    }
                                });
                            }
                        });

                        // Clic droit pour afficher le menu contextuel
                        let selectedMessageId = null;

                        $(document).on("contextmenu", ".message-user", function (e) {
                            e.preventDefault();  // Empêche les actions par défaut du clic droit
                            console.log("Clic droit détecté sur un message utilisateur !");
                            
                            selectedMessageId = $(this).data("id"); // Récupérer l'ID du message
                            console.log("Message sélectionné :", selectedMessageId);

                            $("#context-menu").css({
                                top: e.pageY + "px",
                                left: e.pageX + "px"
                            }).show();  // Afficher le menu à la position du curseur
                        });

                        // Cacher le menu si on clique ailleurs
                        $(document).click(function () {
                            $("#context-menu").hide();
                        });

                        // Supprimer le message après confirmation
                        $("#delete-message").click(function () {
                            if (selectedMessageId) {
                                // Afficher la boîte de confirmation
                                let confirmDelete = confirm("Êtes-vous sûr de vouloir supprimer ce message ? Cette action est irréversible.");

                                if (confirmDelete) {
                                    // Si l'utilisateur confirme, procéder à la suppression
                                    $.post("http://localhost/projet_web/espace_chercheur/delete_message.php", { id_message: selectedMessageId }, function (response) {
                                        if (response.success) {
                                            $(`.message[data-id="${selectedMessageId}"]`).remove();  // Retirer le message
                                        } else {
                                            alert("Erreur lors de la suppression.");
                                        }
                                    }, "json");
                                }
                            }
                        });
                    });
                </script>
            </section>




            <!-- Section Tâches -->
            <section class="project-taches">
                <h3>Liste des Tâches</h3>


                <!-- Tableau des tâches existantes -->
                <table>
                    <thead>
                        <tr>
                            <th>Nom de la Tâche</th>
                            <th>Description</th>
                            <th>État</th>
                            <th>Membres Assignés</th>
                            <th>Date de Début</th>
                            <th>Date d'Échéance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($taches)): ?>
                            <?php foreach ($taches as $tache): ?>
                                <tr>
                                    <td><?= htmlspecialchars($tache['titre']) ?></td>
                                    <td><?= htmlspecialchars($tache['description']) ?></td>
                                    <td><?= htmlspecialchars($tache['statut']) ?></td>
                                    <td><?= htmlspecialchars($tache['utilisateur_nom']) ."".($tache['utilisateur_prenom']) ?></td>
                                    <td><?= htmlspecialchars($tache['date_debut']) ?></td>
                                    <td><?= htmlspecialchars($tache['date_echeance']) ?></td>
                                    <td>
                                        <a href="modifier_tache.php?id_tache=<?= htmlspecialchars($tache['tache_id']) ?>">Modifier</a>
                                        <a href="supprimer_tache.php?id_tache=<?= htmlspecialchars($tache['tache_id']) ?>">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">Aucune tâche pour ce projet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

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
                                        <a href="//localhost/projet_web/espace_chercheur/uploads/<?= urlencode($document['nom_fichier']) ?>" download>
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

            
        </div>
    </main>
    <script src="js/script.js" defer></script>
</body>
</html>
