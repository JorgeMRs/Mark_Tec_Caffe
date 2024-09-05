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
     <?php include 'templates/nav-blur.php'?>
        <div class="header-content">
            <h2 class="top-subtitle">Café Sabrosos</h2>
            <h2 class="subtitle">Siempre el mejor café</h2>
            <div class="header-buttons">
                <a href="/public/menu.html" class="btn">Menú</a>
                <a href="/public/reservas.html" class="btn">Reservas</a>
            </div>
        </div>
    </header>
    <script>

    </script>
    <main>
        <div id="main-category">
            <h1 class="main-category-title">Nuestros productos</h1>
            <div class="product-grid">
                <!-- Categorías principales -->
                <div class="product-item" data-category="Cafés Especiales" data-category-id="1">
                    <div class="image-container">
                        <img src="/public/assets/img/categorias/cafe-especiales.jpg" alt="Producto 1" />
                    </div>
                    <h3>
                        Cafés <br />
                        Especiales
                    </h3>
                </div>
                <div class="product-item" data-category="Cafés con Leche" data-category-id="2">
                    <div class="image-container">
                        <img src="/public/assets/img/categorias/capuccino.jpg" alt="Producto 2" />
                    </div>
                    <h3>
                        Cafés <br />
                        con Leche
                    </h3>
                </div>
                <div class="product-item" data-category="Cafés Fríos" data-category-id="3">
                    <div class="image-container">
                        <img src="/public/assets/img/categorias/cafe-frio.jpg" alt="Producto 3" />
                    </div>
                    <h3>Cafés Fríos</h3>
                </div>
                <div class="product-item" data-category="Pasteles y Postres" data-category-id="4">
                    <div class="image-container">
                        <img src="/public/assets/img/categorias/pastel-y-tortas.jpg" alt="Producto 4" />
                    </div>
                    <h3>
                        Pasteles y <br />
                        Postres
                    </h3>
                </div>
                <div class="product-item" data-category="Té" data-category-id="5">
                    <div class="image-container">
                        <img src="/public/assets/img/categorias/tipos-de-te.jpg" alt="Producto 5" />
                    </div>
                    <h3>Té</h3>
                </div>
                <div class="product-item" data-category="Sandwich y Bocadillos" data-category-id="6">
                    <div class="image-container">
                        <img src="/public/assets/img/categorias/bocadillo.jpg" alt="Producto 6" />
                    </div>
                    <h3>
                        Sandwiches y <br />
                        Bocadillos
                    </h3>
                </div>
                <!-- Final de Categorías principales -->
            </div>
        </div>

        <div id="category-details" style="display: none" class="content-container">
            <!-- Aquí se insertará el contenido dinámico para la categoría seleccionada en la categoria principal y esta ultima se ocultara y ahora sera visible category-details-->

    </main>
    <?php include 'templates/footer.php'; ?>
</body>

<script>
    function addToCart(productId, quantity) {
        if (<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>) {
            // Si el usuario está autenticado, hacer la solicitud al servidor
            const url = '/src/cart/addCart.php';
            const data = new URLSearchParams({
                producto_id: productId,
                cantidad: quantity
            });

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: data.toString()
                }).then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        updateCartCounter();
                        alert('Producto agregado al carrito.');
                    } else {
                        alert('Error: ' + data.message);
                    }
                }).catch(error => {
                    alert('Error en la red. Por favor, inténtelo de nuevo.');
                });
        } else {
            // Si el usuario no está autenticado, almacenar en localStorage
            const carrito = JSON.parse(localStorage.getItem('carrito')) || {};
            carrito[productId] = (carrito[productId] || 0) + quantity;
            localStorage.setItem('carrito', JSON.stringify(carrito));

            const expirationTime = Date.now() + 3600000; // 1 hora
            localStorage.setItem('cart_expiration', expirationTime);

            updateCartCounter();
            alert('Producto agregado al carrito local.');
        }
    }

    function updateCartCounter() {
        const cartCounterElement = document.getElementById('cart-counter');

        fetch('/src/cart/getCartCounter.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    cartCounterElement.textContent = data.totalQuantity;
                } else {
                    handleLocalStorageCart(cartCounterElement);
                }
            }).catch(() => {
                handleLocalStorageCart(cartCounterElement);
            });
    }
</script>
<script src="/public/assets/js/tienda.js"></script>
<script src="/public/assets/js/productos.js"></script>

</html>