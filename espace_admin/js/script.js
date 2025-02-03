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

    // Gestion du clic sur l'icône des notifications
    const ajout_projet = document.getElementById('addProjectBtn');
    const notificationsBtn = document.getElementById('notificationsBtn');
    if (notificationsBtn) {
        notificationsBtn.addEventListener('click', function (event) {
            // Rediriger vers la page des notifications
            window.location.href = 'http://localhost/projet_web/espace_admin/notification.php';
        });
    }
    addProjectBtn.addEventListener('click', ()=> {
            // Rediriger vers la page des notifications
            window.location.href = 'http://localhost/projet_web/espace_admin/ajout_projet.php';
        });

    let btn_creercompte = document.querySelector('.btn_creercompte');
    btn_creercompte.addEventListener('click', ()=>{
        window.location.href = 'http://localhost/projet_web/espace_admin/creation_compte.php';
    })

    // Gestion du bouton "Ajouter un document"
    const addDocumentBtn = document.getElementById('addDocumentBtn');
    const addDocumentModal = document.getElementById('addDocumentModal');
    const closeModalBtn = document.querySelector('.close');

    if (addDocumentBtn) {
        addDocumentBtn.addEventListener('click', function () {
            // Afficher la modale pour ajouter un document
            addDocumentModal.style.display = 'block';
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function () {
            // Fermer la modale
            addDocumentModal.style.display = 'none';
        });
    }

    // Si l'utilisateur clique en dehors de la modale, la fermer
    window.addEventListener('click', function (event) {
        if (event.target === addDocumentModal) {
            addDocumentModal.style.display = 'none';
        }
    });

    // Gestion du formulaire pour ajouter un document
    const addDocumentForm = document.getElementById('addDocumentForm');
    addDocumentForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Empêcher le rechargement de la page

        // Récupérer les données du formulaire
        const documentTitle = document.getElementById('documentTitle').value;
        const documentFile = document.getElementById('documentFile').files[0];

        // Implémenter la logique pour enregistrer le document

        // Fermer la modale après soumission
        addDocumentModal.style.display = "none";

        // Réinitialiser le formulaire
        addDocumentForm.reset();
    });
});


document.addEventListener("DOMContentLoaded", function () {
    // Récupérer l'icône du profil
    const profilBtn = document.getElementById('profilBtn');
    const profileModal = document.getElementById('profileModal');
    const closeProfileBtn = document.querySelector('.close-profile');
    const logoutBtn = document.getElementById('logoutBtn');

    // Afficher la modale de profil
    if (profilBtn) {
        profilBtn.addEventListener('click', function () {
            profileModal.style.display = 'block';
        });
    }

    // Fermer la modale de profil
    if (closeProfileBtn) {
        closeProfileBtn.addEventListener('click', function () {
            profileModal.style.display = 'none';
        });
    }

    // Si l'utilisateur clique en dehors de la modale, la fermer
    window.addEventListener('click', function (event) {
        if (event.target === profileModal) {
            profileModal.style.display = 'none';
        }
    });

    // Déconnexion
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            // Ici, vous pouvez ajouter un script de déconnexion (par exemple, effacer la session)
            alert("Vous êtes déconnecté !");
            window.location.href = '/HTML/login.html'; // Rediriger vers la page de connexion
        });
    }
});


function editRow(button) {
    const row = button.closest('tr'); // La ligne correspondante
    const editableCells = row.querySelectorAll('[data-editable]'); // Cellules modifiables
    const actionCell = row.querySelector('.action-buttons'); // Cellule des actions
    
    // Sauvegarder l'état initial
    row.dataset.originalState = row.innerHTML;

    // Convertir les cellules en champs de saisie
    editableCells.forEach(cell => {
        const fieldName = cell.getAttribute('data-editable');
        const currentValue = cell.textContent;
        cell.innerHTML = `<input type="text" name="${fieldName}" value="${currentValue}" class="editable-input">`;
    });

    // Mettre à jour les boutons d'action
    actionCell.innerHTML = `
    <td>
        <button class="btn btn-save" name="btn_save" onclick="saveRow(this)">Enregistrer</button>
        <button class="btn btn-cancel" onclick="cancelEdit(this)">Annuler</button>
    </td>
    `;
}

function saveRow(button) {
    const row = button.closest('tr'); // Ligne en cours
    const editableCells = row.querySelectorAll('[data-editable]'); // Cellules modifiables
    const updatedData = {}; // Obtenir les nouvelles données
    
    // Collecter les nouvelles valeurs
    editableCells.forEach(cell => {
        const input = cell.querySelector('input');
        if (input) {
            const fieldName = input.name;
            updatedData[fieldName] = input.value;
            cell.textContent = input.value; // Afficher la nouvelle valeur
        }
    });

    // Mettre à jour les boutons
    resetButtons(row);
    
    // Ajoutez ici un appel AJAX ou `fetch()` pour envoyer les données mises à jour au serveur
}

function cancelEdit(button) {
    const row = button.closest('tr'); // Ligne en cours
    const originalState = row.dataset.originalState; // Restaurer l'état initial
    row.innerHTML = originalState;
}

function resetButtons(row) {
    const actionCell = row.querySelector('.action-buttons');
    actionCell.innerHTML = `
        <button class="btn btn-edit" onclick="editRow(this)">Modifier</button>
        <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
            <button type="submit" class="btn btn-delete" name="delete_user">Supprimer</button>
        </form>
    `;
}

function confirmDelete() {
    // Afficher une boîte de dialogue de confirmation avant de soumettre le formulaire
    return confirm("Voulez-vous vraiment supprimer cet utilisateur ?");
}
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("addIdeaModal");
    const openModalBtn = document.getElementById("addIdeaBtn");
    const closeModalBtn = modal.querySelector(".close");

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

document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les éléments de la page
    const addDocumentBtn = document.getElementById('addDocumentBtn');
    const addDocumentModal = document.getElementById('addDocumentModal');
    const closeModal = document.querySelector('.close');

    // Lorsque le bouton "Ajouter un Document" est cliqué, afficher la modale
    addDocumentBtn.addEventListener('click', function() {
        addDocumentModal.style.display = 'block';
    });

    // Lorsque la croix de la modale est cliquée, masquer la modale
    closeModal.addEventListener('click', function() {
        addDocumentModal.style.display = 'none';
    });

    // Lorsque l'utilisateur clique en dehors de la modale, la fermer également
    window.addEventListener('click', function(event) {
        if (event.target === addDocumentModal) {
            addDocumentModal.style.display = 'none';
        }
    });
});
document.addEventListener('DOMContentLoaded', () => {
    let btn_creercompte = document.querySelector('#btn_creercompte');
    btn_creercompte.addEventListener('click', () => {
        window.location.href = 'http://localhost/projet_web/espace_admin/creation_compte.php';
    });
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
