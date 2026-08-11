<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires activité</title>
    <link rel="stylesheet" href="../gestion_comment/commentaires.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>
    <header>
        <nav>
            <a href="../activite.php" class="back-button">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </nav>
    </header>
    <main class="commentaire-page">
        <section id="activiteDetails" class="activite-details">

            <!-- Chargement par JavaScript -->

        </section>

        <!-- Zone commentaires -->

        <section class="commentaires-section">
            <h2>Commentaires</h2>
            <div id="listeCommentaires">

            </div>

            <textarea id="message" placeholder="Écrire un commentaire...">

            </textarea>

            <button onclick="publierCommentaire()" class="cmt-btn-pub">Publier</button>

            </div>

        </section>
    </main>
    <script src="../gestion_comment/commentaires.js"></script>
</body>

</html>