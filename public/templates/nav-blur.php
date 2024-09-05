<?php
$current_page = basename($_SERVER['PHP_SELF']);
$show_dropdown = ($current_page === 'tienda.php') ? 'active' : '';
$logo_path = ($current_page === 'index.php') ? 'public/assets/img/logo-removebg-preview.png' : 'assets/img/logo-removebg-preview.png';
?>
<nav>
    <div class="logo">
        <a href="/" class="logo-link">
            <img src="<?php echo $logo_path; ?>" alt="Logo" class="logo-image" />
            <h1>Café Sabrosos</h1>
        </a>
    </div>
    <div class="nav-content">
        <ul class="nav-links">
            <li><a href="/public/local.php">Locales</a></li>
            <li class="dropdown mobile-only <?php echo $show_dropdown; ?>">
                <a href="#" class="dropdown-link">Productos <i class="fa fa-plus"></i></a>
                <ul class="dropdown-menu" id="mobile-category-dropdown">
                    <li><a href="#" data-category="Cafés Especiales" data-category-id="1">Cafés Especiales</a></li>
                    <li><a href="#" data-category="Cafés con Leche" data-category-id="2">Cafés con Leche</a></li>
                    <li><a href="#" data-category="Cafés Fríos" data-category-id="3">Cafés Fríos</a></li>
                    <li><a href="#" data-category="Pasteles y Postres" data-category-id="4">Pasteles y Postres</a></li>
                    <li><a href="#" data-category="Té" data-category-id="5">Té</a></li>
                    <li><a href="#" data-category="Sandwich y Bocadillos" data-category-id="6">Sandwiches y Bocadillos</a></li>
                </ul>
            </li>
            <li class="desktop-only"><a href="/public/tienda.php">Productos</a></li>
            <li><a href="#">Ofertas</a></li>
            <li><a href="#">Reservas</a></li>
            <li><a href="/public/contactos.php">Contacto</a></li>
        </ul>
        <div class="nav-icons">
            <a href="/public/cuenta.php"><img src="/public/assets/img/image.png" alt="Usuario" class="user-icon" /></a>
            <div class="cart">
                <a href="/public/carrito.html">
                    <img src="/public/assets/img/cart.png" alt="Carrito" />
                    <span id="cart-counter" class="cart-counter">0</span>
                </a>
            </div>
        </div>
    </div>
    <div class="nav-toggle">
        <button class="toggle-button">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</nav>