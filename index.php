<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Sabrosos</title>
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-64x64.png">
    <link rel="icon" type="image/x-icon" href="/public/assets/img/icons/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <style>
        .overlay {
            display: none;
            position: fixed;
            z-index: 9998;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9); /* Fondo gris claro con opacidad */
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Ancho del modal */
            max-width: 500px; /* Ancho máximo del modal */
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #B9860A; /* Color del botón de cerrar */
            cursor: pointer;
        }

        .close-btn:hover {
            color: #d95b1d;
        }

        h2 {
            text-align: center;
            color: #B9860A; /* Color del título */
        }

        .modal p {
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div id="overlay" class="overlay"></div>
    <div id="activationModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>¡Cuenta no activada!</h2>
            <p>Tu cuenta aún no ha sido activada. Por favor, revisa tu correo electrónico y sigue el enlace para activar tu cuenta.</p>
            <p>Si no has recibido el correo, verifica tu carpeta de spam.</p>
        </div>
    </div>

    <header>
        <nav>
        <div class="logo">
                <a href="/" class="logo-link">
                    <img src="/public/assets/img/logo-removebg-preview.png" alt="Logo" class="logo-image">
                    <h1>Café Sabrosos</h1>
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="/public/local.html">Locales</a></li>
                <li><a href="/public/tienda.html">Productos</a></li>
                <li><a href="#">Ofertas</a></li>
                <li><a href="#">Reservas</a></li>
                <li><a href="/public/contactos.html">Contacto</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="/public/cuenta.php"><img src="/public/assets/img/image.png" alt="Usuario" class="user-icon"></a></li>
                <?php else: ?>
                    <li><a href="/public/login.html"><img src="/public/assets/img/image.png" alt="Usuario" class="user-icon"></a></li>
                <?php endif; ?>
                <div class="cart">
                    <a href="carrito.html">
                        <img src="/public/assets/img/cart.png" alt="Carrito">
                        <span id="cart-counter" class="cart-counter">0</span>
                    </a>
                </div>
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
    </main>
    <script src="/public/assets/js/nose.js"></script>
    <script>
  function closeModal() {
        document.getElementById('activationModal').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
        
        // Actualizar la URL para eliminar el parámetro 'showModal'
        const url = new URL(window.location);
        url.searchParams.delete('showModal');
        window.history.pushState({}, '', url);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si la página fue redirigida con el parámetro 'showModal'
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('showModal')) {
            const modal = document.getElementById('activationModal');
            const overlay = document.getElementById('overlay');

            // Mostrar el modal y la capa de fondo
            modal.style.display = 'block';
            overlay.style.display = 'block';

            // Cerrar el modal si el usuario hace clic fuera del modal
            window.onclick = function(event) {
                if (event.target == overlay) {
                    closeModal();
                }
            }
        }
    });
    </script>
</body>

</html>
