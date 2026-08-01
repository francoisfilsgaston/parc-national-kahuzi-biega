<?php
include "includes/header.php";
?>
<main class="content">
    <section class="hero">
        <div class="hero-container">
            <h1>Découvrez les merveuilles de Parc National de Kahuzi Biega</h1>
            <p>Une aventure exceptionnelle entre montagnes, forets tropicales et gorilles de Grauer.</p>
        </div>
    </section>

    <section class="presentation" id="presentation">
        <div class="container">
            <div class="presentation-image">
                <img src="./assets/images/foret.jpg" alt="image presentation">
            </div>
            <div class="presentation-texte">
                <p>
                    Situé dans la province du sud-kivu, le Parc National de Kahuzi Biega est l'une des plus
                    importantes aires protégées de la République Démocratique du congo.
                </p>
                <p>
                    Il est célèbre par ses gorilles de Grauer et sa diversité exceptionnelle.
                </p>
                <a href="./about.php">En savoir plus</a>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="stats-container">
            <div class="start-card">
                <h3>1970</h3>
                <p>Année de creation</p>
            </div>
            <div class="start-card">
                <h3>6000</h3>
                <p>Km<sup>2 </sup> de superficie</p>
            </div>
            <div class="start-card">
                <h3>1980</h3>
                <p>Patrimoine UNESCO</p>
            </div>
            <div class="start-card">
                <h3>500+</h3>
                <p>Espèces animales</p>
            </div>
        </div>
    </section>

    <section class="activites">
        <div class="container">
            <h2>Activités populaires</h2>
            <div id="activitiesContainer" class="activites-grid">
                Chargement...
            </div>
            <div id="allActivitiesBtn" class="all-activities-btn">
            </div>
        </div>
    </section>

</main>
<?php include "includes/footer.php"; ?>