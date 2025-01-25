document.addEventListener("DOMContentLoaded", function () {
    // Gestion des boutons de détail des projets
    const detailButtons = document.querySelectorAll(".details-btn");
    detailButtons.forEach(button => {
        button.addEventListener("click", () => {
            alert("Détails du projet bientôt disponibles !");
        });
    });

    // Gestion de la mise à jour des projets
    const updateForm = document.getElementById("updateForm");
    updateForm.addEventListener("submit", function (e) {
        e.preventDefault();
        alert("Mise à jour enregistrée !");
    });

    // Gestion de l'ajout de rapports
    const reportForm = document.getElementById("reportForm");
    reportForm.addEventListener("submit", function (e) {
        e.preventDefault();
        alert("Rapport ajouté avec succès !");
    });
});
