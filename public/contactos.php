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
<style>
.loader {
    width: 30px;
    height: 30px; /* Asegura que tenga un tamaño fijo */
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
    display: none; /* Oculto inicialmente */
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
    min-width: 150px; /* Ancho mínimo para que no cambie */
    height: 50px; /* Altura fija para evitar que cambie */
    position: relative;
}
.btn:hover .loader {
        background: conic-gradient(#0000 10%, #DAA520); /* Cambia el color del loader al hacer hover */
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
    </main>
    <script>
  document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#contact-form');
            const loader = document.querySelector('.loader');
            const submitText = document.querySelector('.submit-text');
            const responseMessage = document.querySelector('#response-message');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);

                // Ocultar texto de envío y mostrar loader
                submitText.style.display = 'none'; // Ocultar el texto
                loader.style.display = 'block'; // Mostrar el loader
                responseMessage.textContent = ''; // Limpiar mensaje de respuesta

                fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        // Mostrar texto de envío y ocultar loader
                        loader.style.display = 'none';
                        submitText.style.display = 'block'; // Mostrar de nuevo el texto

                        // Mostrar mensaje basado en la respuesta del servidor
                        if (data.includes('El mensaje se ha enviado correctamente.')) {
                            responseMessage.textContent = 'Correo enviado correctamente';
                            responseMessage.style.color = 'green'; // Color de éxito
                        } else {
                            responseMessage.textContent = 'Hubo un error al enviar el mensaje. Por favor, intenta de nuevo.';
                            responseMessage.style.color = 'red'; // Color de error
                        }

                        form.reset();
                    })
                    .catch(error => {
                        loader.style.display = 'none';
                        submitText.style.display = 'block'; // Mostrar de nuevo el texto
                        responseMessage.textContent = 'Hubo un error al enviar el mensaje. Por favor, intenta de nuevo.';
                        responseMessage.style.color = 'red'; // Color de error
                        console.error('Error:', error);
                    });
            });
        });
    </script>
    <?php include 'templates/footer.php' ?>
</body>
<script src="/public/assets/js/updateCartCounter.js"></script>

</html>