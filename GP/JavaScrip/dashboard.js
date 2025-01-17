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

    // Gestion du bouton "Ajouter un nouveau projet"
    const addProjectBtn = document.getElementById('addProjectBtn');
    const addProjectModal = document.getElementById('addProjectModal');
    const closeModalBtn = document.querySelector('.close');

    if (addProjectBtn) {
        addProjectBtn.addEventListener('click', function () {
            // Afficher la modale pour ajouter un projet
            addProjectModal.style.display = 'block';
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function () {
            // Fermer la modale
            addProjectModal.style.display = 'none';
        });
    }

    // Si l'utilisateur clique en dehors de la modale, la fermer
    window.addEventListener('click', function (event) {
        if (event.target === addProjectModal) {
            addProjectModal.style.display = 'none';
        }
    });
});