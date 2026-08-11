
// recuperer l'user connecte
function getUtilisateurConnecte() {
    const user = localStorage.getItem("user");
    // Aucun utilisateur connecté
    if (!user) {
        return null;
    }
    try {
        return JSON.parse(user);
    } catch (error) {
        console.error("Erreur lors de la lecture des informations utilisateur :", error);
        return null;
    }
}



// verifier si l'user est connecte
function estConnecte() {
    const user = getUtilisateurConnecte();
    return user !== null;
}

// recuperer l'id de l'user
function getUtilisateurId() {
    const user = getUtilisateurConnecte();
    if (!user) {
        return null;
    }
    return user.id;
}


// deconnexion
function deconnexion() {
    // Supprimer l'utilisateur
    localStorage.removeItem("user");
    // Retourner vers la connexion
    window.location.href = "connexion/connexion.php";

}