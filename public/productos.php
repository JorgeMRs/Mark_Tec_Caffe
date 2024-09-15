<?php
include '../src/db/db_connect.php';
require '../vendor/autoload.php'; // Asegúrate de incluir el autoloader de Composer

session_start();

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
                    <a href="tienda.php#main-category" class="text-primary" id="back-to-store">
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