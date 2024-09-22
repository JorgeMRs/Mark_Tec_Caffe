<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Sabrosos - Política de Privacidad</title>
    <link rel="stylesheet" href="/public/assets/css/footer.css">
    <link rel="stylesheet" href="/public/assets/css/nav.css">
    <link rel="stylesheet" href="/public/assets/css/politicas.css">
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-64x64.png">
    <link rel="icon" type="image/x-icon" href="/public/assets/img/icons/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>
<body>
    <header>
        <?php include 'templates/nav.php'; ?>
    </header>
    <main>
        <section class="privacy-policy">
            <h1>Política de Privacidad</h1>
            <p>En Café Sabrosos, respetamos tu privacidad y estamos comprometidos a proteger tus datos personales. Esta Política de Privacidad explica cómo recopilamos, usamos y protegemos tu información cuando utilizas nuestra aplicación web.</p>
            
            <h2>1. Información que Recopilamos</h2>
            <p>Recopilamos información personal que nos proporcionas directamente, como tu nombre, dirección de correo electrónico y detalles de contacto. Si eliges registrarte mediante proveedores externos como Google, también recopilaremos información proporcionada por estos, como tu UID, correo electronico y foto de perfil.</p>
            
            <h2>2. Uso de la Información</h2>
            <p>Utilizamos la información que recopilamos para procesar tus pedidos, mejorar nuestros servicios y comunicarnos contigo. También podemos usar tus datos para enviarte actualizaciones y promociones relacionadas con Café Sabrosos.</p>
            

            <h2>3. Uso de Cookies</h2>
            <p>En nuestra aplicación web utilizamos cookies para mejorar tu experiencia. Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas nuestro sitio. Estas nos permiten recordar información sobre tu sesión, como tus preferencias de idioma y los productos que has agregado a tu carrito.</p>
            <p>Al continuar navegando en nuestro sitio, aceptas el uso de cookies. Puedes optar por desactivar las cookies en cualquier momento a través de la configuración de tu navegador, pero esto puede afectar la funcionalidad de nuestra aplicación.</p>

            <h2>4. Compartición de Información</h2>
            <p>No compartimos tu información personal con terceros, excepto cuando sea necesario para procesar tus pedidos o cumplir con las leyes aplicables. Si utilizas proveedores externos para registrarte, tu información puede ser gestionada por esos servicios, y te recomendamos revisar sus políticas de privacidad.</p>
            
            <h2>5. Seguridad de la Información</h2>
            <p>Implementamos medidas de seguridad para proteger tu información personal contra el acceso no autorizado, la alteración, divulgación o destrucción. Sin embargo, ningún sistema es completamente seguro, y no podemos garantizar la seguridad absoluta de tus datos.</p>
            
            <h2>6. Tus Derechos</h2>
            <p>Tienes derecho a acceder, corregir o eliminar tu información personal que tenemos sobre ti. Si deseas ejercer estos derechos, por favor contáctanos utilizando la información de contacto proporcionada a continuación.</p>
            
            <h2>7. Cambios en la Política</h2>
            <p>Podemos actualizar esta Política de Privacidad de vez en cuando. Cualquier cambio se publicará en esta página, y tu uso continuo de nuestra aplicación web constituye aceptación de la política actualizada.</p>
            
            <h2>8. Información de Contacto</h2>
            <p>Si tienes alguna pregunta o inquietud sobre nuestra Política de Privacidad, por favor contáctanos en soporte@cafesabrosos.com.</p>
        </section>
    </main>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?> 
    <?php include 'templates/footer.php'; ?>
</body>
</html>
