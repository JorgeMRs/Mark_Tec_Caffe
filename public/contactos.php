<!DOCTYPE html>
<?php 

$pageTitle = 'Café Sabrosos - Contactanos';

$customCSS = [
    '/public/assets/css/contactos.css',
    '/public/assets/css/nav-blur.css',
    '/public/assets/css/footer.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'

];
$customJS = [
  '/public/assets/js/languageSelect.js',
  '/public/assets/js/updateCartCounter.js'
];

include 'templates/head.php' ?>

<style>
    .loader {
        width: 30px;
        height: 30px;
        /* Asegura que tenga un tamaño fijo */
        --b: 8px;
        aspect-ratio: 1;
        border-radius: 50%;
        padding: 1px;
        background: conic-gradient(#0000 10%, #1B0D0B) content-box;
        -webkit-mask:
            repeating-conic-gradient(#0000 0deg, #000 1deg 20deg, #0000 21deg 36deg),
            radial-gradient(farthest-side, #0000 calc(100% - var(--b) - 1px), #000 calc(100% - var(--b)));
        -webkit-mask-composite: destination-in;
        mask-composite: intersect;
        animation: l4 1s infinite steps(10);
        display: none;
        /* Oculto inicialmente */
    }

    @keyframes l4 {
        to {
            transform: rotate(1turn);
        }
    }

    .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #DAA520;
        color: white;
        padding: 15px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        min-width: 150px;
        /* Ancho mínimo para que no cambie */
        height: 50px;
        /* Altura fija para evitar que cambie */
        position: relative;
    }

    .btn:hover .loader {
        background: conic-gradient(#0000 10%, #DAA520);
        /* Cambia el color del loader al hacer hover */
    }

    .submit-text {
        font-size: 16px;
        margin-right: 10px;
    }

    #response-message {
        margin-bottom: 15px;
        font-size: 16px;
        color: #333;
    }
</style>

<body>
    <?php include 'templates/nav-blur.php' ?>
    <main>
        <div class="wrapper">
        <h1 data-translate="contact.title">¡Ponete en contacto con nosotros!</h1>
<div class="input-box">
    <form method="POST" action="/src/email/contactoEmail.php" id="contact-form">
        <label for="name" data-translate="contact.name_label">Nombre</label>
        <input id="name" name="name" type="text" class="feedback-input" required /> <br>
        
        <label for="email" data-translate="contact.email_label">Email</label>
        <input id="email" name="email" type="email" class="feedback-input" required /><br>
        
        <label for="subject" data-translate="contact.subject_label">Asunto</label>
        <input id="subject" type="text" name="subject" class="feedback-input" required /><br>
        
        <label for="message" data-translate="contact.message_label">Comentario o mensaje</label>
        <textarea id="message" name="message" class="feedback-input" required></textarea><br>
        
        <div id="response-message" data-translate="contact.response_message"></div>
        
        <button class="btn" type="submit">
            <span class="submit-text" data-translate="contact.submit_button">ENVIAR</span>
        </button>
    </form>
</div>

                        <div class="loader" style="display: none;"></div>
                    </button>

                </form>
            </div>
        </div>
    </div>
</main>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <?php include 'templates/footer.php' ?>
</body>
<script src="/public/assets/js/updateCartCounter.js"></script>
<script src="/public/assets/js/contacto.js"></script>
<script src="/public/assets/js/languageSelect.js"></script>

</html>