document.addEventListener("DOMContentLoaded", function () {
    // Sidebar : Ajouter la classe active en fonction de la page
    const navLinks = document.querySelectorAll('.top-nav .nav-links ul li a');
    
    navLinks.forEach(link => {
        if (window.location.pathname.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    // Gestion du bouton Notifications
    const notificationsBtn = document.getElementById('notificationsBtn');
    if (notificationsBtn) {
        notificationsBtn.addEventListener('click', function () {
            window.location.href = 'notifications.html'; // Rediriger vers la page des notifications
        });
    }

    // Gestion du bouton Ajouter un document
    const addDocumentBtn = document.getElementById('addDocumentBtn');
    const addDocumentModal = document.getElementById('addDocumentModal');
    const closeModalBtn = document.querySelector('.close');

    if (addDocumentBtn) {
        addDocumentBtn.addEventListener('click', function () {
            addDocumentModal.style.display = 'block'; // Afficher la modale pour ajouter un document
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function () {
            addDocumentModal.style.display = 'none'; // Fermer la modale
        });
    }

    // Fermer la modale si l'utilisateur clique en dehors
    window.addEventListener('click', function (event) {
        if (event.target === addDocumentModal) {
            addDocumentModal.style.display = 'none';
        }
    });

    // Gestion du formulaire d'ajout de document
    const addDocumentForm = document.getElementById('addDocumentForm');
    if (addDocumentForm) {
        addDocumentForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Empêcher le rechargement de la page

            // Récupérer les données du formulaire
            const documentTitle = document.getElementById('documentTitle').value;
            const documentFile = document.getElementById('documentFile').files[0];

            // Implémenter la logique pour enregistrer le document ici

            // Fermer la modale et réinitialiser le formulaire
            addDocumentModal.style.display = 'none';
            addDocumentForm.reset();
        });
    }

    // Gestion des liens actifs dans la navigation
    const navLinkss = document.querySelectorAll('.top-nav nav ul li a');
    const currentPage = window.location.pathname;

    navLinkss.forEach((link) => {
        if (link.href.includes(currentPage)) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

  // Afficher l'interface du profil
    const profilBtn = document.getElementById('profilBtn');
    const profileInterface = document.createElement('div');
    profileInterface.id = 'profileInterface';
    profileInterface.style.position = 'absolute';
    profileInterface.style.top = '50px';
    profileInterface.style.right = '10px';
    profileInterface.style.width = '300px';
    profileInterface.style.height = '150px';
    profileInterface.style.backgroundColor = '#fff';
    profileInterface.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';
    profileInterface.style.borderRadius = '8px';
    profileInterface.style.padding = '20px';
    profileInterface.style.display = 'none'; // Initialement masqué

    // Ajouter l'interface profil à la page
    const topNav = document.querySelector('.top-nav');
    topNav.appendChild(profileInterface);

    const profileContent = `
        <div style="text-align: center;">
            <img src="https://via.placeholder.com/100" alt="Profil" style="border-radius: 50%; width: 60px; height: 60px;">
            <h4>Nom Utilisateur</h4>
            <p>Rôle : Chercheur</p>
            <button id="logoutBtn" style="background-color: #d13b22; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;">Déconnexion</button>
        </div>
    `;

    profileInterface.innerHTML = profileContent;

    if (profilBtn) {
        profilBtn.addEventListener('click', function (event) {
            // Afficher ou masquer l'interface profil
            profileInterface.style.display = (profileInterface.style.display === 'none' || profileInterface.style.display === '') ? 'block' : 'none';
            event.stopPropagation(); // Empêche la propagation du clic pour éviter de fermer le profil immédiatement
        });
    }

    // Fermer l'interface profil si l'utilisateur clique en dehors de celle-ci
    window.addEventListener('click', function (event) {
        if (event.target !== profilBtn && event.target !== profileInterface && !profileInterface.contains(event.target)) {
            profileInterface.style.display = 'none';
        }
    });

    // Simulation de la déconnexion
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            // Logique de déconnexion, ici on redirige simplement vers la page de connexion
            window.location.href = 'login.html'; // Remplacer par l'URL de votre page de connexion
        });
    }
});

