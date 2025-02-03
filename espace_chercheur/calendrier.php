<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier - TechInnova</title>
    <link rel="stylesheet" href="css/calendrier.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Barre de navigation en haut -->
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

    <!-- Contenu principal du calendrier -->
    <div class="main-content">
        <header>
            <h1>Calendrier des Événements</h1>
        </header>

        <section class="calendar">
            <div class="calendar-header">
                <button id="prevMonth" class="nav-btn"><i class="fas fa-chevron-left"></i></button>
                <h2 id="monthTitle">Janvier 2025</h2>
                <button id="nextMonth" class="nav-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
            <!-- Bouton Ajouter un Nouveau Événement -->
            <button id="addEventBtn" class="btn-add-event">Ajouter un Nouveau Événement</button>
            <div id="calendarGrid" class="calendar-grid">
                <!-- Les jours du mois seront ajoutés par JavaScript -->
            </div>
        </section>
    </div>

    <!-- Modale Ajouter un Nouveau Événement -->
    <div id="addEventModal" class="modal">
        <div class="modal-content">
            <h2>Ajouter un Nouveau Événement</h2>
            <span class="close">&times;</span>
            <form>
                <label for="eventTitle">Titre de l'Événement</label>
                <input type="text" id="eventTitle" name="eventTitle" required>

                <label for="eventDate">Date de l'Événement</label>
                <input type="date" id="eventDate" name="eventDate" required>

                <label for="eventDescription">Description</label>
                <textarea id="eventDescription" name="eventDescription"></textarea>

                <button type="submit">Ajouter l'Événement</button>
            </form>
        </div>
    </div>

    <!-- Interface Profil -->
    <div id="profileInterface" class="profile-interface">
        <div class="profile-info">
            <img src="profile.jpg" alt="Image de Profil" class="profile-img">
            <div class="profile-details">
                <h3>Nom: Jean Dupont</h3>
                <p>Poste: Chercheur Principal</p>
                <p>Email: jean.dupont@techinnova.com</p>
                <p>Département: R&D</p>
                <button id="logoutBtn">Se Déconnecter</button>
            </div>
        </div>
    </div>


    <script src="/JavaScrip/calendrier.js" defer></script>
</body>
</html>
