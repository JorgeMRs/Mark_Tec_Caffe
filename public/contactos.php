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
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/nav-blur.css">
    <link rel="stylesheet" href="assets/css/footer.css">

</head>

<body>
        <?php include 'templates/nav.php' ?>
    <main>
        <div class="wrapper">
            <h1>¡Ponete en contacto con nosotros!
            </h1>
            <div class="input-box">
                <form method="post" action="../src/email/send_email.php" id="contact-form">
                    <label for="name">Nombre</label>
                    <input id="name" name="name" type="text" class="feedback-input"required /> <br>
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" class="feedback-input" required /><br>
                    <label for="subject">Asunto</label>
                    <input id="subject" type="text" name="subject" class="feedback-input" required /><br>
                    <label for="message">Comentario o mensaje</label>
                    <textarea id="message" name="message" class="feedback-input" required></textarea><br>
                    <input class="btn" type="submit" value="ENVIAR" />
                </form>
            </div>
        </div>
    </main>
    <?php include 'templates/footer.php' ?>
</body>

</html>