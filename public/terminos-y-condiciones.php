<!DOCTYPE html>
<?php 

$pageTitle = 'Café Sabrosos - Términos y Condiciones';

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
    <section class="terms-conditions">
    <h1 id="terms-conditions-title">Términos y Condiciones</h1>
    <p id="terms-conditions-intro">¡Bienvenido a Café Sabrosos! Estos Términos y Condiciones establecen las reglas y regulaciones para el uso de nuestra aplicación web. Al acceder o utilizar nuestra aplicación web, aceptas cumplir con estos términos.</p>
    
    <h2 id="terms-conditions-section-1">1. Introducción</h2>
    <p id="terms-conditions-paragraph-1">Estos Términos y Condiciones rigen tu uso de nuestra aplicación web para realizar pedidos en Café Sabrosos. Si no estás de acuerdo con alguna parte de estos términos, no debes utilizar nuestra aplicación web.</p>
    
    <h2 id="terms-conditions-section-2">2. Registro de Cuenta</h2>
    <p id="terms-conditions-paragraph-2">Para utilizar ciertas funciones de nuestra aplicación web, es posible que necesites crear una cuenta. Eres responsable de mantener la confidencialidad de la información de tu cuenta y de todas las actividades que ocurran bajo tu cuenta. También puedes registrarte utilizando proveedores externos como Google. Al hacerlo, aceptas que recopilemos y usemos la información proporcionada por dicho proveedor, de acuerdo con nuestra Política de Privacidad.</p>
    
    <h2 id="terms-conditions-section-3">3. Pedidos y Pagos</h2>
    <p id="terms-conditions-paragraph-3">Al realizar un pedido, aceptas proporcionar información precisa sobre los artículos que deseas comprar y tus datos de contacto. Los pagos deben realizarse de acuerdo con los métodos de pago disponibles. Nos reservamos el derecho de cancelar cualquier pedido si sospechamos actividad fraudulenta.</p>
    
    <h2 id="terms-conditions-section-4">4. Recogida de Pedidos</h2>
    <p id="terms-conditions-paragraph-4">Si eliges recoger tu pedido, por favor hazlo a la hora especificada. Café Sabrosos no se hace responsable de retrasos o problemas con el proceso de recogida.</p>
    
    <h2 id="terms-conditions-section-5">5. Cancelaciones y Modificaciones</h2>
    <p id="terms-conditions-paragraph-5">Los pedidos pueden ser modificados o cancelados antes de que comience la preparación. Una vez que un pedido está en preparación, no puede ser cancelado ni modificado. Por favor, contacta con nosotros lo antes posible si necesitas hacer cambios.</p>
    
    <h2 id="terms-conditions-section-6">6. Conducta del Usuario</h2>
    <p id="terms-conditions-paragraph-6">Aceptas utilizar nuestra aplicación web de manera legal y no participar en ninguna actividad que pueda dañar nuestros servicios, interrumpir la experiencia del usuario o violar las leyes aplicables.</p>
    
    <h2 id="terms-conditions-section-7">7. Política de Privacidad</h2>
    <p id="terms-conditions-paragraph-7">Tu privacidad es importante para nosotros. Por favor, revisa nuestra Política de Privacidad para entender cómo recopilamos, usamos y protegemos tu información personal.</p>
    
    <h2 id="terms-conditions-section-8">8. Cambios en los Términos</h2>
    <p id="terms-conditions-paragraph-8">Podemos actualizar estos Términos y Condiciones de vez en cuando. Cualquier cambio se publicará en esta página, y tu uso continuo de nuestra aplicación web constituye aceptación de los términos actualizados.</p>
    
    <h2 id="terms-conditions-section-9">9. Información de Contacto</h2>
    <p id="terms-conditions-paragraph-9">Si tienes alguna pregunta o inquietud acerca de estos Términos y Condiciones, por favor contáctanos en soporte@cafesabrosos.com.</p>
</section>

    </main>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
