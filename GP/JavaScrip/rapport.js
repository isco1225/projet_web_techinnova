document.addEventListener("DOMContentLoaded", function () {
    const addReportForm = document.getElementById("addReportForm");
    const reportTableBody = document.getElementById("reportTableBody");

    // Ajouter un rapport
    addReportForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const project = document.getElementById("projectSelect").value;
        const fileInput = document.getElementById("fileInput");

        if (fileInput.files.length === 0) {
            alert("Veuillez sélectionner un fichier !");
            return;
        }

        const fileName = fileInput.files[0].name;
        const dateAdded = new Date().toLocaleDateString();

        // Ajouter une ligne au tableau
        const newRow = document.createElement("tr");
        newRow.innerHTML = `
            <td>${fileName}</td>
            <td>${project}</td>
            <td>${dateAdded}</td>
            <td><button class="delete-btn">Supprimer</button></td>
        `;
        reportTableBody.appendChild(newRow);

        // Réinitialiser le formulaire
        addReportForm.reset();

        // Ajouter l'événement de suppression
        const deleteBtn = newRow.querySelector(".delete-btn");
        deleteBtn.addEventListener("click", function () {
            newRow.remove();
        });

        alert("Rapport ajouté avec succès !");
    });
});
