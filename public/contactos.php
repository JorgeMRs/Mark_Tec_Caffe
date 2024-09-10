<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contactos Cafe Sabrosos">
    <meta name="keywords" content="Cafe, Sabrosos, Cafe Sabrosos, Sabrosos Cafe">
    <meta name="author" content="Eduardo Delgado, Luciano Britos, Jose Sanchez, Jorge Martinez">
    <title>Contacto</title>
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-64x64.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/contactos.css">
    <link rel="stylesheet" href="assets/css/nav-blur.css">
    <link rel="stylesheet" href="assets/css/footer.css">

</head>

<body>
    <?php include 'templates/nav-blur.php' ?>
    <main>
        <div class="wrapper">
            <h1>¡Ponete en contacto con nosotros!
            </h1>
            <div class="input-box">
                <form method="POST" action="/src/email/contactoEmail.php" id="contact-form">
                    <label for="name">Nombre</label>
                    <input id="name" name="name" type="text" class="feedback-input" required /> <br>
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" class="feedback-input" required /><br>
                    <label for="subject">Asunto</label>
                    <input id="subject" type="text" name="subject" class="feedback-input" required /><br>
                    <label for="message">Comentario o mensaje</label>
                    <textarea id="message" name="message" class="feedback-input" required></textarea><br>
                    <div id="loaderContainer" style="display: none;">Cargando...</div>
                    <div id="message-container" style="display: none;">
                        <span id="message-text"></span>
                    </div>
                    <input class="btn" type="submit" value="ENVIAR" />
                </form>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#contact-form');
            const loaderContainer = document.querySelector('#loaderContainer');
            const submitButton = document.querySelector('input[type="submit"]');
            const modalBackground = document.querySelector('#message-container');
            const modalMessage = document.querySelector('#message-text');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);

                loaderContainer.style.display = 'block';
                submitButton.style.display = 'none';

                fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        loaderContainer.style.display = 'none';
                        submitButton.style.display = 'block';

                        // Mostrar mensaje basado en la respuesta del servidor
                        if (data.includes('El mensaje se ha enviado correctamente.')) {
                            modalMessage.textContent = 'Correo enviado correctamente';
                        } else {
                            modalMessage.textContent = 'Hubo un error al enviar el mensaje. Por favor, intenta de nuevo.';
                        }

                        modalBackground.style.display = 'flex';
                        form.reset();
                    })
                    .catch(error => {
                        loaderContainer.style.display = 'none';
                        submitButton.style.display = 'block';
                        modalMessage.textContent = 'Hubo un error al enviar el mensaje. Por favor, intenta de nuevo.';
                        console.error('Error:', error);
                    });
            });
        });
    </script>
    <?php include 'templates/footer.php' ?>
</body>
<script src="/public/assets/js/updateCartCounter.js"></script>

</html>