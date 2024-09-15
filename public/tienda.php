<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cafe Sabrosos - Productos</title>
    <link rel="stylesheet" href="assets/css/tienda.css" />
    <link rel="icon" href="assets/img/logo-removebg-preview.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-64x64.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/nav-blur.css">
</head>

<body>
    <header>
        <?php include 'templates/nav-blur.php' ?>
        <div class="header-content">
            <h2 class="top-subtitle">Café Sabrosos</h2>
            <h2 class="subtitle">Siempre el mejor café</h2>
            <div class="header-buttons">
                <a href="/public/menu.html" class="btn">Menú</a>
                <a href="/public/reservas.html" class="btn">Reservas</a>
            </div>
        </div>
    </header>

    <main>
        <div id="category-details" class="content-container"></div>
    </main>
    
    <?php include 'templates/footer.php'; ?>
    <script src="/public/assets/js/tienda.js"></script>
    <script src="/public/assets/js/updateCartCounter.js"></script>
</body>

</html>
