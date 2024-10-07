<!DOCTYPE html>
<?php 
$pageTitle = 'Café Sabrosos - Reservas';
$customCSS = [
    '/public/assets/css/reservas.css',
    '/public/assets/css/nav.css',
    '/public/assets/css/footer.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'
];

include 'templates/head.php' ?>

<body>
    <header>
        <?php include 'templates/nav.php' ?>
    </header>

    <div class="container">
        <h1>Reserva tu Mesa en Café Sabrosos</h1>
        <div class="grid">
            <!-- Sucursal 1 -->
            <div class="sucursal-card">
                <img src="/public/assets/img/sucursales/lisboa.jpg" alt="Sucursal 1" class="sucursal-image">
                <div class="sucursal-info">
                    <h3>Café Sabrosos Lisboa</h3>
                    <p>Dirección: Rua de São Bento 123, Portugal</p>
                    <p>Teléfono: +351 213 456 789</p>
                    <p>Capacidad: 50 personas</p>
                    <a href="mesas.php?sucursal=1" class="btn-reservar">Hacer Reserva</a>
                </div>
            </div>

            <!-- Sucursal 2 -->
            <div class="sucursal-card">
                <img src="/public/assets/img/sucursales/madrid.jpg" alt="Sucursal 2" class="sucursal-image">
                <div class="sucursal-info">
                    <h3>Café Sabrosos Madrid</h3>
                    <p>Dirección: Calle Gran Vía 45, España</p>
                    <p>Teléfono: +34 912 345 678</p>
                    <p>Capacidad: 70 personas</p>
                    <a href="mesas.php?sucursal=2" class="btn-reservar">Hacer Reserva</a>
                </div>
            </div>

            <!-- Sucursal 3 -->
            <div class="sucursal-card">
                <img src="/public/assets/img/sucursales/berlin.png" alt="Sucursal 3" class="sucursal-image">
                <div class="sucursal-info">
                    <h3>Café Sabrosos Berlín</h3>
                    <p>Dirección: Kurfürstendamm 100, Alemania</p>
                    <p>Teléfono: +49 30 12345678</p>
                    <p>Capacidad: 80 personas</p>
                    <a href="mesas.php?sucursal=3" class="btn-reservar">Hacer Reserva</a>
                </div>
            </div>

            <!-- Sucursal 4 -->
            <div class="sucursal-card">
                <img src="/public/assets/img/sucursales/paris.jpg" alt="Sucursal 4" class="sucursal-image">
                <div class="sucursal-info">
                    <h3>Café Sabrosos París</h3>
                    <p>Dirección: Boulevard Saint-Germain 56, Francia</p>
                    <p>Teléfono: +33 1 2345 6789</p>
                    <p>Capacidad: 60 personas</p>
                    <a href="mesas.php?sucursal=4" class="btn-reservar">Hacer Reserva</a>
                </div>
            </div>
        </div>
    </div>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <?php include 'templates/footer.php' ?>
    <script src="/public/assets/js/updateCartCounter.js"></script>
</body>

</html>