<?php
$current_page = basename($_SERVER['PHP_SELF']);
$show_dropdown = ($current_page === 'tienda.php') ? 'active' : '';
$logo_path = ($current_page === 'index.php') ? 'public/assets/img/logo-removebg-preview.png' : 'assets/img/logo-removebg-preview.png';

// Cambiar el texto y el enlace de "Productos" según la página
$productos_text = ($current_page === 'tienda.php') ? 'Productos' : 'Tienda';
$productos_link = ($current_page === 'tienda.php') ? '/public/tienda.php' : '/public/tienda.php';

$show_icon = ($current_page === 'tienda.php') ? '<i class="fa fa-plus"></i>' : '';
?>
<nav>
    <div class="logo">
        <a href="/" class="logo-link">
            <img src="<?php echo $logo_path; ?>" alt="Logo" class="logo-image" />
            <h1 data-translate="nav.logo">Café Sabrosos</h1>
        </a>
    </div>
    <div class="nav-content">
        <ul class="nav-links">
            <li class="dropdown mobile-only <?php echo $show_dropdown; ?>">
                <a href="<?php echo $productos_link; ?>" class="dropdown-link" data-translate="nav.productos"><?php echo $productos_text; ?> <?php echo $show_icon; ?></a>
                <ul class="dropdown-menu" id="mobile-category-dropdown">
                    <li><a href="#" data-category="Cafés Especiales" data-category-id="1" data-translate="nav.categoria1">Cafés Especiales</a></li>
                    <li><a href="#" data-category="Cafés con Leche" data-category-id="2" data-translate="nav.categoria2">Cafés con Leche</a></li>
                    <li><a href="#" data-category="Cafés Fríos" data-category-id="3" data-translate="nav.categoria3">Cafés Fríos</a></li>
                    <li><a href="#" data-category="Pasteles y Postres" data-category-id="4" data-translate="nav.categoria4">Pasteles y Postres</a></li>
                    <li><a href="#" data-category="Té" data-category-id="5" data-translate="nav.categoria5">Té</a></li>
                    <li><a href="#" data-category="Sandwich y Bocadillos" data-category-id="6" data-translate="nav.categoria6">Sandwiches y Bocadillos</a></li>
                </ul>
            </li>
            <li class="desktop-only"><a href="<?php echo $productos_link; ?>"> <?php echo $productos_text; ?></a></li>
            <li><a href="/public/local.php" data-translate="nav.locales">Locales</a></li>
            <li><a href="#">Ofertas</a></li>
            <li><a href="/public/reservas.php" data-translate="nav.reservas">Reservas</a></li>
            <li><a href="/public/contactos.php" data-translate="nav.contacto">Contacto</a></li>
            <li id="piston-cup" style="display:none;">
                <a href="/Doom/doom.html">
                    <img src="/public/assets/img/pistoncup.png" alt="Piston Cup" style="width: 50px; height: auto;">
                </a>
            </li> <!-- Botón oculto -->
        </ul>
        <div class="nav-icons">
            <a href="/public/cuenta.php"><img src="/public/assets/img/image.png" alt="Usuario" class="user-icon" /></a>
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
            <!-- Selector de idiomas -->
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
<script>
    // Secuencia del código Konami
    const konamiCode = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65];
    let konamiIndex = 0;

    // Detecta las teclas presionadas
    document.addEventListener('keydown', function(event) {
        if (event.keyCode === konamiCode[konamiIndex]) {
            konamiIndex++;

            // Si se ha completado la secuencia
            if (konamiIndex === konamiCode.length) {
                // Muestra el botón oculto con la imagen de 'piston cup'
                document.getElementById('piston-cup').style.display = 'block';
                konamiIndex = 0; // Reinicia el índice
            }
        } else {
            // Reinicia si se presiona una tecla incorrecta
            konamiIndex = 0;
        }
    });
</script>