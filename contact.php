<?php
include "includes/header.php";
?>
<main class="content">

    <section class="contact-header">
        <span class="contact-icon">
            <i class="fa-solid fa-envelope"></i>
        </span>

        <h1> Contactez-nous </h1>
        <p>
            Une question, une suggestion ou besoin
            d'informations supplémentaires ?
            Envoyez-nous un message.
        </p>
    </section>

    <section class="contact-content">
        <div class="contact-info">
            <h2> Parlons-nous </h2>
            <p>
                Nous sommes à votre disposition
                pour répondre à vos questions concernant
                les activités touristiques du Sud-Kivu.
            </p>


            <div class="info-item">
                <span class="info-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </span>
                <div>
                    <h3>Localisation </h3>
                    <p>
                        Sud-Kivu, République démocratique du Congo
                    </p>
                </div>
            </div>
            <div class="info-item">
                <span class="info-icon">
                    <i class="fa-solid fa-envelope"></i>
                </span>

                <div>
                    <h3>Email</h3>
                    <p> pnkb@tourisme.com</p>
                </div>
            </div>

            <div class="info-item">
                <span class="info-icon">
                    <i class="fa-solid fa-phone"></i>
                </span>

                <div>
                    <h3>Téléphone</h3>

                    <p>
                        +243 970 807 962
                    </p>
                </div>
            </div>
        </div>

        <div class="contact-form-container">
            <h2> Envoyer un message</h2>
            <form id="contactForm">
                <div class="form-group">
                    <label for="nom">Nom complet </label>
                    <input type="text" id="nom" placeholder="Votre nom" required>
                </div>

                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" placeholder="exemple@email.com" required>
                </div>

                <div class="form-group">
                    <label for="message"> Message</label>
                    <textarea id="message" placeholder="Écrivez votre message..." required></textarea>
                </div>

                <div id="responseMessage" class="response-message"></div>

                <button type="submit" class="btn-contact">
                    <i class="fa-solid fa-paper-plane"></i>
                    Envoyer le message
                </button>

            </form>
        </div>

    </section>

</main>
<?php include "includes/footer.php"; ?>