<!DOCTYPE html>
<?php 

$pageTitle = 'Café Sabrosos - Políticas de Eliminación de Cuenta';

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
        <section class="data-deletion-policy">
            <h1 id="deletion-policy-title">Política de Eliminación de Datos</h1>
            <section>
                <h2 id="deletion-section-1">1. ¿Qué sucede al eliminar tu cuenta?</h2>
                <p id="deletion-paragraph-1">Al eliminar tu cuenta en Café Sabrosos:</p>
                <ul>
                    <li id="deletion-list-item-1">Perderás acceso a todos los datos asociados con tu cuenta.</li>
                    <li id="deletion-list-item-2">Se eliminarán tus datos personales almacenados en tu perfil, incluyendo:</li>
                    <ul>
                        <li id="deletion-sublist-item-1">Historial de pedidos.</li>
                        <li id="deletion-sublist-item-2">Reservas realizadas.</li>
                        <li id="deletion-sublist-item-3">Retroalimentación proporcionada.</li>
                    </ul>
                </ul>
            </section>
            <section>
                <h2 id="deletion-section-2">2. Consecuencias de la eliminación</h2>
                <p id="deletion-paragraph-2">Una vez que tu cuenta sea eliminada:</p>
                <ul>
                    <li id="deletion-list-item-3">No podrás recuperar la información que se haya perdido, incluyendo:</li>
                    <ul>
                        <li id="deletion-sublist-item-4">Historial de pedidos.</li>
                        <li id="deletion-sublist-item-5">Detalles de reservas anteriores.</li>
                        <li id="deletion-sublist-item-6">Comentarios y retroalimentación proporcionados.</li>
                    </ul>
                    <li id="deletion-list-item-4">El proceso es irreversible y no hay manera de deshacer la eliminación.</li>
                </ul>
            </section>
            <section>
                <h2 id="deletion-section-3">3. Política de datos</h2>
                <p id="deletion-paragraph-3">De acuerdo con nuestra política de privacidad y en cumplimiento con el <a href="https://gdpr.eu/" target="_blank">Reglamento General de Protección de Datos (GDPR)</a>:</p>
                <ul>
                    <li id="deletion-list-item-5">Los datos eliminados serán borrados permanentemente de nuestra base de datos.</li>
                    <li id="deletion-list-item-6">No serán accesibles para su recuperación en el futuro.</li>
                    <li id="deletion-list-item-7"><strong>Podemos mantener registros de la eliminación por un periodo de tiempo limitado por razones legales.</strong></li>
                    <li id="deletion-list-item-8">Los registros de la eliminación se mantendrán por un periodo de <strong>6 meses</strong>.</li>
                </ul>
            </section>
            <section>
                <h2 id="deletion-section-4">4. Procedimiento de Eliminación</h2>
                <p id="deletion-paragraph-4">Para eliminar tu cuenta, sigue estos pasos:</p>
                <ol>
                    <li id="deletion-step-1">Accede a tu cuenta y dirígete a la sección de configuración.</li>
                    <li id="deletion-step-2">Selecciona la opción para eliminar tu cuenta.</li>
                    <li id="deletion-step-3">Confirma la acción introduciendo tu contraseña y aceptando los términos.</li>
                    <li id="deletion-step-4">Recibirás un correo de confirmación con un enlace para completar el proceso.</li>
                </ol>
            </section>
            <section>
                <h2 id="deletion-section-5">5. Contacto y Soporte</h2>
                <p id="deletion-paragraph-5">Si tienes preguntas sobre la eliminación de tu cuenta o necesitas asistencia, contáctanos:</p>
                <ul>
                    <li id="deletion-list-item-9">Correo electrónico: <a href="mailto:support@cafesabrosos.com">support@cafesabrosos.com</a></li>
                    <li id="deletion-list-item-10">Teléfono: +34 912 345 678</li>
                    <li id="deletion-list-item-11">Horario de atención: Lunes a Viernes, 9:00 - 18:00</li>
                </ul>
            </section>
        </section>

    </main>
    <?php include 'templates/footer.php'; ?>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
</body>

</html>