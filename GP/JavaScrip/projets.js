document.addEventListener("DOMContentLoaded", function () {
    // Gestion du clic sur l'icône des notifications
    const notificationsBtn = document.getElementById('notificationsBtn');
    if (notificationsBtn) {
        notificationsBtn.addEventListener('click', function () {
            window.location.href = 'notifications.html';
        });
    }

    // Gestion du bouton "Ajouter un nouveau projet"
    const addProjectBtn = document.getElementById('addProjectBtn');
    const addProjectModal = document.getElementById('addProjectModal');
    const closeModalBtn = document.querySelector('.close');

    if (addProjectBtn) {
        addProjectBtn.addEventListener('click', function () {
            addProjectModal.style.display = 'flex';
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function () {
            addProjectModal.style.display = 'none';
        });
    }

    window.addEventListener('click', function (event) {
        if (event.target === addProjectModal) {
            addProjectModal.style.display = 'none';
        }
    });

    // Gestion des liens actifs dans la navigation
    const navLinks = document.querySelectorAll('.top-nav nav ul li a');
    const currentPage = window.location.pathname;

    navLinks.forEach((link) => {
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


