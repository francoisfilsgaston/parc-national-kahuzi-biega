
document.addEventListener("DOMContentLoaded", function () {
    const navUser = document.getElementById("navUser");
    // Vérifier que la zone utilisateur existe
    if (!navUser) {
        console.error("Element #navUser introuvable.");
        return;
    }


    // recuperer l'user
    const user = getUtilisateurConnecte();


    // l'user connecte
    if (user) {
        navUser.innerHTML = `
            <div class="nav-user">
                <div class="user-info">
                    <i class="fa-solid fa-circle-user"></i>
                    <span> ${user.nom}</span>

                </div>
                <button type="button" class="logout-btn" onclick="deconnexion()">
                    <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
                </button>
            </div>
        `;
    }


    // l'user non connecte

    else {
        navUser.innerHTML = `
            <div class="navbar">
                <a href="./connexion/connexion.php" class="connexion-btn">
                    <i class="fa-solid fa-right-to-bracket"></i> Connexion
                </a>
            </div>
            
        `;
    }
});