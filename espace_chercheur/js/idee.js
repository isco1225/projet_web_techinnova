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
    // Restaurer la position après le rechargement
    if (sessionStorage.getItem("scrollPosition")) {
        window.scrollTo(0, sessionStorage.getItem("scrollPosition"));
    }

    // Sauvegarder la position avant soumission d'un formulaire
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", function () {
            sessionStorage.setItem("scrollPosition", window.scrollY);
        });
    });
});
