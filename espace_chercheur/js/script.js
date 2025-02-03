/*const btnNotif = document.getElementById("btn_notif");
const notifContainer = document.querySelector(".project-notifications");

// Afficher/masquer les notifications au clic sur le bouton
btnNotif.addEventListener("click", (e) => {
    notifContainer.classList.toggle("visible");
});

// Cacher les notifications si on clique en dehors
document.addEventListener("click", (e) => {
    if (!notifContainer.contains(e.target) && e.target !== btnNotif) {
        notifContainer.classList.remove("visible");
    }
});

// Empêche la disparition lorsqu'on survole la zone des notifications
notifContainer.addEventListener("mouseenter", () => {
    notifContainer.classList.add("visible");
});

    notifContainer.addEventListener("mouseleave", () => {
        notifContainer.classList.remove("visible");
    });

*/
const tabletask = document.querySelector('.project-tâches table');
const addTaskBtn = document.getElementById('add-task-btn');
const addTaskForm = document.getElementById('add-task-form');
const cancelAddTask = document.getElementById('cancel-add-task');

// Afficher le formulaire
addTaskBtn.addEventListener('click', () => {
    addTaskForm.style.display = 'block';
    addTaskBtn.style.display = 'none';
    tabletask.style.display = 'none';
});

// Masquer le formulaire
cancelAddTask.addEventListener('click', () => {
    addTaskForm.style.display = 'none';
    addTaskBtn.style.display = 'block';
    tabletask.style.display = 'block';
});
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("addIdeaModal");
    const openModalBtn = document.getElementById("addIdeaBtn");
    const closeModalBtn = modal.querySelector(".close");
    console.log(openModalBtn);

    // Ouvrir le modal lorsque le bouton "Ajouter une Nouvelle Idée" est cliqué
    openModalBtn.addEventListener("click", function () {
        modal.style.display = "block";
    });

    // Fermer le modal lorsque l'utilisateur clique sur la croix (X)
    closeModalBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Fermer le modal si l'utilisateur clique en dehors de celui-ci
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});




document.addEventListener("DOMContentLoaded", function () {
    const menuLinks = document.querySelectorAll(".principal nav li a");
    const sections = document.querySelectorAll(".principal section");

    // Mapping entre les liens et les classes des sections
    const sectionMapping = {
        "dashboard": "project-dashboard",
        "details": "project-details",
        "notifications": "project-notifications",
        "discussions": "project-commentaires",
        "taches": "project-tâches",  // Supprime l'accent ici
        "membres": "project-membres",
        "documents": "project-documents"
    };

    menuLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();

            // Normalisation du texte du lien (supprime les accents et espaces)
            const sectionName = this.textContent.trim().toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            // Masquer toutes les sections
            sections.forEach(section => section.style.display = "none");

            // Afficher la section correspondante
            const targetSection = document.querySelector(`.${sectionMapping[sectionName]}`);
            if (targetSection) {
                targetSection.style.display = "block";
            }

            // Gestion des classes actives
            menuLinks.forEach(link => link.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Afficher la section "Details" par défaut
    document.querySelector(".project-details").style.display = "block";
});


// Récupérer l'ID du projet
var id_projet = document.querySelector("#project-taches").getAttribute("data-id_projet");

console.log("ID du projet récupéré dans JavaScript : ", id_projet); // Vérifie que l'ID est bien récupéré

// Fonction pour supprimer la tâche avec AJAX
function supprimerTache(tacheId) {
    if (confirm("Voulez-vous vraiment supprimer cette tâche ?")) {
        var formData = new FormData();
        formData.append('id_tache', tacheId);
        formData.append('supp_tache', true);
        formData.append('id_projet', id_projet);  // Ajouter l'ID du projet aux données

        console.log("ID du projet envoyé avec la requête : ", id_projet); // Vérifie l'ID envoyé
        console.log("ID du tache envoyé avec la requête : ", tacheId); // Vérifie l'ID envoyé

        // Utiliser l'URL correcte avec l'ID du projet
        fetch("http://localhost/projet_web/espace_chercheur/projet_test.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())  // Traiter la réponse en JSON
        .then(data => {
            console.log("Réponse du serveur :", data);  // Log de la réponse serveur

            // Si la tâche est supprimée avec succès, on recharge la section des tâches
            if (data.status === "success") {
                console.log("ID du projet renvoyé : ", data.id_projet);  // Récupérer l'ID du projet renvoyé
                alert("Tâche supprimée avec succès.");
                chargerTaches();
            } else {
                alert("Erreur lors de la suppression.");
            }
        })
        .catch(error => console.error("Erreur AJAX:", error));
    }
}


// Fonction pour recharger la section des tâches
function chargerTaches() {
    fetch("http://localhost/projet_web/espace_chercheur/projet_test.php?id_projet=" + id_projet)  // Passer l'ID du projet dans l'URL
        .then(response => response.text())
        .then(data => {
            document.querySelector(".project-tâches").innerHTML = data;
        })
        .catch(error => console.error("Erreur lors du chargement des tâches:", error));
}
