document.addEventListener("DOMContentLoaded", function () {
    // Sidebar : Ajouter la classe active en fonction de la page
    const navLinks = document.querySelectorAll('.top-nav .nav-links ul li a');
    
    navLinks.forEach(link => {
        if (window.location.pathname.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    // Gestion du bouton Notifications
    const notificationsBtn = document.getElementById('notificationsBtn');
    if (notificationsBtn) {
        notificationsBtn.addEventListener('click', function () {
            window.location.href = 'notifications.html'; // Rediriger vers la page des notifications
        });
    }


    // Gestion des liens actifs dans la navigation
    const navLinkss = document.querySelectorAll('.top-nav nav ul li a');
    const currentPage = window.location.pathname;

    navLinkss.forEach((link) => {
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


// Fonction pour gérer l'upload de document
document.getElementById("uploadForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const fileInput = document.getElementById("documentUpload");
    const file = fileInput.files[0];

    if (!file) {
        document.getElementById("uploadMessage").textContent = "Aucun fichier sélectionné!";
        return;
    }

    // Validation du type de fichier
    const validTypes = ['pdf', 'docx', 'xlsx', 'pptx'];
    const fileExtension = file.name.split('.').pop().toLowerCase();
    if (!validTypes.includes(fileExtension)) {
        document.getElementById("uploadMessage").textContent = "Type de fichier non valide!";
        return;
    }

    // Validation de la taille du fichier (ex : 10MB max)
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
        document.getElementById("uploadMessage").textContent = "Le fichier est trop lourd!";
        return;
    }

    // Simuler l'ajout d'un document dans la liste
    const documentItem = document.createElement("div");
    documentItem.classList.add("document-item");
    documentItem.setAttribute("data-type", fileExtension);

    // Création d'une icône de type de document
    const icon = document.createElement("img");
    icon.src = getDocumentIcon(fileExtension);
    documentItem.appendChild(icon);

    // Détails du document
    const documentName = document.createElement("p");
    documentName.textContent = file.name;
    documentItem.appendChild(documentName);

    // Ajouter le document à l'affichage
    document.getElementById("documentsGrid").appendChild(documentItem);

    // Message de succès
    document.getElementById("uploadMessage").textContent = "Fichier téléchargé avec succès!";
});

// Fonction pour déterminer l'icône du fichier en fonction de son extension
function getDocumentIcon(extension) {
    switch (extension) {
        case 'pdf': return 'path/to/pdf-icon.png';
        case 'docx': return 'path/to/word-icon.png';
        case 'xlsx': return 'path/to/excel-icon.png';
        case 'pptx': return 'path/to/powerpoint-icon.png';
        default: return 'path/to/default-icon.png';
    }
}

// Fonction pour la recherche de documents
document.getElementById("searchButton").addEventListener("click", function() {
    const searchTerm = document.getElementById("searchDocuments").value.toLowerCase();
    const documentItems = document.querySelectorAll(".document-item");
    documentItems.forEach(item => {
        const documentName = item.querySelector("p").textContent.toLowerCase();
        if (documentName.includes(searchTerm)) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
});

// Fonction pour appliquer les filtres
document.getElementById("applyFilter").addEventListener("click", function() {
    const filterValue = document.getElementById("filterCategory").value;
    const documentItems = document.querySelectorAll(".document-item");

    documentItems.forEach(item => {
        if (filterValue === "all" || item.getAttribute("data-type") === filterValue) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
});

// Fonction pour afficher les détails du document dans une modale
document.querySelectorAll(".document-item").forEach(item => {
    item.addEventListener("click", function() {
        const documentName = item.querySelector("p").textContent;
        const documentType = item.getAttribute("data-type");

        // Afficher les détails du document
        const modal = document.getElementById("documentDetailsModal");
        const detailsContainer = document.getElementById("documentDetails");
        detailsContainer.innerHTML = `
            <p><strong>Nom :</strong> ${documentName}</p>
            <p><strong>Type de fichier :</strong> ${documentType}</p>
            <p><strong>Date d'ajout :</strong> ${new Date().toLocaleDateString()}</p>
            <p><strong>Taille :</strong> ${(item.files[0].size / (1024 * 1024)).toFixed(2)} MB</p>
        `;

        modal.style.display = "block";
    });
});

// Fermer la modale
document.querySelector(".close").addEventListener("click", function() {
    document.getElementById("documentDetailsModal").style.display = "none";
});


// Gestion des permissions
document.getElementById("roleSelect").addEventListener("change", function() {
    const role = document.getElementById("roleSelect").value;
    const rolePermissions = document.getElementById("rolePermissions");

    if (role === "researcher") {
        rolePermissions.textContent = "Permissions : Lecture seule, Ajouter des documents";
    } else if (role === "admin") {
        rolePermissions.textContent = "Permissions : Lecture, Modification, Suppression";
    } else {
        rolePermissions.textContent = "Permissions : Lecture seule sur certains documents";
    }
});

// Mettre à jour les permissions
document.getElementById("updatePermissions").addEventListener("click", function() {
    const role = document.getElementById("roleSelect").value;
    alert(`Permissions mises à jour pour le rôle: ${role}`);
});

// Ajout d'un commentaire sur un document
document.getElementById("addComment").addEventListener("click", function() {
    const comment = document.getElementById("commentBox").value;
    if (comment) {
        const commentDiv = document.createElement("div");
        commentDiv.textContent = comment;
        document.getElementById("commentsList").appendChild(commentDiv);
        document.getElementById("commentBox").value = "";  // Réinitialiser le champ de commentaire
    }
});

// Partage d'un document
document.getElementById("shareDocument").addEventListener("click", function() {
    const documentToShare = document.getElementById("documentShareSelect").value;
    const shareWith = document.getElementById("shareWithSelect").value;
    alert(`Le document "${documentToShare}" a été partagé avec un utilisateur ${shareWith}.`);
});


