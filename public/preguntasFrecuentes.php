<!DOCTYPE html>
<?php 

$pageTitle = 'Café Sabrosos - Preguntas Frecuentes';

$customCSS = [
    '/public/assets/css/preguntasFrecuentes.css',
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
    <section class="faq-container">
        <div class="left-section">
            <h1>Preguntas frecuentes</h1>
            <div class="faq-card">
                <div class="faq-icon">
                    <img src="/public/assets/img/cartblack.png" alt="Pedido Icon" style="width: 2rem; height: 2rem;">
                </div>
                <button class="faq-button" onclick="showSection('pedido')">Consultas sobre mi pedido</button>
            </div>
            <div class="faq-card">
                <div class="faq-icon">
                    <img src="https://mcd-landings-q-static.appmcdonalds.com/uploads-quality/Credit_Card_603a7d92bb.png" alt="Pago Icon" style="width: 2rem; height: 2rem;">
                </div>
                <button class="faq-button" onclick="showSection('pago')">Información sobre el pago</button>
            </div>
            <p class="subheading">¿Aún no encuentras lo que buscabas?</p>
            <div class="additional-links">
                <a href="../public/maspreguntasfrecuentes.php">Aqui podras obtener mas respuestas</a>

            </div>
            <div class="support">
                <p>Atención al cliente</p>
                <a href="tel:+34 912 345 678">+34 912 345 678</a>
            </div>
        </div>

        <div class="right-section">
            <!-- Sección de Consultas sobre mi pedido -->
            <div id="pedido-section" class="faq-section">
                <h1>Consultas sobre mi pedido</h1>
                <div class="faq-item">
                    <button class="faq-question">¿Cómo puedo cancelar mi pedido?</button>
                    <div class="faq-answer">
                        <p>Para cancelar tu pedido, por favor contacta con nuestro servicio de atención al cliente lo antes posible para procesar la cancelación antes de que el pedido sea preparado.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">Como se cuando ir a buscar mi pedidio</button>
                    <div class="faq-answer">
                        <p>Una vez hecho el pedido podras ver su seguimento en el apartado de tu cuenta.</p>
                    </div>
                </div>
            </div>

            <!-- Sección de Información sobre el pago -->
            <div id="pago-section" class="faq-section" style="display:none;">
                <h1>Información sobre el pago</h1>
                <div class="faq-item">
                    <button class="faq-question">¿Qué medios de pago acepta Café sabrosos?</button>
                    <div class="faq-answer">
                        <p>Puedes realizar tu compra con tarjetas de débito y crédito, Mastercard, Visa y American Express. Además de aceptar PayPal, Google Pay y Apple Pay.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">Mi tarjeta ha sido rechazada</button>
                    <div class="faq-answer">
                        <p>Si tu tarjeta de débito ha sido rechazada, verifica, en primer lugar, en tu banco emisor si debe ser habilitada para compras en eCommerce, o Compras por internet en tu home banking.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php if (!isset($_COOKIE['cookie_preference'])) {
    include 'templates/cookies.php';
} ?>
<?php include 'templates/footer.php'; ?>

<script>
    const faqItems = document.querySelectorAll('.faq-question');
    faqItems.forEach(item => {
        item.addEventListener('click', () => {
            const answer = item.nextElementSibling;
            answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
        });
    });

    // Función para mostrar la sección seleccionada
    function showSection(section) {
        // Ocultar ambas secciones
        document.getElementById('pedido-section').style.display = 'none';
        document.getElementById('pago-section').style.display = 'none';

        // Mostrar la sección seleccionada
        if (section === 'pedido') {
            document.getElementById('pedido-section').style.display = 'block';
        } else if (section === 'pago') {
            document.getElementById('pago-section').style.display = 'block';
        }
    }
</script>
</body>
</html>
