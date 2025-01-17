document.addEventListener("DOMContentLoaded", function () {
    // Récupérer tous les liens de la sidebar
    const navLinks = document.querySelectorAll('.sidebar nav ul li a');
    
    // Vérifier la page actuelle et ajouter la classe active
    navLinks.forEach(link => {
        if (window.location.pathname.includes(link.getAttribute('href'))) {
            link.classList.add('active'); // Ajouter la classe active
        } else {
            link.classList.remove('active'); // Retirer la classe active des autres
        }
    });

    // Gestion du clic sur l'icône des notifications
    const notificationsBtn = document.getElementById('notificationsBtn');
    if (notificationsBtn) {
        notificationsBtn.addEventListener('click', function (event) {
            // Rediriger vers la page des notifications
            window.location.href = 'notifications.html';
        });
    }

    // Gestion du bouton "Ajouter un événement"
    const addEventBtn = document.getElementById('addEventBtn');
    const addEventModal = document.getElementById('addEventModal');
    const closeModalBtn = document.querySelector('.close');

    if (addEventBtn) {
        addEventBtn.addEventListener('click', function () {
            // Afficher la modale pour ajouter un événement
            addEventModal.style.display = 'block';
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function () {
            // Fermer la modale
            addEventModal.style.display = 'none';
        });
    }

    // Si l'utilisateur clique en dehors de la modale, la fermer
    window.addEventListener('click', function (event) {
        if (event.target === addEventModal) {
            addEventModal.style.display = 'none';
        }
    });

    // Gestion du formulaire pour ajouter un événement
    const addEventForm = document.getElementById('addEventForm');
    addEventForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Empêcher le rechargement de la page

        // Récupérer les données du formulaire
        const eventTitle = document.getElementById('eventTitle').value;
        const eventDate = document.getElementById('eventDate').value;
        const eventDescription = document.getElementById('eventDescription').value;

        // Implémenter la logique pour enregistrer l'événement

        // Fermer la modale après soumission
        addEventModal.style.display = "none";

        // Réinitialiser le formulaire
        addEventForm.reset();
    });
});
document.addEventListener("DOMContentLoaded", function () {
    // Initialiser FullCalendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr', // Langue du calendrier
        events: [
            {
                title: 'Réunion de projet',
                start: '2025-01-20T10:00:00',
                description: 'Réunion avec l\'équipe de R&D',
            },
            {
                title: 'Soumission d\'idée innovante',
                start: '2025-01-22',
                description: 'Dernier jour pour soumettre les idées',
            }
        ]
    });
    calendar.render();

    // Gestion du bouton "Ajouter un événement"
    const addEventBtn = document.getElementById('addEventBtn');
    const addEventModal = document.getElementById('addEventModal');
    const closeModalBtn = document.querySelector('.close');

    addEventBtn.addEventListener('click', function () {
        addEventModal.style.display = 'block';
    });

    closeModalBtn.addEventListener('click', function () {
        addEventModal.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target === addEventModal) {
            addEventModal.style.display = 'none';
        }
    });

    // Ajouter un événement via le formulaire
    const addEventForm = document.getElementById('addEventForm');
    addEventForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const eventTitle = document.getElementById('eventTitle').value;
        const eventDate = document.getElementById('eventDate').value;
        const eventDescription = document.getElementById('eventDescription').value;

        calendar.addEvent({
            title: eventTitle,
            start: eventDate,
            description: eventDescription,
        });

        addEventModal.style.display = 'none';
        addEventForm.reset();
    });
});
