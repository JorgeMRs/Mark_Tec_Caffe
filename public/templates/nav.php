<?php
$current_page = basename($_SERVER['PHP_SELF']);
$show_dropdown = ($current_page === 'tienda.php') ? 'active' : '';
$logo_path = ($current_page === 'index.php') ? 'public/assets/icons/favicon.svg' : 'assets/icons/favicon.svg';

// Cambiar el texto y el enlace de "Productos" según la página
$productos_text = ($current_page === 'tienda.php') ? 'Productos' : 'Tienda';
$productos_link = ($current_page === 'tienda.php') ? '/public/tienda.php' : '/public/tienda.php';

$show_icon = ($current_page === 'tienda.php') ? '<i class="fa fa-plus"></i>' : '';

$isLoggedIn = isset($_COOKIE['user_token']);
?>
<nav>
    <div class="logo">
        <a href="/" class="logo-link">
            <img src="<?php echo $logo_path; ?>" alt="Logo" class="logo-image" />
            <h1 id="nav-logo">Café Sabrosos</h1>
        </a>
    </div>
    <div class="nav-content">
        <ul class="nav-links">
            <li class="desktop-only">
                <a href="<?php echo $productos_link; ?>" id="nav-productos"><?php echo $productos_text; ?></a>
            </li>
            <li><a href="/public/local.php" id="nav-locales">Locales</a></li>
            <li><a href="#" id="nav-ofertas">Ofertas</a></li>
            <li><a href="/public/reservas.php" id="nav-reservas">Reservas</a></li>
            <li><a href="/public/contactos.php" id="nav-contacto">Contacto</a></li>
        </ul>
        <div class="nav-icons">
            <?php if ($isLoggedIn): ?>
                <a href="/public/favoritos.php" class="favorite-icon" id="nav-favoritos">
                    <i class="fas fa-heart2"></i>
                </a>
            <?php endif; ?>
            <a href="/public/cuenta.php" id="nav-usuario">
                <img src="/public/assets/img/image.png" alt="Usuario" class="user-icon" />
            </a>
            <div class="cart" id="cart-icon">
                <a href="/public/carrito.php">
                    <img src="/public/assets/img/cart.png" alt="Carrito" />
                    <span id="cart-counter" class="cart-counter">0</span>
                </a>
                <div class="cart-preview" id="cart-preview">
                    <ul id="cart-items"></ul>
                    <a href="/public/carrito.php" class="view-cart-button">Ver carrito</a>
                </div>
            </div>
            <select id="language-selector" class="language-selector">
                <option value="es">Español</option>
                <option value="en">English</option>
                <option value="fr">Français</option>
                <option value="pt">Português</option>
                <option value="de">Deutsch</option>
            </select>
        </div>
    </div>
    <div class="nav-toggle">
        <button class="toggle-button">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</nav>
<script src="/public/assets/js/nav.js"></script>