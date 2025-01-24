document.addEventListener("DOMContentLoaded", function () {
    const addIdeaBtn = document.getElementById('addIdeaBtn');
    const addIdeaModal = document.getElementById('addIdeaModal');
    const closeModalBtn = document.querySelector('.close');
    const addIdeaForm = document.getElementById('addIdeaForm');
    const ideasTableBody = document.getElementById('ideasTableBody');

    // Ouvrir la modale
    if (addIdeaBtn) {
        addIdeaBtn.addEventListener('click', function () {
            addIdeaModal.style.display = 'flex';
        });
    }

    // Fermer la modale
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function () {
            addIdeaModal.style.display = 'none';
        });
    }

    // Fermer la modale si on clique en dehors
    window.addEventListener('click', function (event) {
        if (event.target === addIdeaModal) {
            addIdeaModal.style.display = 'none';
        }
    });

    // Ajouter une idée dans le tableau
    addIdeaForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const title = document.getElementById('ideaTitle').value;
        const description = document.getElementById('ideaDescription').value;
        const category = document.getElementById('ideaCategory').value;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${title}</td>
            <td>${description}</td>
            <td>En attente</td>
            <td><button class="delete-btn">Supprimer</button></td>
        `;

        ideasTableBody.appendChild(row);

        // Réinitialiser et fermer la modale
        addIdeaForm.reset();
        addIdeaModal.style.display = 'none';
    });

    // Supprimer une idée
    ideasTableBody.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('delete-btn')) {
            const row = e.target.closest('tr');
            ideasTableBody.removeChild(row);
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


