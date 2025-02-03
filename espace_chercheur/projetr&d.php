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

try {
    // Récupérer les projets globaux
    $sql = "SELECT id, titre, description_projet, etat, date_fin FROM projets";
    if (!empty($search)) {
        $sql .= " WHERE titre LIKE :search OR description_projet LIKE :search";
    }
    $sql .= " ORDER BY date_fin ASC";
    $stmt = $db->prepare($sql);
    if (!empty($search)) {
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
    }
    $stmt->execute();
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les projets personnels si le filtre est activé
    if ($filter == 'my_projects') {
        $sql_personnels = "SELECT id, titre, description_projet, etat, date_fin 
                           FROM projets 
                           WHERE createur_id = :user_id";
        if (!empty($search)) {
            $sql_personnels .= " AND (titre LIKE :search OR description_projet LIKE :search)";
        }
        $sql_personnels .= " ORDER BY date_fin ASC";
        $stmt_personnels = $db->prepare($sql_personnels);
        $stmt_personnels->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        if (!empty($search)) {
            $stmt_personnels->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
        $stmt_personnels->execute();
        $projets_personnels = $stmt_personnels->fetchAll(PDO::FETCH_ASSOC);
    }

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
    <link rel="stylesheet" href="css/projet.css">
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
                <li><a href="chercheur.php" data-page="accueil">Accueil</a></li>
                <li><a href="projetr&d.php" data-page="projets">Projets R&D</a></li>
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

    <!-- Contenu principal -->
    <main id="content">
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
    });
    </script>
</body>
</html>
