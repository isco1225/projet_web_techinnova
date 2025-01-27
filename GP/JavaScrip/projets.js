document.addEventListener("DOMContentLoaded", function () {
    // Gestion du clic sur l'icône des notifications
    const notificationsBtn = document.getElementById('notificationsBtn');
    if (notificationsBtn) {
        notificationsBtn.addEventListener('click', function () {
            window.location.href = 'notifications.html';
        });
    }

    // Gestion des liens actifs dans la navigation
    const navLinks = document.querySelectorAll('.top-nav nav ul li a');
    const currentPage = window.location.pathname;

    navLinks.forEach((link) => {
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



// Liste des projets (simulée ici pour l'exemple)
const projects = [
    {
        title: "Développement AI",
        description: "IA pour la R&D",
        status: "En cours",
        progress: 60,
        members: ["Alice", "Bob", "Claire"],
        deadline: "30/06/2025"
    },
    {
        title: "Blockchain Research",
        description: "Projet blockchain pour la finance",
        status: "Terminé",
        progress: 100,
        members: ["David", "Elsa"],
        deadline: "15/12/2024"
    },
    {
        title: "IoT Research",
        description: "Développement de solutions IoT",
        status: "À venir",
        progress: 10,
        members: ["John", "Sara"],
        deadline: "01/06/2025"
    }
];

// Fonction pour afficher les projets
function displayProjects(filteredProjects) {
    const projectsList = document.getElementById("projects-list");
    projectsList.innerHTML = "";

    filteredProjects.forEach(project => {
        const projectRow = document.createElement("tr");

        // Titre du projet
        const titleCell = document.createElement("td");
        const titleLink = document.createElement("a");
        titleLink.href = "#";
        titleLink.textContent = project.title;
        titleCell.appendChild(titleLink);

        // Description
        const descriptionCell = document.createElement("td");
        descriptionCell.textContent = project.description;

        // État
        const statusCell = document.createElement("td");
        const statusBadge = document.createElement("span");
        statusBadge.textContent = project.status;
        statusBadge.classList.add("status");
        if (project.status === "En cours") statusBadge.classList.add("ongoing");
        if (project.status === "Terminé") statusBadge.classList.add("completed");
        if (project.status === "À venir") statusBadge.classList.add("upcoming");
        statusCell.appendChild(statusBadge);

        // Progression
        const progressCell = document.createElement("td");
        progressCell.textContent = project.progress + "%";

        // Membres
        const membersCell = document.createElement("td");
        membersCell.textContent = project.members.join(", ");

        // Échéance
        const deadlineCell = document.createElement("td");
        deadlineCell.textContent = project.deadline;

        // Actions
        const actionsCell = document.createElement("td");
        const accessButton = document.createElement("button");
        accessButton.textContent = "Accéder";
        actionsCell.appendChild(accessButton);

        projectRow.appendChild(titleCell);
        projectRow.appendChild(descriptionCell);
        projectRow.appendChild(statusCell);
        projectRow.appendChild(progressCell);
        projectRow.appendChild(membersCell);
        projectRow.appendChild(deadlineCell);
        projectRow.appendChild(actionsCell);

        projectsList.appendChild(projectRow);
    });
}

// Filtrer les projets selon les critères
document.getElementById("search").addEventListener("input", () => {
    const searchQuery = document.getElementById("search").value.toLowerCase();
    const filteredProjects = projects.filter(project =>
        project.title.toLowerCase().includes(searchQuery) ||
        project.description.toLowerCase().includes(searchQuery)
    );
    displayProjects(filteredProjects);
});

document.getElementById("status").addEventListener("change", () => {
    const statusFilter = document.getElementById("status").value;
    const filteredProjects = projects.filter(project => 
        statusFilter === "all" || project.status.toLowerCase() === statusFilter.toLowerCase()
    );
    displayProjects(filteredProjects);
});

document.getElementById("category").addEventListener("change", () => {
    const categoryFilter = document.getElementById("category").value;
    const filteredProjects = projects.filter(project =>
        categoryFilter === "all" || project.description.toLowerCase().includes(categoryFilter)
    );
    displayProjects(filteredProjects);
});

// Initialisation
displayProjects(projects);


// Liste des membres (exemple avec les mêmes projets)
const projectMembers = [
    {
        name: "Alice",
        role: "Chef de projet",
        email: "alice@techinnova.com"
    },
    {
        name: "Bob",
        role: "Développeur",
        email: "bob@techinnova.com"
    },
    {
        name: "Claire",
        role: "Chercheur",
        email: "claire@techinnova.com"
    }
];

// Fonction pour afficher les membres
function displayMembers() {
    const membersList = document.getElementById("members-list");
    membersList.innerHTML = ""; // Clear existing list

    projectMembers.forEach(member => {
        const row = document.createElement("tr");

        // Nom du membre
        const nameCell = document.createElement("td");
        nameCell.textContent = member.name;

        // Rôle
        const roleCell = document.createElement("td");
        roleCell.textContent = member.role;

        // Email
        const emailCell = document.createElement("td");
        emailCell.textContent = member.email;

        // Actions
        const actionsCell = document.createElement("td");
        const viewProfileBtn = document.createElement("button");
        viewProfileBtn.textContent = "Voir Profil";
        const modifyRoleBtn = document.createElement("button");
        modifyRoleBtn.textContent = "Modifier Rôle";
        const removeMemberBtn = document.createElement("button");
        removeMemberBtn.textContent = "Retirer";

        // Ajouter des événements pour les boutons
        modifyRoleBtn.addEventListener("click", () => modifyRole(member));
        removeMemberBtn.addEventListener("click", () => removeMember(member));

        actionsCell.appendChild(viewProfileBtn);
        actionsCell.appendChild(modifyRoleBtn);
        actionsCell.appendChild(removeMemberBtn);

        row.appendChild(nameCell);
        row.appendChild(roleCell);
        row.appendChild(emailCell);
        row.appendChild(actionsCell);

        membersList.appendChild(row);
    });
}

// Ajouter un membre (avec un prompt pour l'exemple)
document.getElementById("add-member-btn").addEventListener("click", () => {
    const name = prompt("Nom du membre :");
    const role = prompt("Rôle du membre :");
    const email = prompt("Email du membre :");

    if (name && role && email) {
        const newMember = { name, role, email };
        projectMembers.push(newMember);
        displayMembers(); // Re-render the member list
    }
});

// Modifier un rôle de membre
function modifyRole(member) {
    const newRole = prompt(`Modifier le rôle de ${member.name}:`, member.role);
    if (newRole) {
        member.role = newRole;
        displayMembers(); // Re-render the member list
    }
}

// Retirer un membre
function removeMember(member) {
    const confirmation = confirm(`Êtes-vous sûr de vouloir retirer ${member.name} ?`);
    if (confirmation) {
        const index = projectMembers.indexOf(member);
        if (index > -1) {
            projectMembers.splice(index, 1);
            displayMembers(); // Re-render the member list
        }
    }
}

// Initialisation de l'affichage des membres
displayMembers();




