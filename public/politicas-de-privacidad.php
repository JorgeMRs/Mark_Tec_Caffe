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
        <h1 data-translate="title">Política de Privacidad</h1>
<p data-translate="description">En Café Sabrosos, respetamos tu privacidad y estamos comprometidos a proteger tus datos personales. Esta Política de Privacidad explica cómo recopilamos, usamos y protegemos tu información cuando utilizas nuestra aplicación web.</p>

<h2 data-translate="section1:heading">1. Información que Recopilamos</h2>
<p data-translate="section1:content">Recopilamos información personal que nos proporcionas directamente, como tu nombre, dirección de correo electrónico y detalles de contacto. También podemos recopilar información sobre tu actividad en nuestra aplicación web para mejorar nuestros servicios.</p>

<h2 data-translate="section2:heading">2. Uso de la Información</h2>
<p data-translate="section2:content">Utilizamos la información que recopilamos para procesar tus pedidos, mejorar nuestros servicios y comunicarnos contigo. También podemos usar tus datos para enviarte actualizaciones y promociones relacionadas con Café Sabrosos.</p>

<h2 data-translate="section3:heading">3. Compartición de Información</h2>
<p data-translate="section3:content">No compartimos tu información personal con terceros, excepto cuando sea necesario para procesar tus pedidos o cumplir con las leyes aplicables. Podemos compartir tu información con proveedores de servicios que trabajan en nuestro nombre bajo estrictos acuerdos de confidencialidad.</p>

<h2 data-translate="section4:heading">4. Seguridad de la Información</h2>
<p data-translate="section4:content">Implementamos medidas de seguridad para proteger tu información personal contra el acceso no autorizado, la alteración, divulgación o destrucción. Sin embargo, ningún sistema es completamente seguro, y no podemos garantizar la seguridad absoluta de tus datos.</p>

<h2 data-translate="section5:heading">5. Tus Derechos</h2>
<p data-translate="section5:content">Tienes derecho a acceder, corregir o eliminar tu información personal que tenemos sobre ti. Si deseas ejercer estos derechos, por favor contáctanos utilizando la información de contacto proporcionada a continuación.</p>

<h2 data-translate="section6:heading">6. Cambios en la Política</h2>
<p data-translate="section6:content">Podemos actualizar esta Política de Privacidad de vez en cuando. Cualquier cambio se publicará en esta página, y tu uso continuo de nuestra aplicación web constituye aceptación de la política actualizada.</p>

<h2 data-translate="section7:heading">7. Información de Contacto</h2>
<p data-translate="section7:content">Si tienes alguna pregunta o inquietud sobre nuestra Política de Privacidad, por favor contáctanos en soporte@cafesabrosos.com.</p>

        </section>
    </main>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <?php include 'templates/footer.php'; ?>

</body>

</html>