<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Kahuzi-Biega</title>
    <link rel="stylesheet" href="./connexion.css">

</head>

<body>

    <main class="connexion-container">
        <div class="connexion-card">
            <div class="connexion-header">
                <h1>Bienvenue</h1>

                <p> Connectez-vous pour continuer</p>

            </div>

            <form id="connexionForm">
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" placeholder="exemple@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" placeholder="Votre mot de passe" required>
                </div>

                <div id="message" class="message">
                </div>

                <button type="submit" class="btn-connexion"> Se connecter</button>
            </form>

            <div class="inscription-link">

                <p>Vous n'avez pas encore de compte ?</p>
                <a href="../inscription/inscription.php">Créer un compte</a>
            </div>
        </div>
    </main>

    <script src="./connexion.js"></script>
</body>

</html>