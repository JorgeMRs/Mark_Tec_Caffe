<!DOCTYPE html>
<?php 

$pageTitle = 'Café Sabrosos - Políticas de Privacidad';

$customCSS = [
    '/public/assets/css/politicas.css',
    '/public/assets/css/nav.css',
    '/public/assets/css/footer.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'
];
$customJS = [
    '/public/assets/js/languageSelect.js',
    '/public/assets/js/updateCartCounter.js'
  ];
include 'templates/head.php' ?>

<body>
    <header>
        <?php include 'templates/nav.php'; ?>
    </header>
    <main>
        <section class="privacy-policy">
            <h1 id="privacy-policy-title">Política de Privacidad</h1>
            <p id="privacy-policy-intro">En Café Sabrosos, respetamos tu privacidad y estamos comprometidos a proteger tus datos personales. Esta Política de Privacidad explica cómo recopilamos, usamos y protegemos tu información cuando utilizas nuestra aplicación web.</p>

            <h2 id="info-section">1. Información que Recopilamos</h2>
            <p id="paragraph-1">Recopilamos información personal que nos proporcionas directamente, como tu nombre, dirección de correo electrónico y detalles de contacto. Si eliges registrarte mediante proveedores externos como Google, también recopilaremos información proporcionada por estos, como tu UID, correo electrónico y foto de perfil.</p>

            <h2 id="use-section">2. Uso de la Información</h2>
            <p id="paragraph-2">Utilizamos la información que recopilamos para procesar tus pedidos, mejorar nuestros servicios y comunicarnos contigo. También podemos usar tus datos para enviarte actualizaciones y promociones relacionadas con Café Sabrosos.</p>

            <h2 id="cookies-section">3. Uso de Cookies</h2>
            <p id="paragraph-3">En nuestra aplicación web utilizamos cookies para mejorar tu experiencia. Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas nuestro sitio. Estas nos permiten recordar información sobre tu sesión, como tus preferencias de idioma y los productos que has agregado a tu carrito.</p>
            <p id="paragraph-4">Al continuar navegando en nuestro sitio, aceptas el uso de cookies. Puedes optar por desactivar las cookies en cualquier momento a través de la configuración de tu navegador, pero esto puede afectar la funcionalidad de nuestra aplicación.</p>

            <h2 id="sharing-section">4. Compartición de Información</h2>
            <p id="paragraph-5">No compartimos tu información personal con terceros, excepto cuando sea necesario para procesar tus pedidos o cumplir con las leyes aplicables. Si utilizas proveedores externos para registrarte, tu información puede ser gestionada por esos servicios, y te recomendamos revisar sus políticas de privacidad.</p>

            <h2 id="security-section">5. Seguridad de la Información</h2>
            <p id="paragraph-6">Implementamos medidas de seguridad para proteger tu información personal contra el acceso no autorizado, la alteración, divulgación o destrucción. Sin embargo, ningún sistema es completamente seguro, y no podemos garantizar la seguridad absoluta de tus datos.</p>

            <h2 id="rights-section">6. Tus Derechos</h2>
            <p id="paragraph-7">Tienes derecho a acceder, corregir o eliminar tu información personal que tenemos sobre ti. Si deseas ejercer estos derechos, por favor contáctanos utilizando la información de contacto proporcionada a continuación.</p>

            <h2 id="changes-section">7. Cambios en la Política</h2>
            <p id="changes-paragraph">Podemos actualizar esta Política de Privacidad de vez en cuando. Cualquier cambio se publicará en esta página, y tu uso continuo de nuestra aplicación web constituye aceptación de la política actualizada.</p>

            <h2 id="contact-section">8. Información de Contacto</h2>
            <p id="contact-paragraph">Si tienes alguna pregunta o inquietud sobre nuestra Política de Privacidad, por favor contáctanos en soporte@cafesabrosos.com.</p>
        </section>
    </main>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <?php include 'templates/footer.php'; ?>

</body>

</html>