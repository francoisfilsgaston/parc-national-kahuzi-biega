const API = "http://localhost:8000/api_tourisme/api/";

const connexionForm = document.getElementById("connexionForm");
const message = document.getElementById("message");

// Vérifier que le formulaire existe
if (connexionForm) {

    connexionForm.addEventListener("submit", function (event) {
        event.preventDefault();


        // recuperer les infos
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;

        // Réinitialiser le message
        message.textContent = "";
        message.className = "message";


        // envoyer les donnees a l'API
        fetch(API + "connexion.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify({
                email: email,
                password: password
            })
        }).then(response => response.json()).then(result => {
            // connexion reussi
            if (result.status === "success") {


                // L'API peut utiliser "user"
                // ou "data"
                const utilisateur = result.user || result.data;

                // Vérifier les informations utilisateur
                if (!utilisateur) {
                    message.textContent = "Erreur : informations utilisateur absentes.";
                    message.classList.add("error");
                    return;
                }


                // enregistrer l'user
                localStorage.setItem(
                    "user",
                    JSON.stringify(utilisateur)
                );
                message.textContent = result.message || "Connexion réussie.";
                message.classList.add("success");

                // redirection vers l'accueil
                setTimeout(() => {
                    window.location.href = "../index.php";
                }, 1000);
            } else {
                message.textContent = result.message || "Email ou mot de passe incorrect.";
                message.classList.add("error");
            }
        }).catch(error => {
            console.error("Erreur connexion :", error);
            message.textContent = "Impossible de contacter le serveur.";
            message.classList.add("error");
        });
    });
}