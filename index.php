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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <link rel="stylesheet" href="/public/assets/css/nav-blur.css">
    <link rel="stylesheet" href="/public/assets/css/footer.css">
    <style>
        .overlay {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
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
            width: 80%;
            max-width: 500px;
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
            color: #B9860A;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #d95b1d;
        }

        h2 {
            text-align: center;
            color: #B9860A;
        }

        .modal p {
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }
    </style>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Estilo para el contenido del modal */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-content button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }


        .modal-content h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #B9860A;
        }

        .modal-content p {
            font-size: 18px;
            color: #666;
        }

        .modal-content button {
            background-color: #B9860A;
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
    <?php if (isset($_GET['accountDeleted']) && $_GET['accountDeleted'] == 'true'): ?>
        <div class="modal" id="accountDeletedModal">
            <div class="modal-content">
                <h2>Cuenta eliminada</h2>
                <p>Tu cuenta ha sido eliminada exitosamente.</p>
                <button onclick="closeModalAccount()">Cerrar</button>
            </div>
        </div>
        <script>
            function closeModalAccount() {
                document.getElementById('accountDeletedModal').style.display = 'none';

                // Actualiza la URL sin el parámetro 'accountDeleted'
                var url = new URL(window.location.href);
                url.searchParams.delete('accountDeleted');
                window.history.replaceState({}, '', url);
            }

            // Mostrar el modal
            document.getElementById('accountDeletedModal').style.display = 'block';
        </script>
    <?php endif; ?>
    <header>
        <?php include 'public/templates/nav-blur.php' ?>
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
                <img src="/public/assets/img/como-montar-cafeteria-.webp" alt="Image 2">
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
    <section class="info">
        <div class="info-box">
            <h3>Ven al brunch de los domingos</h3>
            <p>Disfruta de nuestro delicioso brunch dominical en un ambiente acogedor, con café fresco y una selección
                de platillos para empezar bien tu día.</p>
        </div>
        <div class="info-box">
            <h3>Celebra tus fiestas con nosotros</h3>
            <p>Organiza tus eventos y celebraciones con nosotros, garantizando un ambiente acogedor y sabores irresistibles.</p>
        </div>
    </section>
    <section class="cta">
        <div class="cta-content">
            <h3>Ven desayunar</h3>
            <p>Medialunas, Croissants, Jugos o lo que quieras. Aquí en Café Sabrosos.</p>
            <button onclick="location.href='/public/tienda.php'">Descubre nuestros productos<i class="fa-solid fa-mug-saucer"></i></i></button>
        </div>
    </section>
    <section class="features">
        <div class="feature">
            <h4>Café recién hecho</h4>
            <p>Café recién molido y preparado con esmero para ofrecerte la mejor experiencia.</p>
        </div>
        <div class="feature">
            <h4>Tartas para cada día</h4>
            <p>Variedad de tartas frescas y deliciosas disponibles todos los días para satisfacer tu antojo.</p>
        </div>
        <div class="feature">
            <h4>Prueba nuestros cafés</h4>
            <p>Explora nuestra selección de cafés únicos, preparados por expertos baristas.</p>
        </div>
        <div class="feature">
            <h4>Lo que busques</h4>
            <p>Nos adaptamos a tus gustos y preferencias para brindarte exactamente lo que deseas.</p>
        </div>
    </main>
    <?php include 'public/templates/footer.php'; ?>
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
            const toggleButton = document.querySelector('.toggle-button');
            const navLinks = document.querySelector('.nav-links');
            const dropdownLinks = document.querySelectorAll('.dropdown-link');

            // Obtén la URL actual
            const currentPage = window.location.pathname;

            // Evento para mostrar/ocultar el menú de navegación
            toggleButton.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });

            // Evento para mostrar/ocultar el menú desplegable en móviles
            dropdownLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Solo abre el dropdown si estás en tienda.php
                    if (currentPage === '/public/tienda.php') {
                        e.preventDefault();
                        const dropdown = link.parentElement;
                        dropdown.classList.toggle('active');
                    }
                });
            });
        });
    </script>
</body>
<script src="/public/assets/js/updateCartCounter.js"></script>
</html>