<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Sabrosos</title>
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-64x64.png">
    <link rel="icon" type="image/x-icon" href="/public/assets/img/icons/favicon.ico">
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <img src="/public/assets/img/logo-removebg-preview.png" alt="Logo" class="logo-image">
                <h1>Café Sabroso</h1>
            </div>

            <ul class="nav-links">
                <li><a href="/public/local.html">Locales</a></li>
                <li><a href="#">Productos</a></li>
                <li><a href="#">Ofertas</a></li>
                <li><a href="#">Reservas</a></li>
                <li><a href="/public/contactos.html">Contacto</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="/src/db/logout.php">Cerrar sesión</a></li>
                <?php endif; ?>
                <li><a href="/public/login.html"><img src="/public/assets/img/image.png" alt="Usuario" class="user-icon"></a></li>
            </ul>
        </nav>
        <div class="carousel-content">
            <h1>RECIEN HECHO, TODOS LOS DIAS</h1>
            <p>Café recién preparado con granos seleccionados para ofrecerte una experiencia inigualable</p>
            <div class="buttons">
                <a href="#order" class="btn-order">Order Now</a>
                <a href="#menu" class="btn-menu">View Menu</a>
            </div>
        </div>
        <div class="carousel">
            <div class="carousel-item active fade-in">
                <img src="/public/assets/img/kishore-v-tf7Y9kMhETg-unsplash.jpg" alt="Image 1">
            </div>
            <div class="carousel-item fade-out">
                <img src="/public/assets/img/senya-mitin-PIy8Hrys8bQ-unsplash.jpg" alt="Image 2">
            </div>
            <div class="carousel-item fade-out">
                <img src="/public/assets/img/hamza-nouasria-P2mIRmNIIPQ-unsplash.jpg" alt="Image 3">
            </div>
        </div>
        <div class="carousel-indicators">
            <span class="indicator active" data-slide="0"></span>
            <span class="indicator" data-slide="1"></span>
            <span class="indicator" data-slide="2"></span>
        </div>
    </header>
    <main>
        <!-- Otros contenidos de la página -->
    </main>
    <footer>

        <!-- Pie de página -->
    </footer>
    <script src="/public/assets/js/nose.js"></script>
</body>

</html>