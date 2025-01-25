document.addEventListener("DOMContentLoaded", function () {
    // Gestion de l'ajout de rapports
    const reportForm = document.getElementById("reportForm");
    reportForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const project = document.getElementById("projectReport").value;
        const fileInput = document.getElementById("reportInput");

        if (fileInput.files.length === 0) {
            alert("Veuillez sélectionner un fichier à ajouter.");
            return;
        }

        alert(`Rapport ajouté pour le projet "${project}".`);
    });
});
