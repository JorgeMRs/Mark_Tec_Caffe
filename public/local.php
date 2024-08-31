<?php
require '../src/db/db_connect.php';

$conn = getDbConnection();

$sql = "SELECT nombre, direccion, pais, ciudad, tel FROM sucursal";
$result = $conn->query($sql);

$locations = [];

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $locations[$row['pais']] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Descubre los locales de Café Sabrosos en todo el mundo. Disfruta de nuestro café de alta calidad y ambiente acogedor.">
    <meta name="keywords" content="Cafe, Sabrosos, Cafe Sabrosos, Sabrosos Cafe, locales, internacional">
    <meta name="author" content="Mark Tec">
    <title>Café Sabrosos - Locales Internacionales</title>
    <link rel="stylesheet" href="assets/css/stylelocal.css">
    <script src="https://kit.fontawesome.com/3047b62e1b.js" crossorigin="anonymous"></script>
    <link rel="icon" href="assets/img/logo-removebg-preview.png">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="body-local">
<header>
        <nav>
            <div class="logo">
                <a href="/" class="logo-link">
                    <img src="/public/assets/img/logo-removebg-preview.png" alt="Logo" class="logo-image" />
                    <h1>Café Sabrosos</h1>
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="local.php">Locales</a></li>
                <li><a href="tienda.php">Productos</a></li>
                <li><a href="#">Ofertas</a></li>
                <li><a href="#">Reservas</a></li>
                <li><a href="contactos.html">Contacto</a></li>
                <li>
                    <a href="/public/cuenta.php"><img src="/public/assets/img/image.png" alt="Usuario"
                            class="user-icon" /></a>
                </li>
                <div class="cart">
                    <a href="carrito.html">
                        <img src="/public/assets/img/cart.png" alt="Carrito" />
                        <span id="cart-counter" class="cart-counter">0</span>
                    </a>
                </div>
            </ul>
        </nav>
    </header>
<br>
    <main>
        <section class="hero">
            <h2>Nuestros Locales</h2>
            <p>Descubre la magia de Café Sabrosos alrededor del mundo</p>
        </section>
        
        <section class="mapa-world">
    <div class="map-container">
        <img src="/public/assets/img/europe.svg" alt="Mapa mundial con ubicaciones de Café Sabrosos">

        <!-- France -->
        <?php if(isset($locations['Francia'])): ?>
        <div class="pin francia" data-country="Francia">
            <span class="pin-dot"></span>
            <div class="pin-info">
                <img src="assets/img/sucursales/paris.jpg" class="pin-image" alt="Café Sabrosos en París">
                <div class="pin-details">
                    <h3><?php echo $locations['Francia']['ciudad']; ?>, <?php echo $locations['Francia']['pais']; ?></h3>
                    <p><?php echo $locations['Francia']['direccion']; ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Germany -->
        <?php if(isset($locations['Alemania'])): ?>
        <div class="pin alemania" data-country="Alemania">
            <span class="pin-dot"></span>
            <div class="pin-info">
                <img src="assets/img/sucursales/berlin.png" class="pin-image" alt="Café Sabrosos en Berlín">
                <div class="pin-details">
                    <h3><?php echo $locations['Alemania']['ciudad']; ?>, <?php echo $locations['Alemania']['pais']; ?></h3>
                    <p><?php echo $locations['Alemania']['direccion']; ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Portugal -->
        <?php if(isset($locations['Portugal'])): ?>
        <div class="pin portugal" data-country="Portugal">
            <span class="pin-dot"></span>
            <div class="pin-info">
                <img src="assets/img/sucursales/lisboa.jpg" class="pin-image" alt="Café Sabrosos en Lisboa">
                <div class="pin-details">
                    <h3><?php echo $locations['Portugal']['ciudad']; ?>, <?php echo $locations['Portugal']['pais']; ?></h3>
                    <p><?php echo $locations['Portugal']['direccion']; ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Spain -->
        <?php if(isset($locations['España'])): ?>
        <div class="pin españa" data-country="España">
            <span class="pin-dot"></span>
            <div class="pin-info">
                <img src="assets/img/sucursales/madrid.jpg" class="pin-image" alt="Café Sabrosos en Barcelona">
                <div class="pin-details">
                    <h3><?php echo $locations['España']['ciudad']; ?>, <?php echo $locations['España']['pais']; ?></h3>
                    <p><?php echo $locations['España']['direccion']; ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<script>
 document.querySelectorAll('.pin').forEach(pin => {
        const pinDot = pin.querySelector('.pin-dot');
        const pinInfo = pin.querySelector('.pin-info');
        const countryLabel = pin.querySelector('.pin-country-label');

        pinDot.addEventListener('mouseover', () => {
            pinInfo.style.opacity = '1';
            pinInfo.style.transform = 'translateX(-50%) translateY(0)';
            pin.classList.add('hover');  // Add a class to handle ::after
        });

        pinDot.addEventListener('mouseout', () => {
            pinInfo.style.opacity = '0';
            pinInfo.style.transform = 'translateX(-50%) translateY(10px)';
            pin.classList.remove('hover');
        });

        pin.addEventListener('mouseleave', () => {
            pinInfo.style.opacity = '0';
            pinInfo.style.transform = 'translateX(-50%) translateY(10px)';
            pin.classList.remove('hover');
        });
    });
</script>
<section class="locations-grid">
    <!-- France -->
    <?php if(isset($locations['Francia'])): ?>
    <div class="location-card">
        <img src="assets/img/sucursales/paris.jpg" alt="Café Sabrosos en París">
        <h3><?php echo $locations['Francia']['ciudad']; ?>, <?php echo $locations['Francia']['pais']; ?></h3>
        <p><?php echo $locations['Francia']['direccion']; ?></p>
        <p>Teléfono: <?php echo $locations['Francia']['tel']; ?></p>
    </div>
    <?php endif; ?>

    <!-- Germany -->
    <?php if(isset($locations['Alemania'])): ?>
    <div class="location-card">
        <img src="assets/img/sucursales/berlin.png" alt="Café Sabrosos en Berlín">
        <h3><?php echo $locations['Alemania']['ciudad']; ?>, <?php echo $locations['Alemania']['pais']; ?></h3>
        <p><?php echo $locations['Alemania']['direccion']; ?></p>
        <p>Teléfono: <?php echo $locations['Alemania']['tel']; ?></p>
    </div>
    <?php endif; ?>

    <!-- Portugal -->
    <?php if(isset($locations['Portugal'])): ?>
    <div class="location-card">
        <img src="assets/img/sucursales/lisboa.jpg" alt="Café Sabrosos en Lisboa">
        <h3><?php echo $locations['Portugal']['ciudad']; ?>, <?php echo $locations['Portugal']['pais']; ?></h3>
        <p><?php echo $locations['Portugal']['direccion']; ?></p>
        <p>Teléfono: <?php echo $locations['Portugal']['tel']; ?></p>
    </div>
    <?php endif; ?>

    <!-- Spain -->
    <?php if(isset($locations['España'])): ?>
    <div class="location-card">
        <img src="assets/img/sucursales/madrid.jpg" alt="Café Sabrosos en Madrid">
        <h3><?php echo $locations['España']['ciudad']; ?>, <?php echo $locations['España']['pais']; ?></h3>
        <p><?php echo $locations['España']['direccion']; ?></p>
        <p>Teléfono: <?php echo $locations['España']['tel']; ?></p>
    </div>
    <?php endif; ?>
</section>
    </main>

    <?php include 'templates/footer.html';?>
</body>
</html>
