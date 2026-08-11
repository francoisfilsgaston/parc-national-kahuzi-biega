<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="inscription.css">
</head>

<body>
    <main class="inscription-container">
        <div class="inscription-card">
            <div class="inscription-header">
                <div class="logo">
                    <i class="fa-solid fa-user-plus"></i>
                </div>

                <h1>Créer un compte</h1>
                <p>
                    Rejoignez-nous pour découvrir
                    toutes les activités.
                </p>

            </div>

            <form id="inscriptionForm">
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" id="nom" placeholder="Votre nom" required>
                </div>

                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" placeholder="exemple@email.com" required>
                </div>


                <div class="form-group">
                    <label for="password"> Mot de passe</label>
                    <input type="password" id="password" placeholder="Votre mot de passe" required>
                </div>

                <div class="form-group">
                    <label for="confirmPassword"> Confirmer le mot de passe</label>
                    <input type="password" id="confirmPassword" placeholder="Confirmez votre mot de passe" required>
                </div>

                <div id="message" class="message"></div>

                <button type="submit" class="btn-inscription">
                    <i class="fa-solid fa-user-plus"></i>
                    Créer mon compte
                </button>
            </form>

            <div class="connexion-link">
                <p>Vous avez déjà un compte ?</p>
                <a href="../connexion/connexion.php">
                    Se connecter
                </a>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>
    <script src="inscription.js"></script>

</body>

</html>