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

    // Gestion du bouton "Ajouter un document"
    const addDocumentBtn = document.getElementById('addDocumentBtn');
    const addDocumentModal = document.getElementById('addDocumentModal');
    const closeModalBtn = document.querySelector('.close');

    if (addDocumentBtn) {
        addDocumentBtn.addEventListener('click', function () {
            // Afficher la modale pour ajouter un document
            addDocumentModal.style.display = 'block';
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function () {
            // Fermer la modale
            addDocumentModal.style.display = 'none';
        });
    }

    // Si l'utilisateur clique en dehors de la modale, la fermer
    window.addEventListener('click', function (event) {
        if (event.target === addDocumentModal) {
            addDocumentModal.style.display = 'none';
        }
    });

    // Gestion du formulaire pour ajouter un document
    const addDocumentForm = document.getElementById('addDocumentForm');
    addDocumentForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Empêcher le rechargement de la page

        // Récupérer les données du formulaire
        const documentTitle = document.getElementById('documentTitle').value;
        const documentFile = document.getElementById('documentFile').files[0];

        // Implémenter la logique pour enregistrer le document

        // Fermer la modale après soumission
        addDocumentModal.style.display = "none";

        // Réinitialiser le formulaire
        addDocumentForm.reset();
    });
});


document.addEventListener("DOMContentLoaded", function () {
    // Récupérer l'icône du profil
    const profilBtn = document.getElementById('profilBtn');
    const profileModal = document.getElementById('profileModal');
    const closeProfileBtn = document.querySelector('.close-profile');
    const logoutBtn = document.getElementById('logoutBtn');

    // Afficher la modale de profil
    if (profilBtn) {
        profilBtn.addEventListener('click', function () {
            profileModal.style.display = 'block';
        });
    }

    // Fermer la modale de profil
    if (closeProfileBtn) {
        closeProfileBtn.addEventListener('click', function () {
            profileModal.style.display = 'none';
        });
    }

    // Si l'utilisateur clique en dehors de la modale, la fermer
    window.addEventListener('click', function (event) {
        if (event.target === profileModal) {
            profileModal.style.display = 'none';
        }
    });

    // Déconnexion
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            // Ici, vous pouvez ajouter un script de déconnexion (par exemple, effacer la session)
            alert("Vous êtes déconnecté !");
            window.location.href = '/HTML/login.html'; // Rediriger vers la page de connexion
        });
    }
});
