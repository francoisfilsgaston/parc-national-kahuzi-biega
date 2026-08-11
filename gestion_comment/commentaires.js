
const API = "http://localhost:8000/api_tourisme/api/";

// id de l'activite
const params = new URLSearchParams(window.location.search);
const activiteId = params.get("id");

if (!activiteId) {
    alert("Activité introuvable.");
    window.location.href = "../activite.php";

}


// recuperer l'user connecte
function getUserConnecte() {
    const user = localStorage.getItem("user");
    if (!user) {
        return null;
    }
    try {
        return JSON.parse(user);
    } catch (error) {
        console.error("Erreur LocalStorage :", error);
        return null;
    }
}


// verifier la connexion
function verifierConnexion() {
    const user = getUserConnecte();
    if (!user) {
        alert(
            "Veuillez vous connecter pour effectuer cette action."
        );
        window.location.href = "/connexion/connexion.php";
        return null;
    }
    return user;
}


// charger l'activite
chargerActivite();

function chargerActivite() {
    fetch(
        API + "activites.php?id=" + activiteId
    ).then(response => response.json()).then(result => {
        if (result.success) {
            afficherActivite(result.data);
        } else {
            alert(
                "Activité introuvable."
            );
        }
    }).catch(error => {
        console.error(
            "Erreur activité :",
            error
        );
    });
}


// Afficher l'activite

function afficherActivite(activity) {
    document.getElementById("activiteDetails").innerHTML = `
        <img
            src="../assets/images/${activity.image}"
            alt="${activity.titre}"
        >
        <div class="activite-info">
            <h1>${activity.titre}</h1>
            <p>${activity.description}</p>

            <div class="activite-meta">
                <span>
                    <i class="fa-solid fa-clock"></i>
                    ${activity.duree}
                </span>

                <span>
                    <i class="fa-solid fa-money-bill-wave"></i>
                    ${activity.prix}$
                </span>
            </div>

            <div class="activite-actions">
                <button id="likeBtn" class="like-btn" onclick="toggleLike()">
                    <i class="fa-solid fa-thumbs-up"></i>
                    <span id="likes"> 0</span> J'aime
                </button>

                <span class="comment-info">
                    <i class="fa-solid fa-comment"></i>
                    <span id="nbCommentaires"> 0 </span> Commentaires
                </span>
            </div>
        </div>
    `;

    // Charger les likes
    chargerLikes();
    // Charger les commentaires
    chargerCommentaires();

}

function chargerLikes() {
    const user = getUserConnecte();
    // Si connecté → utiliser son ID
    let utilisateurId = "";

    if (user) {
        utilisateurId = user.id;
    }


    fetch(API + "likes.php?activite_id=" + activiteId + "&utilisateur_id=" + utilisateurId)
        .then(response => response.json())
        .then(result => {
            if (result.status === "success") {
                // Nombre de likes
                document.getElementById("likes").textContent = result.likes;

                const bouton = document.getElementById("likeBtn");

                // État du bouton
                if (result.liked) {
                    bouton.classList.add("liked");
                } else {
                    bouton.classList.remove("liked");
                }
            }
        }).catch(error => {
            console.error("Erreur likes :", error);
        });

}

function chargerCommentaires() {

    fetch(API + "commentaires_activites.php?activite_id=" + activiteId)
        .then(response => response.json())
        .then(result => {
            const liste = document.getElementById("listeCommentaires");
            liste.innerHTML = "";
            if (result.status === "success") {

                // Nombre de commentaires
                document.getElementById("nbCommentaires").textContent = result.data.length;
                result.data.forEach(commentaire => {
                    liste.innerHTML += `
                            <div class="commentaire">
                                <div class="commentaire-gauche">
                                    <strong>${commentaire.nom}</strong>
                                    <p>${commentaire.message}</p>

                                    <small>${commentaire.date_creation}</small>
                                </div>

                                <button class="delete-comment" onclick="supprimerCommentaire(${commentaire.id})"title="Supprimer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        `;
                }
                );
            }
        }).catch(error => {
            console.error("Erreur commentaires :", error);
        });
}



function publierCommentaire() {
    // Vérifier la connexion
    const user = verifierConnexion();
    if (!user) {
        return;
    }


    const textarea = document.getElementById("message");
    const message = textarea.value.trim();
    if (message === "") {
        alert("Écrivez un commentaire.");
        return;
    }


    fetch(API + "commentaires_activites.php", {
        method: "POST",
        headers: {
            "Content-Type":
                "application/json"
        },

        body: JSON.stringify({
            activite_id: activiteId,
            utilisateur_id: user.id,
            message: message
        })
    }
    ).then(response => response.json()).then(result => {
        if (result.status === "success") {
            // Vider textarea
            textarea.value = "";
            // Recharger commentaires
            chargerCommentaires();
        } else {
            alert(result.message);
        }
    }).catch(error => {
        console.error("Erreur publication :", error);
        alert("Une erreur est survenue.");
    });
}

// supprimer un commentaire
function supprimerCommentaire(id) {
    // Vérifier la connexion
    const user = verifierConnexion();
    if (!user) {
        return;
    }
    if (!confirm("Supprimer ce commentaire ?")) {
        return;
    }
    fetch(API + "commentaires_activites.php", {
        method: "DELETE",
        headers: {
            "Content-Type":
                "application/json"
        },
        body: JSON.stringify({
            commentaire_id: id,
            utilisateur_id: user.id
        })
    }
    ).then(response => response.json()).then(result => {
        if (result.status === "success") {
            alert(result.message);

            // Recharger commentaires
            chargerCommentaires();

        } else {
            alert(result.message);
        }
    }).catch(error => {
        console.error("Erreur suppression :", error);
        alert("Une erreur est survenue.");
    });
}


// Like & delike
function toggleLike() {
    // Vérifier la connexion
    const user = verifierConnexion();
    if (!user) {
        return;
    }

    const bouton = document.getElementById("likeBtn");
    const liked = bouton.classList.contains("liked");

    fetch(API + "likes.php", {
        method: liked
            ? "DELETE" : "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            activite_id: activiteId,
            utilisateur_id: user.id
        })
    }
    ).then(response => response.json()).then(result => {
        if (result.status === "success") {
            chargerLikes();
        } else {
            alert(result.message);
        }
    })
        .catch(error => {
            console.error("Erreur like :", error);
        });
}