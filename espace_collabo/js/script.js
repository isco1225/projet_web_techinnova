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

document.addEventListener("DOMContentLoaded", function () {
    const menuLinks = document.querySelectorAll(".principal nav li a");
    const sections = document.querySelectorAll(".principal section");

    // Mapping entre les liens et les classes des sections
    const sectionMapping = {
        "dashboard": "project-dashboard",
        "details": "project-details",
        "notifications": "project-notifications",
        "discussions": "project-commentaires",
        "taches": "project-taches",  // Supprime l'accent ici
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