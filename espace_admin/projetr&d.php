<?php
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");
require_once("C:/xampp/htdocs/projet_web/config/commandes.php");

session_start();

if (isset($_SESSION['utilisateur'])) {
    $utilisateur = $_SESSION['utilisateur'];
    $user_id = $utilisateur['id'] ?? null;
    $nom = $utilisateur['nom'] ?? null;
    $prenom = $utilisateur['prenom'] ?? null;
} else {
    die("Vous devez être connecté pour accéder à cette page.");
}

// Récupérer le terme de recherche et le filtre
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Action de suppression
if (isset($_GET['delete_project_id'])) {
    $delete_project_id = $_GET['delete_project_id'];
    try {
        // Supprimer d'abord les messages associés au projet
        $sql_delete_messages = "DELETE FROM messages WHERE id_projet = :project_id";
        $stmt_messages = $db->prepare($sql_delete_messages);
        $stmt_messages->bindParam(':project_id', $delete_project_id, PDO::PARAM_INT);
        $stmt_messages->execute();
        
        // Puis supprimer le projet
        $sql_delete_project = "DELETE FROM projets WHERE id = :project_id";
        $stmt_project = $db->prepare($sql_delete_project);
        $stmt_project->bindParam(':project_id', $delete_project_id, PDO::PARAM_INT);
        $stmt_project->execute();
        
        header("Location: projetr&d.php?filter=tableau");
    } catch (PDOException $e) {
        die("Erreur lors de la suppression du projet : " . $e->getMessage());
    }
}


try {
    // Requête pour récupérer les projets personnels
    if ($filter == 'my_projects') {
        $sql_personnels = "SELECT id, titre, description_projet, etat, date_fin 
                           FROM projets 
                           WHERE createur_id = :user_id";
        if (!empty($search)) {
            $sql_personnels .= " AND (titre LIKE :search OR description_projet LIKE :search)";
        }
        $sql_personnels .= " ORDER BY date_creation ASC";
        $stmt_personnels = $db->prepare($sql_personnels);
        $stmt_personnels->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $stmt_personnels->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
        $stmt_personnels->execute();
        $projets_personnels = $stmt_personnels->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les projets globaux
    $sql = "SELECT id, titre, description_projet, etat, date_fin FROM projets";
    if (!empty($search)) {
        $sql .= " WHERE titre LIKE :search OR description_projet LIKE :search";
    }
    $sql .= " ORDER BY date_creation ASC";
    $stmt = $db->prepare($sql);
    if (!empty($search)) {
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
    }
    $stmt->execute();
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des projets : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets R&D - TechInnova</title>
    <link rel="stylesheet" href="css/projets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

<!-- Contenu principal -->
<main id="content" class="main-content">
    <h2>Projets R&D</h2>
    <p class="intro">Voici la liste des projets auxquels vous avez accès.</p>

    <!-- Barre de recherche -->
    <div class="search-bar">
        <form method="GET" action="projetr&d.php">
            <input type="text" name="search" placeholder="Rechercher un projet..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <button id="btn_add_projet" class="view-details">Ajouter projet</button>
    <button id="btn_global_projects" class="view-details">Projets globaux</button>
    <button id="btn_my_projects" class="view-details">Mes projets</button>
    <button id="btn_tableau" class="view-details">Tableau</button>

    <!-- Affichage des projets -->
    <?php if ($filter == 'my_projects'): ?>
        <h2>Vos projets personnels</h2>
        <section class="projects">
            <?php if (!empty($projets_personnels)): ?>
                <?php foreach ($projets_personnels as $projet): ?>
                    <article class="project">
                        <h1><?= htmlspecialchars($projet['titre']) ?></h1>
                        <p><?= htmlspecialchars($projet['description_projet']) ?></p>
                        <?php
                        $sql_check_assign = "SELECT COUNT(*) FROM membres_projet WHERE id_projet = :projet_id AND id_utilisateur = :user_id";
                        $stmt_check = $db->prepare($sql_check_assign);
                        $stmt_check->bindParam(':projet_id', $projet['id'], PDO::PARAM_INT);
                        $stmt_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $stmt_check->execute();
                        $is_assigned = $stmt_check->fetchColumn() > 0;
                        ?>
                        <?php if ($is_assigned): ?>
                            <a href='projet_test.php?id=<?= $projet['id'] ?>'>
                                <button class='view-details'>Accéder au projet</button>
                            </a>
                        <?php else: ?>
                            <a href='projet.php?id=<?= $projet['id'] ?>'>
                                <button class='view-details'>Voir les Détails</button>
                            </a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Vous n'avez aucun projet personnel pour le moment.</p>
            <?php endif; ?>
        </section>
    <?php elseif ($filter == 'tableau'): ?>
        <h2>Tableau des projets</h2>
        <table class="project-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>État</th>
                    <th>Date de fin</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projets as $projet): ?>
                    <tr>
                        <td><?= htmlspecialchars($projet['titre']) ?></td>
                        <td><?= htmlspecialchars($projet['description_projet']) ?></td>
                        <td><?= htmlspecialchars($projet['etat']) ?></td>
                        <td><?= htmlspecialchars($projet['date_fin']) ?></td>
                        <td>
                            <a href="projetr&d.php?delete_project_id=<?= $projet['id'] ?>" class="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h2>Projets globaux</h2>
        <section class="projects">
            <?php if (!empty($projets)): ?>
                <?php foreach ($projets as $projet): ?>
                    <article class="project">
                        <h1><?= htmlspecialchars($projet['titre']) ?></h1>
                        <p><?= htmlspecialchars($projet['description_projet']) ?></p>
                        <?php
                        $sql_check_assign = "SELECT COUNT(*) FROM membres_projet WHERE id_projet = :projet_id AND id_utilisateur = :user_id";
                        $stmt_check = $db->prepare($sql_check_assign);
                        $stmt_check->bindParam(':projet_id', $projet['id'], PDO::PARAM_INT);
                        $stmt_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $stmt_check->execute();
                        $is_assigned = $stmt_check->fetchColumn() > 0;
                        ?>
                        <?php if ($is_assigned): ?>
                            <a href='projet_test.php?id=<?= $projet['id'] ?>'>
                                <button class='view-details'>Accéder au projet</button>
                            </a>
                        <?php else: ?>
                            <a href='projet.php?id=<?= $projet['id'] ?>'>
                                <button class='view-details'>Voir les Détails</button>
                            </a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun projet disponible pour le moment.</p>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn_projet = document.getElementById('btn_add_projet');
    if (btn_projet) {
        btn_projet.addEventListener('click', () => {
            window.location.href = "ajout_projet.php";
        });
    }

    const btn_global_projects = document.getElementById('btn_global_projects');
    if (btn_global_projects) {
        btn_global_projects.addEventListener('click', () => {
            window.location.href = "projetr&d.php";
        });
    }

    const btn_my_projects = document.getElementById('btn_my_projects');
    if (btn_my_projects) {
        btn_my_projects.addEventListener('click', () => {
            window.location.href = "projetr&d.php?filter=my_projects";
        });
    }

    const btn_tableau = document.getElementById('btn_tableau');
    if (btn_tableau) {
        btn_tableau.addEventListener('click', () => {
            window.location.href = "projetr&d.php?filter=tableau";
        });
    }
});
</script>
</body>
</html>
