<?php
include '../src/db/db_connect.php';
require '../vendor/autoload.php';

try {
    // Obtener conexión a la base de datos
    $conn = getDbConnection();
    $conn->set_charset('utf8mb4'); // Asegúrate de que la conexión use el charset correcto

    // Obtener el ID del producto desde la URL
    $id_producto = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Preparar y ejecutar la consulta SQL para obtener el producto
    $query = $conn->prepare('SELECT * FROM producto WHERE idProducto = ?');
    $query->bind_param('i', $id_producto);
    $query->execute();
    $result = $query->get_result();
    $producto = $result->fetch_assoc();

    if (!$producto) {
        echo "Producto no encontrado.";
        exit;
    }

    // Obtener el nombre de la categoría
    $idCategoria = $producto['idCategoria'];
    $queryCategoria = $conn->prepare('SELECT nombre FROM categoria WHERE idCategoria = ?');
    $queryCategoria->bind_param('i', $idCategoria);
    $queryCategoria->execute();
    $resultCategoria = $queryCategoria->get_result();
    $categoria = $resultCategoria->fetch_assoc();

    if (!$categoria) {
        echo "Categoría no encontrada.";
        exit;
    }

    // Cerrar conexión a la base de datos
    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-64x64.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars('Café Sabrosos - ' . $producto['nombre']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/productos.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>
    <header>
        <?php include 'templates/nav.php'; ?>
    </header>

    <div class="container">
        <div class="grid">
            <div class="product-container">
                <div class="back-link">
                    <a href="tienda.php#category-details" class="text-primary" id="back-to-store">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12 19-7-7 7-7"></path>
                            <path d="M19 12H5"></path>
                        </svg>
                        Regresar a la tienda
                    </a>
                </div>
                <div class="product-image-container">
                    <input type="hidden" id="product-id" value="<?= htmlspecialchars($producto['idProducto']) ?>">
                    <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="product-image">
                    <button class="favorite-button" data-product-id="<?= htmlspecialchars($producto['idProducto']) ?>">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
            </div>
            <div class="details-grid">
                <div>
                    <p class="product-category"><?= htmlspecialchars($categoria['nombre']) ?></p>
                    <h1 class="product-title"><?= htmlspecialchars($producto['nombre']) ?></h1>
                    <p class="product-description"><?= htmlspecialchars($producto['descripcion']) ?></p>
                </div>
                <div class="price-container">
                    <div class="product-price">€<?= number_format($producto['precio'], 2) ?></div>
                    <div class="quantity-control">
                        <button class="btn-outline">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"></path>
                            </svg>
                        </button>
                        <div class="quantity">1</div>
                        <button class="btn-outline">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="action-buttons">
                    <button class="btn-lg">Agregar al Carrito</button>
                    <button class="btn-lg btn-outline">Comprar Ahora</button>
                </div>
                <div class="card">
                    <div class="header">
                        <button class="back-button">
                            <i class="fas fa-chevron-left icon"></i>
                            <span class="sr-only">Back</span>
                        </button>
                        <h3 id="toggle-title">Métodos de Pago</h3>
                    </div>
                    <div class="content">
                        <div class="buttons">
                            <button class="payment-button">
                                <i class="fa-brands fa-cc-visa icon"></i>
                            </button>
                            <button class="payment-button">
                                <i class="fa-brands fa-cc-mastercard icon"></i>
                            </button>
                            <button class="payment-button">
                                <i class="fa-brands fa-paypal icon"></i>
                            </button>
                            <button class="payment-button">
                                <i class="fa-brands fa-cc-amex icon"></i>
                            </button>
                            <button class="payment-button">
                                <i class="fa-brands fa-google-pay icon"></i>
                            </button>
                            <button class="payment-button">
                                <i class="fa-brands fa-apple-pay icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function initializeFavoritesOnProductPage() {
            const favoriteButton = document.querySelector('.favorite-button'); // Selecciona el botón de favorito en la página del producto
            const productId = favoriteButton.dataset.productId; // Obtiene el ID del producto

            // Obtener la lista de favoritos del localStorage o crear una nueva
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];

            // Verificar si el producto ya está en favoritos y marcar el botón como seleccionado
            if (favorites.includes(productId)) {
                favoriteButton.classList.add('selected'); // Marcar como favorito
            }

            // Agregar el event listener para manejar el click en el botón de favorito
            favoriteButton.addEventListener('click', function() {
                // Verificar si el usuario tiene una sesión activa
                fetch('/src/db/checkSession.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.loggedIn) {
                            // Si el usuario está autenticado, permite agregar/quitar de favoritos
                            if (favoriteButton.classList.contains('selected')) {
                                // Si ya está seleccionado, eliminar de favoritos
                                favoriteButton.classList.remove('selected');
                                favorites = favorites.filter(favId => favId !== productId); // Eliminar del array
                                showNotification('Producto eliminado de favoritos'); // Mostrar notificación
                            } else {
                                // Si no está seleccionado, agregar a favoritos
                                favoriteButton.classList.add('selected');
                                favorites.push(productId); // Agregar al array
                                showNotification('Producto añadido a favoritos'); // Mostrar notificación
                            }

                            // Actualizar el localStorage con la nueva lista de favoritos
                            localStorage.setItem('favorites', JSON.stringify(favorites));
                        } else {
                            // Si no hay sesión activa, mostrar un mensaje de inicio de sesión
                            showNotification('Por favor, inicia sesión para agregar productos a favoritos.');
                        }
                    })
                    .catch(error => {
                        console.error('Error verificando sesión:', error);
                        showNotification('Hubo un problema al verificar la sesión. Inténtalo de nuevo más tarde.');
                    });
            });
        }

        function showNotification(message) {
            const notification = document.getElementById('notification');

            // Cambiar el mensaje de la notificación
            notification.textContent = message;

            // Mostrar la notificación con la animación desde el footer
            notification.classList.remove('hidden');
            notification.classList.add('show');

            // Ocultar la notificación después de 5 segundos
            setTimeout(() => {
                notification.classList.remove('show');
                notification.classList.add('hidden');
            }, 5000); // 5 segundos
        }

        // Ejecutar la función cuando el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', initializeFavoritesOnProductPage);
    </script>
    <div id="notification" class="notification hidden">Producto añadido a favoritos</div>
    <style>
        .notification {
            position: fixed;
            bottom: -100px;
            /* Fuera de la pantalla al inicio */
            left: 50%;
            transform: translateX(-50%);
            background-color: #daa520;
            color: #1b0c0a;
            padding: 25px 20px;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            font-size: 1rem;
            z-index: 1000;
            opacity: 0;
            transition: bottom 0.5s ease, opacity 0.5s ease;
            white-space: nowrap;
        }

        /* Mostrar notificación */
        .notification.show {
            bottom: 20px;
            /* La notificación se mueve hacia arriba cuando se muestra */
            opacity: 1;
        }

        /* Ocultar notificación (se va hacia abajo) */
        .notification.hidden {
            bottom: -100px;
            opacity: 0;
        }

        .favorite-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.5rem;
            color: red;
            /* Color gris inicial */
            transition: color 0.3s, transform 0.3s;
        }

        .favorite-button:hover {
            color: #DAA520;
            /* Cambiar a dorado en hover */
            transform: scale(1.2);
            /* Color dorado al hacer hover */
        }

        .product-image-container {
            position: relative;
            /* Necesario para que el botón se posicione respecto al contenedor */
        }

        .favorite-button .fas.fa-heart {
            font-size: 2rem;
            /* Ajusta el tamaño del corazón */
        }

        .favorite-button.selected {
            color: #DAA520;
            /* Color dorado */
        }
    </style>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <?php include 'templates/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const title = document.getElementById('toggle-title');
            const buttons = document.querySelector('.buttons');

            title.addEventListener('click', () => {
                const icon = document.querySelector('.back-button .icon');
                icon.classList.toggle('rotate');
                buttons.classList.toggle('show');
            });
        });
    </script>
    <script src="/public/assets/js/productos.js"></script>
</body>

</html>