<?php
include "includes/header.php";
?>
<main class="content">
    <section class="activities-header">
        <div class="container">
            <h1>Nos Activités</h1>
            <p>Découvrez toutes les experiences à vivre au coeur de la nature.</p>
        </div>
    </section>

    <section class="search-section">
        <div class="container">
            <h2>Que souhaitez vous découvrir ?</h2>
        </div>
        <div class="search-box">
            <input type="text" id="activitySearch" placeholder="Rechercher une activité...">
            <button id="searchBtn">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </section>

    <section class="allActivities-section">
        <div id="allActivities" class="activites-grid">
            Chargement des activites...
        </div>
    </section>

    <!-- Popup -->
    <div id="activityPopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="fermerPopup()">
                <i class="fa-solid fa-xmark" style="color: red;"></i>
            </span>
            <img id="popupImage">
            <h2 id="popupTitre"></h2>
            <p id="popupDescription"></p>
            <p id="popupDuree"></p>
            <p id="popupPrix"></p>
        </div>
    </div>
</main>
<?php include "includes/footer.php"; ?>