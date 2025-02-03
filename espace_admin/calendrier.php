<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier - TechInnova</title>
    <link rel="stylesheet" href="CSS/calendrier.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />
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

    <div class="main-content">
        <header>
            <h1>Calendrier</h1>
            <div id="header-icons">
                <a href="notification.php" id="notificationsBtn"><i class="fas fa-bell"></i></a>
                <a href="profil.html" id="profilBtn"><i class="fas fa-user"></i></a>
            </div>
        </header>

        <section class="calendar">
            <div id="calendar"></div>
        </section>

        <section class="actions">
            <button id="addEventBtn">Ajouter un Événement</button>
        </section>

        <!-- Modale pour ajouter un événement -->
        <div id="addEventModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Ajouter un Événement</h2>
                <form id="addEventForm">
                    <label for="eventTitle">Titre :</label>
                    <input type="text" id="eventTitle" name="eventTitle" required>

                    <label for="eventDate">Date :</label>
                    <input type="date" id="eventDate" name="eventDate" required>

                    <label for="eventDescription">Description :</label>
                    <textarea id="eventDescription" name="eventDescription" required></textarea>

                    <button type="submit">Ajouter l'Événement</button>
                </form>
            </div>
        </div>
    </div>

    <script src="js/script.js" defer></script>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
</body>
</html>
