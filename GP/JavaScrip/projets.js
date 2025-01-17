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

    // Gestion du clic sur le bouton "Ajouter un Nouveau Projet"
    const addProjectBtn = document.getElementById('addProjectBtn');
    const addProjectModal = document.getElementById('addProjectModal');
    const closeModalBtn = document.querySelector('.close');

    // Afficher la modale
    addProjectBtn.addEventListener('click', function () {
        addProjectModal.style.display = 'block';
    });

    // Fermer la modale
    closeModalBtn.addEventListener('click', function () {
        addProjectModal.style.display = 'none';
    });

    // Fermer la modale si on clique en dehors
    window.addEventListener('click', function (event) {
        if (event.target === addProjectModal) {
            addProjectModal.style.display = 'none';
        }
    });

    // Formulaire d'ajout de projet
    const addProjectForm = document.getElementById('addProjectForm');
    if (addProjectForm) {
        addProjectForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const projectTitle = document.getElementById('projectTitle').value;
            const projectDescription = document.getElementById('projectDescription').value;
            const projectDeadline = document.getElementById('projectDeadline').value;

            alert(`Projet ajouté: ${projectTitle}\nDescription: ${projectDescription}\nÉchéance: ${projectDeadline}`);

            // Réinitialiser le formulaire
            addProjectForm.reset();
            addProjectModal.style.display = 'none';
        });
    }
});
