<?php
include '../src/db/db_connect.php';
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
    <title><?= htmlspecialchars($producto['nombre']) ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            box-sizing: border-box;
        }

        /* Basic Styling */
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            display: flex;
            flex-direction: column;
            background-color: #f5f5f5;
        }

        .container {
    position: relative; /* Permite que los elementos hijos se posicionen absolutamente dentro de este contenedor */
    max-width: 1200px;
    margin: 0 auto 100px;
    margin-top: 100px;
    padding: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #ffffff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

        .user-icon {
            width: 40px;
            height: 40px;
            margin: 0 5px;
        }

        nav {
            display: flex;
            background-color: #1B0D0B;
            align-items: center;
            justify-content: space-around;
            padding: 1rem;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-sizing: border-box;
            /* Asegura que el padding no cause desbordamiento */
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            /* Transición del color */
            position: relative;
        }

        nav ul li a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            /* Altura de la línea */
            bottom: -5px;
            /* Posición de la línea debajo del texto */
            left: 0;
            background-color: #b8860b;
            /* Color de la línea */
            transform: scaleX(0);
            /* Inicialmente no visible */
            transition: transform 0.3s ease;
            /* Transición de la línea */
        }

        nav ul li a:hover {
            color: #b8860b;
            /* Cambia el color del texto al pasar el ratón */
        }

        nav ul li a:hover::after {
            transform: scaleX(1);
            /* Muestra la línea al pasar el ratón */
        }

        .logo {
            display: flex;
            align-items: center;
            /* Alinea verticalmente al centro */
            margin-left: 4vh;
        }

        .logo-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            /* Elimina el subrayado del enlace */
        }

        .logo img {
            width: 70px;
            /* Ajusta el tamaño del logo según tus necesidades */
            margin-right: 10px;
            /* Espacio entre el logo y el texto */
        }

        .logo-link h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: white;
            background: linear-gradient(45deg, #FFF, #b8860b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            align-items: center;
            /* Alinea verticalmente los elementos de la lista */
            list-style: none;
            margin: 0;
            padding: 0;
            margin-right: 3vh;
        }

        .nav-links li {
            margin: 0 10px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .nav-links a:hover {
            color: #b8860b;
            /* Color dorado al pasar el cursor */
        }

        .cart {
            position: relative;
        }

        .cart img {
            width: 30px;
            height: 30px;
        }

        .cart-counter {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #8B4513;
            /* Marrón */
            color: #ffffff;
            /* Blanco */
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        /* Columns */
        .left-column {
            width: 65%;
            position: relative;
        }

        .right-column {
            width: 35%;
            margin-top: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Left Column */
        .left-column img {
            width: 100%;
            margin-right: 25vh;
            height: 584px;
            object-fit: cover;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        /* Product Description */
        .product-description {
            border-bottom: 1px solid #E1E8EE;
            margin-bottom: 20px;
            text-align: center;
        }

        .product-description span {
            font-size: 12px;
            color: #1B0D0B;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
        }

        .product-description h1 {
            margin-bottom: 5px;
            font-weight: 300;
            font-size: 52px;
            color: #43484D;
            letter-spacing: -2px;
        }

        .product-description p {
            text-align: center;
            display: inline-flex;
            width: 35vh;
            margin: 20px 20px;
            font-size: 16px;
            font-weight: 300;
            color: #86939E;
            line-height: 24px;
        }

        /* Product Color */
        .product-color {
            margin-bottom: 30px;
        }

        /* Product Price */
        .product-price {
            display: flex;
            align-items: center;
            flex-direction: column;
            margin-top: 20px;
        }

        .product-price span {
            font-size: 26px;
            font-weight: 300;
            color: #43474D;
            margin-bottom: 20px;
        }

        .cart-btn {
            display: inline-block;
            background-color: #DAA520;
            font-size: 16px;
            color: #FFFFFF;
            text-decoration: none;
            padding: 12px 30px;
            transition: all .5s;
            margin-top: 10px;
        }

        .cart-btn:hover {
            background-color: #1B0D0B;
        }

        /* Quantity Selector */
        .quantity-selector {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .quantity-selector button {
            background-color: #DAA520;
            color: white;
            border: none;
            /* border-radius: 6px; */
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            margin: 0 5px;
        }

        .quantity-selector button:hover {
            background-color: #1B0D0B;
        }

        .quantity-selector span {
            font-size: 24px;
            margin: 0 15px;
        }

        footer {
            background-color: #DAA520;
            /* Dorado */
            color: #1B0D0B;
            text-align: center;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }


        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-section {
            margin-bottom: 20px;
            text-align: center;
        }

        .footer-section h3 {
            font-family: 'Poppins';
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .footer-section p,
        .footer-section ul {
            font-family: 'Poppins';
            font-size: 16px;
            font-weight: 400;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: #1B0D0B;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: #333;
        }

        .socials {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .socials a {
            color: #1B0D0B;
            margin: 0 10px;
            font-size: 20px;
            transition: color 0.3s ease;
        }

        .socials a:hover {
            color: #333;
        }

        .footer-bottom {
            background-color: #1B0D0B;
            /* Marrón oscuro */
            padding: 20px 0;
            width: 100%;
            font-family: 'Poppins';
            font-size: 16px;
            font-weight: 400;
            color: #FFF;
        }

        @media (min-width: 768px) {
            .footer-content {
                flex-direction: row;
                justify-content: space-between;
                text-align: left;
            }

            .footer-section {
                flex: 1;
                padding: 0 20px;
            }
        }
        .navigation-links {
        position: absolute;
        top: 145px;
        left: -530px;
        z-index: 10;
        display: flex;
        justify-content: center; /* Centra horizontalmente */
        width: 100%; /* Asegura que ocupe el ancho disponible */
        padding: 0 20px; /* Espaciado interno */
        box-sizing: border-box; /* Incluye padding en el ancho total */
    }

    .navigation-links a {
        color: #DAA520;
        text-decoration: none;
        font-size: 18px;
        margin: 0 10px;
        display: inline-block; /* Asegura que el enlace no se expanda a todo el ancho */
    }

    .navigation-links a:hover {
        color: #1B0D0B;
    }

    @media (max-width: 768px) {
        .navigation-links {
            top: 10vh; /* Ajusta la posición en pantallas pequeñas */
            left: 0; /* Centra el enlace horizontalmente */
        }

        .navigation-links a {
            font-size: 16px; /* Reduce el tamaño del texto en pantallas pequeñas */
            margin: 0 5px; /* Reduce el margen en pantallas pequeñas */
        }
    }

    @media (max-width: 480px) {
        .navigation-links {
            top: 8vh; /* Ajusta aún más en pantallas muy pequeñas */
        }

        .navigation-links a {
            font-size: 14px; /* Reduce aún más el tamaño del texto */
            margin: 0 2px; /* Reduce el margen para pantallas muy pequeñas */
        }
    }
    </style>
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
                <li><a href="/public/local.html">Locales</a></li>
                <li><a href="#">Productos</a></li>
                <li><a href="#">Ofertas</a></li>
                <li><a href="#">Reservas</a></li>
                <li><a href="/public/contactos.html">Contacto</a></li>
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
    <div class="navigation-links">
    <a href="tienda.html#main-category" id="back-to-store" onclick="setCategoryInLocalStorage('<?= htmlspecialchars($categoria['nombre']) ?>', <?= (int)$producto['idCategoria'] ?>)">Volver a la tienda</a>    </div>
    <main class="container">

        <!-- Left Column / Imagen del producto -->
        <div class="left-column">
            <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
        </div>
        <!-- Right Column -->
        <div class="right-column">

            <!-- Product Description -->
            <div class="product-description">
                <u><span><?= htmlspecialchars($categoria['nombre']) ?></span></u>
                <h1><?= htmlspecialchars($producto['nombre']) ?></h1>
                <p><?= htmlspecialchars($producto['descripcion']) ?></p>
            </div>

            <!-- Product Pricing -->
            <div class="product-price">
                <span>$<?= number_format($producto['precio'], 2) ?></span>
                <div class="quantity-selector">
                    <button id="decrease">-</button>
                    <span id="quantity">1</span>
                    <button id="increase">+</button>
                </div>
                <a href="#" class="cart-btn">Agregar al carrito</a>
            </div>
        </div>
    </main>
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
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-instagram"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
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
                    <li><i class="fa fa-map-marker"></i> 123 Calle Café, San José</li>
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
        document.addEventListener('DOMContentLoaded', function() {
            const quantityElement = document.getElementById('quantity');
            let quantity = parseInt(quantityElement.textContent);

            document.getElementById('increase').addEventListener('click', function() {
                quantity += 1;
                quantityElement.textContent = quantity;
            });

            document.getElementById('decrease').addEventListener('click', function() {
                if (quantity > 1) {
                    quantity -= 1;
                    quantityElement.textContent = quantity;
                }
            });
        });

        function setCategoryInLocalStorage(category, idCategoria) {
    const categoryData = {
        category: category,
        idCategoria: idCategoria
    };
    localStorage.setItem('selectedCategory', JSON.stringify(categoryData));
}


    </script>
</body>

</html>