<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas - Café Sabrosos</title>
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/reservas.css">
</head>
<body>
    <header>
        <?php include 'templates/nav.php'?>
    </header>

<div class="container">
<h1 data-translate="title">Reserva tu Mesa en Café Sabrosos</h1>
<div class="grid">
    <!-- Sucursal 1 -->
    <div class="sucursal-card">
        <img src="/public/assets/img/sucursales/lisboa.jpg" alt="Sucursal 1" class="sucursal-image">
        <div class="sucursal-info">
            <h3 data-translate="branches[0].name">Café Sabrosos Lisboa</h3>
            <p data-translate="branches[0].address">Dirección: Rua de São Bento 123, Portugal</p>
            <p data-translate="branches[0].phone">Teléfono: +351 213 456 789</p>
            <p data-translate="branches[0].capacity">Capacidad: 50 personas</p>
            <a href="mesas.php?sucursal=1" class="btn-reservar" data-translate="branches[0].button_text">Hacer Reserva</a>
        </div>
    </div>

    <!-- Sucursal 2 -->
    <div class="sucursal-card">
        <img src="/public/assets/img/sucursales/madrid.jpg" alt="Sucursal 2" class="sucursal-image">
        <div class="sucursal-info">
            <h3 data-translate="branches[1].name">Café Sabrosos Madrid</h3>
            <p data-translate="branches[1].address">Dirección: Calle Gran Vía 45, España</p>
            <p data-translate="branches[1].phone">Teléfono: +34 912 345 678</p>
            <p data-translate="branches[1].capacity">Capacidad: 70 personas</p>
            <a href="mesas.php?sucursal=2" class="btn-reservar" data-translate="branches[1].button_text">Hacer Reserva</a>
        </div>
    </div>

    <!-- Sucursal 3 -->
    <div class="sucursal-card">
        <img src="/public/assets/img/sucursales/berlin.png" alt="Sucursal 3" class="sucursal-image">
        <div class="sucursal-info">
            <h3 data-translate="branches[2].name">Café Sabrosos Berlín</h3>
            <p data-translate="branches[2].address">Dirección: Kurfürstendamm 100, Alemania</p>
            <p data-translate="branches[2].phone">Teléfono: +49 30 12345678</p>
            <p data-translate="branches[2].capacity">Capacidad: 80 personas</p>
            <a href="mesas.php?sucursal=3" class="btn-reservar" data-translate="branches[2].button_text">Hacer Reserva</a>
        </div>
    </div>

    <!-- Sucursal 4 -->
    <div class="sucursal-card">
        <img src="/public/assets/img/sucursales/paris.jpg" alt="Sucursal 4" class="sucursal-image">
        <div class="sucursal-info">
            <h3 data-translate="branches[3].name">Café Sabrosos París</h3>
            <p data-translate="branches[3].address">Dirección: Boulevard Saint-Germain 56, Francia</p>
            <p data-translate="branches[3].phone">Teléfono: +33 1 2345 6789</p>
            <p data-translate="branches[3].capacity">Capacidad: 60 personas</p>
            <a href="mesas.php?sucursal=4" class="btn-reservar" data-translate="branches[3].button_text">Hacer Reserva</a>
        </div>
    </div>
</div>
    </div>
        <?php include 'templates/footer.php'?>
        <script src="/public/assets/js/updateCartCounter.js"></script>
</body>
</html>