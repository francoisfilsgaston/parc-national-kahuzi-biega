// URL de l'API
const API = "http://localhost:8000/api_tourisme/api/";

// =================================
// PAGE ACTIVITES
// =================================
const allActivities = document.getElementById("allActivities");

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

                        <div class="activity-actions">

                            <button id="like-btn-${activity.id}"  class="like-btn" onclick="toggleLike(${activity.id})">
                                <i class="fa-solid fa-thumbs-up"></i>
                                <span id="like-count-${activity.id}">0</span> J'aime
                            </button>

                            <span class="separator">|</span>

                            <a href="gestion_comment/commentaires.php?id=${activity.id}" class="comment-btn">
                                <i class="fa-solid fa-comment"></i>
                                <span id="comment-count-${activity.id}"> 0 </span>Commentaires
                            </a>
                        </div>
                    </div>
                </div>
            `;
    });

    // garder les données disponibles
    window.listeActivites = activites;

    activites.forEach(activity => {
        chargerLikes(activity.id);
        chargerNombreCommentaires(activity.id);
    });
}


function chargerLikes(id) {
    const user = getUtilisateurConnecte();
    let url = API + "likes.php?activite_id=" + id;

    // Ajouter l'utilisateur seulement s'il est connecté
    if (user) {
        url += "&utilisateur_id=" + user.id;
    }
    fetch(url).then(response => response.json()).then(result => {
        if (result.status === "success") {
            const compteur = document.getElementById("like-count-" + id);
            const bouton = document.getElementById("like-btn-" + id);

            if (compteur) {
                compteur.textContent = result.likes;
            }

            if (bouton) {
                if (result.liked) {
                    bouton.classList.add("liked");
                } else {
                    bouton.classList.remove("liked");
                }
            }
        }
    }).catch(error => {
        console.error(
            "Erreur chargement likes :",
            error
        );
    });
}

function toggleLike(id) {
    const user = getUtilisateurConnecte();
    // UTILISATEUR NON CONNECTÉ
    if (!user) {
        alert(
            "Veuillez vous connecter pour aimer cette activité."
        );
        window.location.href = "connexion/connexion.php";
        return;
    }
    const bouton = document.getElementById("like-btn-" + id);
    if (!bouton) {
        return;
    }
    const liked = bouton.classList.contains("liked");
    // POST OU DELETE
    fetch(API + "likes.php", {
        method: liked ? "DELETE" : "POST",
        headers: {
            "Content-Type":
                "application/json"
        },

        body: JSON.stringify({
            activite_id: id,
            utilisateur_id: user.id
        })
    }).then(response => response.json()).then(result => {
        console.log("Réponse like :", result);
        if (result.status === "success") {
            chargerLikes(id);
        }
        else {
            alert(result.message);
        }
    }).catch(error => {
        console.error(
            "Erreur like :",
            error
        );
    });
}

// Charger les nombres de commentaires
function chargerNombreCommentaires(id) {
    fetch(API + "commentaires_activites.php?activite_id=" + id).then(response => response.json()).then(result => {
        if (result.status === "success") {
            const compteur = document.getElementById("comment-count-" + id);
            if (compteur) {
                compteur.textContent = result.data.length;
            }
        }
    }).catch(error => console.log(error));
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

const activityPopup = document.getElementById("activityPopup");
if (activityPopup) {
    activityPopup.addEventListener("click", function (event) {
        if (event.target === this) {
            fermerPopup();
        }
    });
}


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

// ===================================PAGE CONTACT===================

const contactForm = document.getElementById("contactForm");
const responseMessage = document.getElementById("responseMessage");

// envoyer le formulaire
if (contactForm) {
    contactForm.addEventListener("submit", function (event) {
        event.preventDefault();
        // recuperer les donnees
        const nom = document.getElementById("nom").value.trim();
        const email = document.getElementById("email").value.trim();
        const message = document.getElementById("message").value.trim();

        // reset
        responseMessage.textContent = "";
        responseMessage.className = "response-message";


        // envoyer a L'API
        fetch(API + "contact.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            }, body: JSON.stringify({
                nom: nom,
                email: email,
                message: message
            })
        }
        ).then(response => response.json()).then(result => {
            console.log("Réponse contact :", result);
            if (result.success) {
                responseMessage.textContent = result.message;
                responseMessage.classList.add("success");

                // Vider le formulaire
                contactForm.reset();
            }
            else {
                responseMessage.textContent = result.message;
                responseMessage.classList.add("error");
            }
        }
        ).catch(
            error => {
                console.error("Erreur contact :", error);
                responseMessage.textContent = "Impossible de contacter le serveur.";
                responseMessage.classList.add("error");
            }
        );
    }
    );

}