document.addEventListener("DOMContentLoaded", function () {
    // Gestion du clic sur le bouton "Éditer Profil"
    const editProfileBtn = document.getElementById('editProfileBtn');
    const editProfileModal = document.getElementById('editProfileModal');
    const closeModalBtn = document.querySelector('.close');

    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', function () {
            editProfileModal.style.display = 'flex';
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function () {
            editProfileModal.style.display = 'none';
        });
    }

    window.addEventListener('click', function (event) {
        if (event.target === editProfileModal) {
            editProfileModal.style.display = 'none';
        }
    });

    // Gestion du bouton "Se Déconnecter"
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            window.location.href = '/HTML/login.html';  // Redirige vers la page de login
        });
    }

    // Gestion du formulaire d'édition de profil
    const editProfileForm = document.getElementById('editProfileForm');
    if (editProfileForm) {
        editProfileForm.addEventListener('submit', function (event) {
            event.preventDefault();
            alert("Profil mis à jour avec succès !");
            editProfileModal.style.display = 'none';
        });
    }
});
