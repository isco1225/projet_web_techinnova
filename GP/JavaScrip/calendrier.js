// Initialisation de la date actuelle
let currentDate = new Date();
let selectedMonth = currentDate.getMonth();
let selectedYear = currentDate.getFullYear();

// Affichage du calendrier du mois courant
function renderCalendar(month, year) {
    const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();
    const calendarGrid = document.getElementById("calendarGrid");
    const monthTitle = document.getElementById("monthTitle");

    // Mise à jour du titre du mois
    monthTitle.textContent = `${monthNames[month]} ${year}`;

    // Réinitialisation de la grille du calendrier
    calendarGrid.innerHTML = '';

    // Ajout des jours dans le calendrier
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        calendarGrid.appendChild(emptyCell);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement('div');
        dayCell.classList.add('day');
        dayCell.textContent = day;
        dayCell.onclick = () => {
            alert(`Événements pour le ${day}/${month + 1}/${year}`);
        };
        calendarGrid.appendChild(dayCell);
    }
}

// Navigation entre les mois
document.getElementById("prevMonth").addEventListener("click", () => {
    selectedMonth--;
    if (selectedMonth < 0) {
        selectedMonth = 11;
        selectedYear--;
    }
    renderCalendar(selectedMonth, selectedYear);
});

document.getElementById("nextMonth").addEventListener("click", () => {
    selectedMonth++;
    if (selectedMonth > 11) {
        selectedMonth = 0;
        selectedYear++;
    }
    renderCalendar(selectedMonth, selectedYear);
});

// Initialisation du calendrier
renderCalendar(selectedMonth, selectedYear);

// Gestion de la modale pour ajouter un événement
const addEventModal = document.getElementById("addEventModal");
const addEventBtn = document.getElementById("addEventBtn");
const closeModalBtn = document.querySelector(".modal .close");

addEventBtn.addEventListener("click", () => {
    addEventModal.style.display = "flex";
});

closeModalBtn.addEventListener("click", () => {
    addEventModal.style.display = "none";
});

// Fermer la modale si on clique en dehors de l'interface "Ajouter un événement"
window.addEventListener("click", (event) => {
    if (event.target === addEventModal) {
        addEventModal.style.display = "none";
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
