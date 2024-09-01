<?php
include '../src/db/db_connect.php';
require '../vendor/autoload.php'; // Asegúrate de incluir el autoloader de Composer

use Stichoza\GoogleTranslate\GoogleTranslate;

session_start();
// Comprobar si se ha enviado una preferencia de idioma
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    if (in_array($lang, ['es', 'en', 'fr', 'de', 'pt'])) {
        $_SESSION['language'] = $lang;
    }
}

// Obtener el idioma preferido de la sesión o usar español como predeterminado
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'es';
try {
    // Obtener conexión a la base de datos
    $conn = getDbConnection();

    // Obtener el ID del producto desde la URL
    $id_producto = $_GET['id'];

    // Preparar y ejecutar la consulta SQL para obtener el producto
    $query = $conn->prepare('SELECT * FROM producto WHERE idProducto = ?');
    $query->bind_param('i', $id_producto);
    $query->execute();
    $result = $query->get_result();
    $producto = $result->fetch_assoc();

    // Verificar si se encontró el producto
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

    // Crear instancia de GoogleTranslate
    $tr = new GoogleTranslate();

    // Obtener traducciones en español
    $nombreProductoES = $producto['nombre'];
    $descripcionProductoES = $producto['descripcion'];
    $nombreCategoriaES = $categoria['nombre'];

    // Obtener traducciones en inglés
    $tr->setSource('es')->setTarget('en');
    $nombreProductoEN = $tr->translate($producto['nombre']);
    $descripcionProductoEN = $tr->translate($producto['descripcion']);
    $nombreCategoriaEN = $tr->translate($categoria['nombre']);

    // Obtener traducciones en francés
    $tr->setSource('es')->setTarget('fr');
    $nombreProductoFR = $tr->translate($producto['nombre']);
    $descripcionProductoFR = $tr->translate($producto['descripcion']);
    $nombreCategoriaFR = $tr->translate($categoria['nombre']);

    // Obtener traducciones en alemán
    $tr->setSource('es')->setTarget('de');
    $nombreProductoDE = $tr->translate($producto['nombre']);
    $descripcionProductoDE = $tr->translate($producto['descripcion']);
    $nombreCategoriaDE = $tr->translate($categoria['nombre']);

    // Obtener traducciones en portugués
    $tr->setSource('es')->setTarget('pt');
    $nombreProductoPT = $tr->translate($producto['nombre']);
    $descripcionProductoPT = $tr->translate($producto['descripcion']);
    $nombreCategoriaPT = $tr->translate($categoria['nombre']);

    // Cerrar conexión a la base de datos
    $conn->close();
} catch (Exception $e) {
    // Manejo de errores de conexión
    http_response_code(500);
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars('Café Sabrosos - ' . $producto['nombre']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/productos.css">
</head>
<body>
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
                    <a href="cuenta.php"><img src="/public/assets/img/image.png" alt="Usuario" class="user-icon" /></a>
                </li>
                <div class="cart">
                    <a href="carrito.html">
                        <img src="/public/assets/img/cart.png" alt="Carrito" />
                        <span id="cart-counter" class="cart-counter">0</span>
                    </a>
                </div>
                <li>
                    <button id="language-toggle" class="language-btn">ES</button>
                </li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="grid">
            <div class="product-container">
                <div class="back-link">
                    <a href="tienda.php#main-category" class="text-primary" id="back-to-store" onclick="setCategoryInLocalStorage('<?= htmlspecialchars($categoria['nombre']) ?>', <?= (int)$producto['idCategoria'] ?>)">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12 19-7-7 7-7"></path>
                            <path d="M19 12H5"></path>
                        </svg>
                        <span data-lang="es">Regresar a la tienda</span>
                        <span data-lang="en" style="display: none;">Back to Store</span>
                        <span data-lang="fr" style="display: none;">Retour au magasin</span>
                        <span data-lang="de" style="display: none;">Zurück zum Laden</span>
                        <span data-lang="pt" style="display: none;">Voltar à loja</span>
                    </a>
                </div>
                <div class="product-image-container">
                    <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="product-image">
                </div>
            </div>
            <div class="details-grid">
                <div>
                    <p class="product-category" data-lang="es"><?php echo $nombreCategoriaES; ?></p>
                    <p class="product-category" data-lang="en" style="display:none;"><?php echo $nombreCategoriaEN; ?></p>
                    <p class="product-category" data-lang="fr" style="display:none;"><?php echo $nombreCategoriaFR; ?></p>
                    <p class="product-category" data-lang="de" style="display:none;"><?php echo $nombreCategoriaDE; ?></p>
                    <p class="product-category" data-lang="pt" style="display:none;"><?php echo $nombreCategoriaPT; ?></p>
                    <h1 class="product-title" data-lang="es"><?php echo $nombreProductoES; ?></h1>
                    <h1 class="product-title" data-lang="en" style="display:none;"><?php echo $nombreProductoEN; ?></h1>
                    <h1 class="product-title" data-lang="fr" style="display:none;"><?php echo $nombreProductoFR; ?></h1>
                    <h1 class="product-title" data-lang="de" style="display:none;"><?php echo $nombreProductoDE; ?></h1>
                    <h1 class="product-title" data-lang="pt" style="display:none;"><?php echo $nombreProductoPT; ?></h1>
                    <p class="product-description" data-lang="es"><?php echo $descripcionProductoES; ?></p>
                    <p class="product-description" data-lang="en" style="display:none;"><?php echo $descripcionProductoEN; ?></p>
                    <p class="product-description" data-lang="fr" style="display:none;"><?php echo $descripcionProductoFR; ?></p>
                    <p class="product-description" data-lang="de" style="display:none;"><?php echo $descripcionProductoDE; ?></p>
                    <p class="product-description" data-lang="pt" style="display:none;"><?php echo $descripcionProductoPT; ?></p>
                </div>
                <div class="price-container">
                    <div class="product-price">$<?php echo number_format($producto['precio'], 2); ?></div>
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
                    <button class="btn-lg" data-lang="es">Agregar al Carrito</button>
                    <button class="btn-lg btn-outline" data-lang="es">Comprar Ahora</button>
                    <button class="btn-lg" data-lang="en" style="display:none;">Add to Cart</button>
                    <button class="btn-lg btn-outline" data-lang="en" style="display:none;">Buy Now</button>
                    <button class="btn-lg" data-lang="fr" style="display:none;">Ajouter au panier</button>
                    <button class="btn-lg btn-outline" data-lang="fr" style="display:none;">Acheter maintenant</button>
                    <button class="btn-lg" data-lang="de" style="display:none;">In den Warenkorb</button>
                    <button class="btn-lg btn-outline" data-lang="de" style="display:none;">Jetzt kaufen</button>
                    <button class="btn-lg" data-lang="pt" style="display:none;">Adicionar ao carrinho</button>
                    <button class="btn-lg btn-outline" data-lang="pt" style="display:none;">Comprar Agora</button>
                </div>
                <div class="card">
                    <div class="header">
                        <button class="back-button">
                            <i class="fas fa-chevron-left icon"></i>
                            <span class="sr-only">Back</span>
                        </button>
                        <h3 id="toggle-title">Metodos de Pagos</h3>
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
                            <!-- Añade más íconos según sea necesario -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer-content">
            <div class="footer-section about">
                <h3>Café Sabrosos</h3>
                <p>
                    Disfruta del mejor café con nosotros. Nos preocupamos por cada
                    detalle, desde la selección de los granos hasta la preparación de tu
                    bebida.
                </p>
                <div class="socials">
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-section links">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="/public/local.html">Locales</a></li>
                    <li><a href="#">Productos</a></li>
                    <li><a href="#">Ofertas</a></li>
                    <li><a href="#">Reservas</a></li>
                    <li><a href="/public/contactos.html">Contacto</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h3>Contáctanos</h3>
                <ul>
                    <li><i class="fa-solid fa-location-dot"></i> 123 Calle Café, San José</li>
                    <li><i class="fa fa-phone"></i> +598 123 4567</li>
                    <li><i class="fa fa-envelope"></i> info@cafesabrosos.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Café Sabrosos. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const title = document.getElementById('toggle-title');
            const buttons = document.querySelector('.buttons');

            title.addEventListener('click', () => {
                // Toggle rotation for the icon
                const icon = document.querySelector('.back-button .icon');
                icon.classList.toggle('rotate');

                // Toggle the visibility of the buttons
                buttons.classList.toggle('show');
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityElement = document.querySelector('.quantity');
            let quantity = parseInt(quantityElement.textContent);

            document.querySelector('.quantity-control .btn-outline:first-of-type').addEventListener('click', function() {
                if (quantity > 1) {
                    quantity -= 1;
                    quantityElement.textContent = quantity;
                }
            });

            document.querySelector('.quantity-control .btn-outline:last-of-type').addEventListener('click', function() {
                quantity += 1;
                quantityElement.textContent = quantity;
            });

            document.querySelector('.action-buttons .btn-lg:first-of-type').addEventListener('click', function(e) {
                e.preventDefault();
                const productId = '<?= htmlspecialchars($id_producto) ?>';
                const quantity = parseInt(quantityElement.textContent);
                addToCart(productId, quantity);
            });

            updateCartCounter();
        });

        function addToCart(productId, quantity) {
            if (<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>) {
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
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            updateCartCounter();
                            alert('Producto agregado al carrito.');
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error en la red. Por favor, inténtelo de nuevo.');
                    });
            } else {
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
                })
                .catch(() => {
                    handleLocalStorageCart(cartCounterElement);
                });
        }

        function handleLocalStorageCart(cartCounterElement) {
            if (isCartExpired()) {
                clearExpiredCart();
            }
            const carrito = JSON.parse(localStorage.getItem('carrito')) || {};
            const totalQuantity = Object.values(carrito).reduce((acc, quantity) => acc + quantity, 0);
            cartCounterElement.textContent = totalQuantity;
        }

        function getCartExpiration() {
            return parseInt(localStorage.getItem('cart_expiration'));
        }

        function isCartExpired() {
            const expirationTime = getCartExpiration();
            return expirationTime && Date.now() > expirationTime;
        }

        function clearExpiredCart() {
            localStorage.removeItem('carrito');
            localStorage.removeItem('cart_expiration');
        }

        function setCategoryInLocalStorage(category, idCategoria) {
            const categoryData = {
                category,
                idCategoria
            };
            localStorage.setItem('selectedCategory', JSON.stringify(categoryData));
        }

        document.getElementById('language-toggle').addEventListener('click', function() {
            const languageMap = {
                'ES': 'es',
                'EN': 'en',
                'FR': 'fr',
                'DE': 'de',
                'PT': 'pt'
            };
            const languageKeys = Object.keys(languageMap);

            let currentLang = this.innerText;
            let nextLangIndex = (languageKeys.indexOf(currentLang) + 1) % languageKeys.length;
            let nextLang = languageKeys[nextLangIndex];
            this.innerText = nextLang;

            translateContent(languageMap[nextLang]);
        });

        function translateContent(lang) {
            const languages = ['es', 'en', 'fr', 'de', 'pt'];
            languages.forEach((language) => {
                document.querySelectorAll(`[data-lang="${language}"]`).forEach(el => {
                    el.style.display = language === lang ? 'block' : 'none';
                });
            });

            const translations = {
                'en': {
                    addToCart: 'Add to Cart',
                    backToStore: 'Back to Store'
                },
                'es': {
                    addToCart: 'Agregar al carrito',
                    backToStore: 'Regresar a la tienda'
                },
                'fr': {
                    addToCart: 'Ajouter au panier',
                    backToStore: 'Retour au magasin'
                },
                'de': {
                    addToCart: 'In den Warenkorb',
                    backToStore: 'Zurück zum Laden'
                },
                'pt': {
                    addToCart: 'Adicionar ao carrinho',
                    backToStore: 'Voltar à loja'
                }
            };

            document.querySelector('.action-buttons .btn-lg:first-of-type').innerText = translations[lang].addToCart;
            document.getElementById('back-to-store').querySelector('span').innerText = translations[lang].backToStore;
        }
    </script>
    <script src="assets/js/productos.js"></script>
</body>

</html>