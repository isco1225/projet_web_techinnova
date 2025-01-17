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

    // Gestion du clic sur le bouton "Ajouter une Nouvelle Idée"
    const addIdeaBtn = document.getElementById('addIdeaBtn');
    const addIdeaModal = document.getElementById('addIdeaModal');
    const closeModalBtn = document.querySelector('.close');

    // Afficher la modale
    addIdeaBtn.addEventListener('click', function () {
        addIdeaModal.style.display = "block";
    });

    // Fermer la modale
    closeModalBtn.addEventListener('click', function () {
        addIdeaModal.style.display = "none";
    });

    // Fermer la modale si on clique en dehors de la fenêtre
    window.addEventListener('click', function (event) {
        if (event.target === addIdeaModal) {
            addIdeaModal.style.display = "none";
        }
    });

    // Gestion du formulaire pour ajouter une idée
    const addIdeaForm = document.getElementById('addIdeaForm');
    addIdeaForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Empêcher le rechargement de la page

        // Récupérer les données du formulaire
        const ideaTitle = document.getElementById('ideaTitle').value;
        const ideaDescription = document.getElementById('ideaDescription').value;
        const ideaCategory = document.getElementById('ideaCategory').value;

        // Implémenter la logique pour enregistrer l'idée (par exemple en l'ajoutant à une base de données)

        // Fermer la modale après soumission
        addIdeaModal.style.display = "none";

        // Réinitialiser le formulaire
        addIdeaForm.reset();
    });
});
