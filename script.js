// URL de l'API
const API = "http://localhost:8000/api_tourisme/api/";

// ==============================
// CHARGER LES ACTIVITÉS
// ==============================

fetch(API + "activites.php?parc_id=1").then(response => response.json()).then(result => {
    console.log(result);
    const container = document.getElementById("activitiesContainer");
    const allActivitBtn = document.getElementById("allActivitiesBtn")
    if (!result.success) {
        container.innerHTML = "<p>Aucune activité trouvée.</p>";
        return;
    }
    container.innerHTML = "";
    result.data.slice(4, 7).forEach(activity => {
        container.innerHTML += `
                <div class="activity-card">
                        <img src="assets/images/${activity.image}" alt="${activity.titre}">
                    <div class="activity-content">
                        <h3>${activity.titre}</h3>
                        <p>${activity.description}</p>
                        <p>⏱ ${activity.duree}</p>
                    </div>
                </div>
            `;
    });
    allActivitBtn.innerHTML += `
            <a href="activite.php">Voir toutes les activités</a>
        `;
})
    .catch(error => {

        console.error(error);

    });
// =================================
// PAGE ACTIVITES
// =================================
const allActivities =
    document.getElementById("allActivities");
if (allActivities) {
    fetch(API + "activites.php?parc_id=1").then(response => response.json()).then(result => {
        if (result.success) {
            afficherActivites(result.data);
        }
    });
}

function afficherActivites(activites) {
    allActivities.innerHTML = "";
    activites.forEach((activity, index) => {
        allActivities.innerHTML += `
                <div class="activity-card">
                    <img src="assets/images/${activity.image}" class="activity-image" onclick="ouvrirPopup(${index})">
                    <div class="activity-content">
                        <h3>${activity.titre}</h3>
                         <p>${activity.description}</p>
                    </div>
                </div>
            `;
    });

    // garder les données disponibles
    window.listeActivites = activites;
}

function ouvrirPopup(index) {
    let activity = window.listeActivites[index];
    document.getElementById("popupImage").src = "assets/images/" + activity.image;
    document.getElementById("popupTitre").innerHTML = activity.titre;
    document.getElementById("popupDescription").innerHTML = activity.description;
    document.getElementById("popupDuree").innerHTML = "⏱ Durée : " + activity.duree;
    document.getElementById("popupPrix").innerHTML = "💰 Prix : " + activity.prix + " $";
    document.getElementById("activityPopup").style.display = "flex";
}

function fermerPopup() {
    document.getElementById("activityPopup").style.display = "none";
}
// Fermeture en cliquant à l'extérieur du popup

document.getElementById("activityPopup").addEventListener("click", function (event) {
    if (event.target === this) {
        fermerPopup();
    }
});

// ---------Recherche dans Activite---------------------
const activitySearch =
    document.getElementById("activitySearch");
let activitiesData = [];
if (activitySearch) {
    fetch(API + "activites.php?parc_id=1").then(response => response.json()).then(result => {
        activitiesData = result.data;
        afficherActivites(activitiesData);
    });

    activitySearch.addEventListener("input", () => {
        let recherche = activitySearch.value.toLowerCase();
        let resultat = activitiesData.filter(activity => {
            return (
                activity.titre.toLowerCase().includes(recherche) || activity.description.toLowerCase().includes(recherche));
        });
        afficherActivites(resultat);
    });
}