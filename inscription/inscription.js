const API = "http://localhost:8000/api_tourisme/api/";



const inscriptionForm = document.getElementById("inscriptionForm");
const message = document.getElementById("message");


if (inscriptionForm) {
    inscriptionForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const nom = document.getElementById("nom").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirmPassword").value;


        message.textContent = "";
        message.className = "message";

        if (password !== confirmPassword) {
            message.textContent = "Les mots de passe ne correspondent pas.";
            message.classList.add("error");
            return;
        }

        if (password.length < 6) {
            message.textContent = "Le mot de passe doit contenir au moins 6 caractères.";
            message.classList.add("error");
            return;
        }

        // envoyer a l'API
        fetch(API + "inscription.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                nom: nom,
                email: email,
                password: password
            })
        }
        ).then(response => response.json()).then(
            result => {
                console.log("Réponse inscription :", result);

                if (result.status === "success") {
                    message.textContent = result.message;
                    message.classList.add("success");

                    // Vider le formulaire
                    inscriptionForm.reset();

                    // redirection
                    setTimeout(() => {
                        window.location.href = "../connexion/connexion.php";
                    },
                        1500
                    );

                }
                else {
                    message.textContent = result.message;
                    message.classList.add("error");
                }
            }
        ).catch(error => {
            console.error("Erreur inscription :", error);

            message.textContent = "Impossible de contacter le serveur.";
            message.classList.add("error");
        }
        );
    }
    );

}