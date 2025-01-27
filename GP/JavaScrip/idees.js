document.addEventListener("DOMContentLoaded", function () {
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



// JavaScript pour la gestion des Idées Innovantes

// Initialisation des idées (simulation d'une base de données locale)
const idees = [
    {
      titre: "Idée 1",
      date: "2025-01-01",
      etat: "En attente",
      description: "Description détaillée de l'idée 1",
      categorie: "IA",
      motsCles: ["innovation", "tech"],
      documents: [],
      budget: null
    },
    {
      titre: "Idée 2",
      date: "2025-01-15",
      etat: "Acceptée",
      description: "Description détaillée de l'idée 2",
      categorie: "Robotique",
      motsCles: ["robot", "IA"],
      documents: [],
      budget: 5000
    }
  ];
  
  // Référence des éléments HTML
  const tableIdees = document.querySelector("#table-idees tbody");
  const formSoumission = document.querySelector("#form-soumission");
  const addIdeaBtn = document.querySelector("#addIdeaBtn");
  
  // Fonction pour mettre à jour le tableau des idées
  function majTableauIdees() {
    tableIdees.innerHTML = ""; // Réinitialise le contenu
    idees.forEach((idee, index) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${idee.titre}</td>
        <td>${idee.date}</td>
        <td>${idee.etat}</td>
        <td>
          <button class="btn-consulter" data-index="${index}">Consulter</button>
          ${idee.etat === "En attente" ? `<button class="btn-modifier" data-index="${index}">Modifier</button>` : ""}
          ${idee.etat === "En attente" ? `<button class="btn-supprimer" data-index="${index}">Supprimer</button>` : ""}
        </td>
      `;
      tableIdees.appendChild(row);
    });
  }
  
  // Gestion de la soumission d'une idée
  formSoumission.addEventListener("submit", (e) => {
    e.preventDefault();
  
    const nouvelleIdee = {
      titre: formSoumission.titre.value,
      date: new Date().toISOString().split("T")[0],
      etat: "En attente",
      description: formSoumission.description.value,
      categorie: formSoumission.categorie.value,
      motsCles: formSoumission["mots-cles"].value.split(","),
      documents: formSoumission.documents.files,
      budget: formSoumission.budget.value || null
    };
  
    idees.push(nouvelleIdee);
    majTableauIdees();
    formSoumission.reset();
    alert("Idée soumise avec succès !");
  });
  
  // Gestion des actions sur les idées
  function gererActions(event) {
    const target = event.target;
    const index = target.dataset.index;
  
    if (target.classList.contains("btn-consulter")) {
      alert(`Détails de l'idée :\n\nTitre : ${idees[index].titre}\nDescription : ${idees[index].description}\nCatégorie : ${idees[index].categorie}\nÉtat : ${idees[index].etat}`);
    } else if (target.classList.contains("btn-modifier")) {
      const nouveauTitre = prompt("Modifier le titre de l'idée", idees[index].titre);
      if (nouveauTitre) {
        idees[index].titre = nouveauTitre;
        majTableauIdees();
      }
    } else if (target.classList.contains("btn-supprimer")) {
      if (confirm("Voulez-vous vraiment supprimer cette idée ?")) {
        idees.splice(index, 1);
        majTableauIdees();
      }
    }
  }
  
  tableIdees.addEventListener("click", gererActions);
  
  // Gestion du bouton Ajouter une Nouvelle Idée
  addIdeaBtn.addEventListener("click", () => {
    document.querySelector("#soumission-idee").scrollIntoView({ behavior: "smooth" });
  });
  
  // Initialisation du tableau au chargement
  majTableauIdees();
  


