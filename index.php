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
</head>

<body>
    <div id="googleRegistrationModal" class="modal google-modal" style="display: none;">
        <div class="google-signin-container">
            <div class="google-signin-message">
                <button class="close" style="background: none; border: none; font-size: 24px; color: #aaa; cursor: pointer;">&times;</button> <!-- Botón de cerrar como "X" -->

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="96px" height="96px">
                    <path fill="#fbc02d" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12	s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20	s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
                    <path fill="#e53935" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039	l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
                    <path fill="#4caf50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36	c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
                    <path fill="#1565c0" d="M43.611,20.083L43.595,20L42,20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571	c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
                </svg>
                <p>¡Te has registrado exitosamente con Google!</p>
            </div>
        </div>
    </div>

    <div id="overlay" class="overlay"></div>
    <div id="activationModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>¡Cuenta no activada!</h2>
            <p>Tu cuenta aún no ha sido activada. Por favor, revisa tu correo electrónico y sigue el enlace para activar tu cuenta.</p>
            <p>Si no has recibido el correo, verifica tu carpeta de spam.</p>
        </div>
    </div>
    <?php if (isset($_GET['accountDeactivated']) && $_GET['accountDeactivated'] == 'true'): ?>
        <div class="modal" id="accountDeactivatedModal">
            <div class="modal-content">
                <h2>Cuenta desactivada</h2>
                <p>Tu cuenta ha sido desactivada exitosamente.</p>
                <button onclick="closeModalAccount()">Cerrar</button>
            </div>
        </div>
        <script>
            function closeModalAccount() {
                document.getElementById('accountDeactivatedModal').style.display = 'none';

                // Actualiza la URL sin el parámetro 'accountDeactivated'
                var url = new URL(window.location.href);
                url.searchParams.delete('accountDeactivated');
                window.history.replaceState({}, '', url);
            }

            // Mostrar el modal
            document.getElementById('accountDeactivatedModal').style.display = 'block';
        </script>
    <?php endif; ?>
    <header>
        <?php include 'public/templates/nav-blur.php' ?>
        <div class="carousel-content">
            <h1 data-translate="header_title">RECIEN HECHO, TODOS LOS DIAS</h1>
            <p data-translate="header_subtitle">Café recién preparado con granos seleccionados para ofrecerte una experiencia inigualable</p>
            <div class="buttons">
                <a href="#order" class="btn-order" data-translate="order_button">Reservar Ahora</a>
                <a href="#menu" class="btn-menu" data-translate="menu_button">Ver Menú</a>
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
        <section class="history">
            <div class="overlay-image"></div>
            <div class="content">
                <h1>
                    <span class="history_title" data-translate="history_title">Nuestra</span>
                    <span class="history_subtitle" data-translate="history_subtitle">Historia</span>
                </h1>
                <p data-translate="history_content">
                    Sabrosos Café nació en 1990, cuando la familia Valdez decidió compartir su pasión
                    por el café con el mundo. En un pequeño rincón del vecindario, la abuela Carmen,
                    con sus manos expertas, comenzó a moler granos de café, mientras su esposo,
                    Don Juan, se encargaba de darle vida al lugar, construyendo mesas de madera
                    maciza y decorando con cortinas a cuadros que hasta el día de hoy adornan
                    nuestras ventanas. Junto a sus hijos, Marta y Javier, convirtieron a Sabrosos en más
                    que una simple cafetería: un lugar donde la comunidad se encontraba, compartía
                    sus historias y forjaba recuerdos.
                </p>
            </div>
        </section>

        <section class="data">
            <div class="features">
                <div class="feature-item">
                    <div class="icon-container">
                        <!-- Aquí va el SVG del primer ícono -->
                        <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M50.55 6.34995H37.35C37.3 3.24995 34.55 0.449951 31.25 0.449951C27.95 0.449951 25.4 3.14995 25.35 6.39995H13.45C10.75 6.39995 8.5 8.59995 8.5 11.25V58.7C8.5 61.4 10.7 63.5499 13.45 63.5499H50.55C53.3 63.5499 55.5 61.35 55.5 58.7V11.25C55.5 8.54995 53.3 6.34995 50.55 6.34995ZM30.7 6.34995C30.85 6.29995 31.05 6.24995 31.25 6.24995C31.45 6.24995 31.65 6.24995 31.8 6.34995C32.6 6.59995 33.2 7.29995 33.2 8.19995C33.2 8.39995 33.2 8.59995 33.1 8.74995C32.85 9.54995 32.1 10.1 31.2 10.1C30.3 10.1 29.55 9.54995 29.35 8.74995C29.3 8.54995 29.25 8.34995 29.25 8.19995C29.3 7.34995 29.9 6.59995 30.7 6.34995ZM53.05 58.7C53.05 60.05 51.95 61.15 50.6 61.15H13.45C12.1 61.15 11 60.05 11 58.7V11.25C11 9.89995 12.1 8.79995 13.45 8.79995H25.35C25.35 10.75 25.35 12.1 25.35 12.1H19.45C18.35 12.1 17.45 13 17.45 14.05C17.45 15.1 17.45 16.7 17.45 16.7H45.2C45.2 16.7 45.2 15.1 45.2 14.05C45.2 13 44.3 12.1 43.2 12.1H37.3C37.3 12.1 37.35 10.85 37.35 8.79995H50.55C51.9 8.79995 53 9.89995 53 11.25V58.7H53.05ZM47.15 32.95H16.85V30.5H47.2V32.95H47.15ZM47.15 40.25H16.85V37.7999H47.2V40.25H47.15ZM47.15 47.5499H16.85V45.0999H47.2V47.5499H47.15ZM47.15 54.8499H16.85V52.4H47.2V54.8499H47.15ZM47.15 25.65H16.85V23.2H47.2V25.65H47.15Z" fill="#DAA520" />
                        </svg>


                    </div>
                    <h3 data-translate="feature1_title">PEDIDOS DESDE TU MESA O PARA LLEVAR</h3>
                    <p data-translate="feature1_description">Disfruta la comodidad de realizar tu pedido
                        desde tu smartphone. Fácil, rápido y con la
                        calidad que nos caracteriza.</p>
                </div>
                <div class="feature-item">
                    <div class="icon-container">
                        <!-- Aquí va el SVG del segundo ícono -->
                        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_348_172)">
                                <path d="M53.49 0H27.24C25.7482 0 24.3174 0.592628 23.2625 1.64752C22.2076 2.70241 21.615 4.13316 21.615 5.625V16.875C21.615 17.3723 21.8126 17.8492 22.1642 18.2008C22.5158 18.5525 22.9927 18.75 23.49 18.75C23.9873 18.75 24.4642 18.5525 24.8158 18.2008C25.1675 17.8492 25.365 17.3723 25.365 16.875V5.625C25.365 5.12772 25.5626 4.6508 25.9142 4.29917C26.2658 3.94754 26.7427 3.75 27.24 3.75H53.49C53.9873 3.75 54.4642 3.94754 54.8158 4.29917C55.1675 4.6508 55.365 5.12772 55.365 5.625V56.25H47.865V43.125C47.865 42.6277 47.6675 42.1508 47.3158 41.7992C46.9642 41.4475 46.4873 41.25 45.99 41.25H34.74C34.2427 41.25 33.7658 41.4475 33.4142 41.7992C33.0626 42.1508 32.865 42.6277 32.865 43.125V56.25H27.24C26.7427 56.25 26.2658 56.4475 25.9142 56.7992C25.5626 57.1508 25.365 57.6277 25.365 58.125C25.365 58.6223 25.5626 59.0992 25.9142 59.4508C26.2658 59.8025 26.7427 60 27.24 60H59.115V5.625C59.115 4.13316 58.5224 2.70241 57.4675 1.64752C56.4126 0.592628 54.9819 0 53.49 0ZM36.615 56.25V45H44.115V56.25H36.615ZM36.615 18.75H29.115V11.25H36.615V18.75ZM51.615 18.75H44.115V11.25H51.615V18.75ZM44.115 26.25H51.615V33.75H44.115V26.25ZM30.0338 33.75H36.615V26.25H29.115V31.9725C28.4612 30.7675 27.6462 29.6573 26.6925 28.6725C23.8512 25.8394 20.0025 24.2484 15.99 24.2484C11.9776 24.2484 8.12883 25.8394 5.28751 28.6725C2.45438 31.5138 0.863434 35.3626 0.863434 39.375C0.863434 43.3874 2.45438 47.2362 5.28751 50.0775L14.6625 59.4525C14.837 59.6265 15.044 59.7644 15.2718 59.8584C15.4996 59.9523 15.7436 60.0004 15.99 60C16.2364 60.0004 16.4805 59.9523 16.7083 59.8584C16.936 59.7644 17.1431 59.6265 17.3175 59.4525L26.6925 50.0775C28.7842 47.9803 30.2154 45.3162 30.8092 42.4143C31.4031 39.5125 31.1335 36.5003 30.0338 33.75ZM15.99 55.4737L7.94251 47.4225C5.81011 45.2872 4.61238 42.3928 4.61238 39.375C4.61238 36.3572 5.81011 33.4628 7.94251 31.3275C8.99826 30.2695 10.2527 29.4306 11.6338 28.8591C13.0149 28.2877 14.4954 27.9948 15.99 27.9975C17.4847 27.9948 18.9651 28.2877 20.3462 28.8591C21.7273 29.4306 22.9818 30.2695 24.0375 31.3275C26.1699 33.4628 27.3676 36.3572 27.3676 39.375C27.3676 42.3928 26.1699 45.2872 24.0375 47.4225L15.99 55.4737ZM21.615 39.375C21.615 40.8668 21.0224 42.2976 19.9675 43.3525C18.9126 44.4074 17.4819 45 15.99 45C14.4982 45 13.0674 44.4074 12.0125 43.3525C10.9576 42.2976 10.365 40.8668 10.365 39.375C10.365 37.8832 10.9576 36.4524 12.0125 35.3975C13.0674 34.3426 14.4982 33.75 15.99 33.75C17.4819 33.75 18.9126 34.3426 19.9675 35.3975C21.0224 36.4524 21.615 37.8832 21.615 39.375Z" fill="#DAA520" />
                            </g>
                            <defs>
                                <clipPath id="clip0_348_172">
                                    <rect width="60" height="60" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>

                    </div>
                    <h3 data-translate="feature2_title">EXPANSIÓN INTERNACIONAL</h3>
                    <p data-translate="feature2_description"> Próximamente en París, Berlín y Lisboa.
                        Llevamos nuestra tradición y
                        calidez a nuevas ciudades sin
                        perder nuestra esencia.</p>
                </div>
                <div class="feature-item">
                    <div class="icon-container">
                        <!-- Aquí va el SVG del tercer ícono -->
                        <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_348_157)">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M37.461 23.882C35.729 28.48 36.032 29.933 30.858 34.453C28.506 36.507 27.418 39.108 26.983 41.523L26.814 41.457C23.53 39.56 23.172 34.022 26.015 29.097C28.859 24.172 33.834 21.712 37.119 23.609L37.461 23.882Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M39.906 24.731C42.87 26.769 43.099 32.077 40.362 36.817C37.558 41.673 32.683 44.132 29.427 42.371C29.862 39.957 30.95 37.356 33.302 35.302C38.477 30.782 38.174 29.329 39.906 24.731Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M52.096 23.4861C54.5813 23.4861 56.596 20.7998 56.596 17.4861C56.596 14.1724 54.5813 11.4861 52.096 11.4861C49.6107 11.4861 47.596 14.1724 47.596 17.4861C47.596 20.7998 49.6107 23.4861 52.096 23.4861Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M22.216 11.0319C24.197 12.5299 24.183 15.8909 22.185 18.5319C20.187 21.1719 16.957 22.0999 14.976 20.6019C12.996 19.1029 13.01 15.7429 15.008 13.1019C17.005 10.4609 20.235 9.53292 22.216 11.0319Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.12 36.6989C18.101 38.1969 18.087 41.5579 16.089 44.1989C14.091 46.8399 10.861 47.7669 8.87999 46.2689C6.89899 44.7709 6.91299 41.4099 8.91099 38.7689C10.909 36.1279 14.139 35.1999 16.12 36.6989Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M52.39 49.8459C52.863 52.2839 50.607 54.7749 47.356 55.4049C44.105 56.0349 41.082 54.5669 40.61 52.1289C40.137 49.6909 42.393 47.1999 45.644 46.5699C48.895 45.9389 51.918 47.4069 52.39 49.8459Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M52.096 23.486C52.096 23.486 50.801 20.439 52.596 17.986C54.392 15.532 51.596 11.522 51.596 11.522" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M14.976 20.602C14.976 20.602 15.781 17.391 18.693 16.517C21.606 15.643 21.795 10.759 21.795 10.759" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8.88 46.269C8.88 46.269 9.685 43.058 12.597 42.184C15.51 41.311 15.699 36.426 15.699 36.426" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M15.699 31.3369C17.183 31.3369 18.386 30.1339 18.386 28.6499C18.386 27.1659 17.183 25.9629 15.699 25.9629C14.215 25.9629 13.012 27.1659 13.012 28.6499C13.012 30.1339 14.215 31.3369 15.699 31.3369Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M40.547 11.523C41.3959 11.523 42.084 10.8348 42.084 9.98597C42.084 9.13711 41.3959 8.44897 40.547 8.44897C39.6982 8.44897 39.01 9.13711 39.01 9.98597C39.01 10.8348 39.6982 11.523 40.547 11.523Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M54.596 41.9861C55.7006 41.9861 56.596 41.0907 56.596 39.9861C56.596 38.8815 55.7006 37.9861 54.596 37.9861C53.4914 37.9861 52.596 38.8815 52.596 39.9861C52.596 41.0907 53.4914 41.9861 54.596 41.9861Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M46.104 51.5731C48.855 52.8691 52.259 49.3621 52.259 49.3621" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_348_157">
                                    <rect width="64" height="64" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>

                    </div>
                    <h3 data-translate="feature3_title">CAFÉ DE CALIDAD</h3>
                    <p data-translate="feature3_description">Seleccionamos los mejores granos de
                        café para ofrecerte una experiencia
                        única en cada taza, siempre recién
                        molido y preparado con pasión.</p>
                </div>
            </div>
        </section>

        <section class="menu">
            <div class="menu-container">
                <div class="menu-left">
                    <h1>
                        <span class="menu_title" data-translate="menu_title">Descubre</span>
                        <span class="menu_subtitle" data-translate="menu_subtitle">NUESTRO MENÚ</span>
                    </h1>
                    <p data-translate="menu_description">
                        En tierras donde el sabor es rey, nuestros productos nacen de la perfecta unión entre tradición y calidad. Cada taza y cada plato cuentan una historia, ofreciendo un viaje sensorial único en cada experiencia.
                    </p>
                    <a href="/public/tienda.php"><button data-translate="menu_button_text">Ver menú completo</button></a>
                </div>
                <div class="menu-right">
                    <img src="/public/assets/img/productos/Cafés con Leche/cortado.jpg" alt="Menu Image 1"> <!-- Reemplaza con tus URLs de imágenes -->
                    <img src="/public/assets/img/productos/Cafés Especiales/flat-white.jpg" alt="Menu Image 2">
                    <img src="/public/assets/img/productos/Cafés Fríos/affogato.jpg" alt="Menu Image 3">
                    <img src="/public/assets/img/productos/Cafés Fríos/latte-con-hielo.jpg" alt="Menu Image 4">
                </div>
            </div>
        </section>

        <section class="data-2">
            <div class="features-2">
                <div class="feature-item-2">
                    <div class="icon-container-2">
                        <div class="icon-border"></div>
                        <svg width="36" height="41" viewBox="0 0 36 41" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_347_16)">
                                <path d="M18 20.5C20.7279 20.5 23.3442 19.4201 25.2731 17.4978C27.202 15.5756 28.2857 12.9685 28.2857 10.25C28.2857 7.53153 27.202 4.9244 25.2731 3.00216C23.3442 1.07991 20.7279 0 18 0C15.2721 0 12.6558 1.07991 10.7269 3.00216C8.79796 4.9244 7.71429 7.53153 7.71429 10.25C7.71429 12.9685 8.79796 15.5756 10.7269 17.4978C12.6558 19.4201 15.2721 20.5 18 20.5ZM14.3277 24.3438C6.4125 24.3438 0 30.734 0 38.6217C0 39.935 1.06875 41 2.38661 41H33.6134C34.9312 41 36 39.935 36 38.6217C36 30.734 29.5875 24.3438 21.6723 24.3438H14.3277Z" />
                            </g>
                            <defs>
                                <clipPath id="clip0_347_16">
                                    <rect width="36" height="41" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>

                    </div>
                    <h3 data-target="12475" data-translate="feature1_title-2">0</h3>
                    <p data-translate="feature1_description-2">Clientes registrados</p>
                </div>
                <div class="feature-item-2">
                    <div class="icon-container-s">
                        <div class="icon-border"></div>
                        <svg width="64" height="64" viewBox="0 0 64 64" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_347_34)">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M47.396 19.126H16.604C16.604 19.126 19.1 46.23 19.812 53.963C19.919 55.117 20.887 56 22.046 56H41.954C43.113 56 44.081 55.117 44.188 53.963C44.9 46.23 47.396 19.126 47.396 19.126Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M50.026 14.7059C50.026 13.4679 49.022 12.4639 47.784 12.4639C41.317 12.4639 22.683 12.4639 16.216 12.4639C14.978 12.4639 13.974 13.4679 13.974 14.7059C13.974 15.4079 13.974 16.1819 13.974 16.8829C13.974 18.1219 14.978 19.1259 16.216 19.1259C22.683 19.1259 41.317 19.1259 47.784 19.1259C49.022 19.1259 50.026 18.1219 50.026 16.8829C50.026 16.1819 50.026 15.4079 50.026 14.7059Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M44.375 12.464H19.625V10.243C19.625 9.004 20.629 8 21.867 8C26.572 8 37.428 8 42.133 8C43.371 8 44.375 9.004 44.375 10.243C44.375 11.355 44.375 12.464 44.375 12.464Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M30.939 34.8438C31.461 38.0128 32.107 38.7358 30.655 42.9858C29.995 44.9178 30.229 46.7448 30.772 48.2538L30.655 48.2718C28.176 48.2718 26.164 45.2538 26.164 41.5368C26.164 37.8188 28.176 34.8008 30.655 34.8008L30.939 34.8438Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M33.628 34.8008C35.972 34.9858 37.836 37.9158 37.836 41.4938C37.836 45.1588 35.88 48.1438 33.462 48.2108C32.919 46.7018 32.685 44.8748 33.345 42.9428C34.797 38.6928 34.15 37.9698 33.628 34.8008Z" stroke="#DAA520" stroke-width="2" stroke-miterlimit="2" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_347_34">
                                    <rect width="64" height="64" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>

                    </div>
                    <h3 data-target="65" data-translate="feature2_title-2">0</h3>
                    <p data-translate="feature2_description-2">Porductos para comprar</p>
                </div>
                <div class="feature-item-2">
                    <div class="icon-container-2">
                        <div class="icon-border"></div>
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_347_44)">
                                <path d="M33.31 27.259C48.657 21.456 48 5.478 48 5.316C47.99 5.12993 47.9091 4.95478 47.7738 4.8266C47.6386 4.69843 47.4593 4.62699 47.273 4.627H39.35C39.558 2.453 39.62 0.996 39.628 0.756C39.6318 0.658068 39.6157 0.560383 39.5808 0.4688C39.546 0.377218 39.4929 0.293624 39.4249 0.223029C39.357 0.152434 39.2754 0.0962921 39.1852 0.0579682C39.095 0.0196444 38.998 -7.22518e-05 38.9 2.6486e-07H9.10701C9.00909 -8.33711e-05 8.91217 0.0196414 8.82207 0.0579879C8.73198 0.0963344 8.65057 0.15251 8.58275 0.223135C8.51492 0.293761 8.46209 0.377376 8.42742 0.46895C8.39275 0.560525 8.37696 0.658166 8.38101 0.756C8.39001 0.996 8.45101 2.456 8.65901 4.627H0.733012C0.546749 4.62723 0.36764 4.69875 0.232447 4.82688C0.0972533 4.955 0.0162316 5.13002 0.00601159 5.316C1.15936e-05 5.478 -0.655989 21.462 14.7 27.263C15.9385 29.5713 17.7325 31.5348 19.92 32.976C19.4218 34.3429 18.656 35.5967 17.6675 36.6642C16.679 37.7317 15.4876 38.5914 14.163 39.193H9.46301C9.36741 39.1931 9.27277 39.2121 9.18449 39.2488C9.09622 39.2855 9.01604 39.3392 8.94853 39.4069C8.88102 39.4746 8.82751 39.555 8.79104 39.6433C8.75458 39.7317 8.73588 39.8264 8.73601 39.922V47.274C8.73653 47.4659 8.81278 47.6498 8.94817 47.7857C9.08356 47.9217 9.26715 47.9987 9.45901 48H38.55C38.7427 47.9997 38.9275 47.9231 39.0638 47.7868C39.2001 47.6505 39.2767 47.4657 39.277 47.273V39.922C39.2771 39.8264 39.2584 39.7317 39.222 39.6433C39.1855 39.555 39.132 39.4746 39.0645 39.4069C38.997 39.3392 38.9168 39.2855 38.8285 39.2488C38.7403 39.2121 38.6456 39.1931 38.55 39.193H33.85C32.5231 38.5949 31.3297 37.7365 30.3408 36.6685C29.3519 35.6006 28.5875 34.3449 28.093 32.976C30.2803 31.5338 32.0734 29.5688 33.31 27.259ZM46.552 6.082C46.517 8.988 45.617 20.061 34.383 25.232C37.316 19.086 38.626 11.224 39.199 6.082H46.552ZM1.45201 6.082H8.81001C9.38301 11.227 10.694 19.09 13.628 25.238C2.38501 20.07 1.48501 8.988 1.45001 6.082H1.45201ZM37.823 40.648V46.548H10.187V40.648H37.823ZM26.876 31.994C26.7282 32.0724 26.6119 32.1993 26.5467 32.3534C26.4815 32.5075 26.4714 32.6793 26.518 32.84C27.2593 35.3935 28.8278 37.6285 30.977 39.194H17.035C19.1836 37.6281 20.7516 35.3932 21.493 32.84C21.5395 32.6794 21.5294 32.5078 21.4644 32.3537C21.3995 32.1997 21.2835 32.0727 21.136 31.994C11.915 27.107 10.141 5.756 9.87501 1.455H38.135C37.87 5.755 36.1 27.107 26.876 31.994Z" />
                                <path d="M15.278 44.3241H32.732C32.9248 44.3241 33.1098 44.2475 33.2461 44.1112C33.3824 43.9748 33.459 43.7899 33.459 43.5971C33.459 43.4043 33.3824 43.2194 33.2461 43.083C33.1098 42.9467 32.9248 42.8701 32.732 42.8701H15.278C15.0852 42.8701 14.9003 42.9467 14.764 43.083C14.6276 43.2194 14.551 43.4043 14.551 43.5971C14.551 43.7899 14.6276 43.9748 14.764 44.1112C14.9003 44.2475 15.0852 44.3241 15.278 44.3241Z" />
                            </g>
                            <defs>
                                <clipPath id="clip0_347_44">
                                    <rect width="48.001" height="48.001" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>

                    </div>
                    <h3 data-target="35" data-translate="feature3_title-2">0</h3>
                    <p data-translate="feature3_description-2">Número de premios</p>
                </div>
                <div class="feature-item-2">
                    <div class="icon-container-2">
                        <div class="icon-border"></div>
                        <svg width="52" height="52" viewBox="0 0 52 52" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_347_50)">
                                <path d="M26 52C20.8577 52 15.8309 50.4751 11.5552 47.6182C7.27951 44.7613 3.94702 40.7007 1.97914 35.9498C0.0112654 31.1989 -0.503621 25.9712 0.499594 20.9277C1.50281 15.8842 3.97907 11.2514 7.61524 7.61524C11.2514 3.97907 15.8842 1.50281 20.9277 0.499594C25.9712 -0.503621 31.1989 0.0112654 35.9498 1.97914C40.7007 3.94702 44.7613 7.27951 47.6182 11.5552C50.4751 15.8309 52 20.8577 52 26C51.9926 32.8934 49.2509 39.5022 44.3766 44.3766C39.5022 49.2509 32.8934 51.9926 26 52ZM26 4.33335C21.7148 4.33335 17.5257 5.60407 13.9627 7.98484C10.3996 10.3656 7.62252 13.7495 5.98262 17.7085C4.34272 21.6676 3.91365 26.024 4.74966 30.227C5.58568 34.4299 7.64923 38.2905 10.6794 41.3207C13.7095 44.3508 17.5701 46.4144 21.7731 47.2504C25.976 48.0864 30.3324 47.6573 34.2915 46.0174C38.2506 44.3775 41.6344 41.6004 44.0152 38.0374C46.396 34.4743 47.6667 30.2853 47.6667 26C47.6604 20.2556 45.3756 14.7483 41.3137 10.6863C37.2518 6.62441 31.7444 4.33965 26 4.33335Z" />
                                <path d="M23.8333 21.6665H19.5C19.5 21.0919 19.2717 20.5408 18.8654 20.1344C18.459 19.7281 17.9079 19.4998 17.3333 19.4998C16.7587 19.4998 16.2076 19.7281 15.8012 20.1344C15.3949 20.5408 15.1666 21.0919 15.1666 21.6665H10.8333C10.8333 19.9426 11.5181 18.2893 12.7371 17.0703C13.9561 15.8513 15.6094 15.1665 17.3333 15.1665C19.0572 15.1665 20.7105 15.8513 21.9295 17.0703C23.1485 18.2893 23.8333 19.9426 23.8333 21.6665Z" />
                                <path d="M41.1667 21.6665H36.8334C36.8334 21.0919 36.6051 20.5408 36.1988 20.1344C35.7924 19.7281 35.2413 19.4998 34.6667 19.4998C34.0921 19.4998 33.541 19.7281 33.1346 20.1344C32.7283 20.5408 32.5 21.0919 32.5 21.6665H28.1667C28.1667 19.9426 28.8515 18.2893 30.0705 17.0703C31.2895 15.8513 32.9428 15.1665 34.6667 15.1665C36.3906 15.1665 38.0439 15.8513 39.2629 17.0703C40.4819 18.2893 41.1667 19.9426 41.1667 21.6665Z" />
                                <path d="M30.394 38.0555C24.381 37.7849 18.5947 35.6808 13.8125 32.0257L16.5187 28.6392C16.6162 28.7172 26.5287 36.4587 36.0187 32.654L37.6307 36.6797C35.3284 37.5962 32.872 38.0633 30.394 38.0555Z" />
                            </g>
                            <defs>
                                <clipPath id="clip0_347_50">
                                    <rect width="52" height="52" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>

                    </div>
                    <h3 data-target="11252" data-translate="feature4_title-2">0</h3>
                    <p data-translate="feature4_description-2">Clientes satisfechos</p>
                </div>
            </div>
        </section>

        <section class="mostSold">
            <div class="content-2">
                <div class="text-container">
                    <h1>
                        <span class="sold_title" data-translate="mostSold-title">Descubre</span><br>
                        <span class="sold_subtitle" data-translate="mostSold-subtitle">LOS CAFÉS MÁS VENDIDOS</span>
                    </h1>
                    <p data-translate="mostSold-description">Explora nuestras creaciones más populares, elaboradas con los granos más selectos y el toque perfecto de tradición. Cada taza es una experiencia que conquista los paladares de quienes buscan lo mejor.</p>
                </div>
                <div class="products">
                    <div class="product-item">
                        <img src="/public/assets/img/productos/Cafés Fríos/affogato.jpg" alt="Producto 1">
                        <h3 data-translate="product1-name">CAFÉ AFFOGATO</h3>
                        <p data-translate="product1-description">Café con helado de vainilla, combinando el sabor intenso del café con la dulzura del helado.</p>
                        <a href="https://cafesabrosos.myvnc.com/public/productos.php?id=32"><button data-translate="viewMore">Ver Más</button></a>
                    </div>
                    <div class="product-item">
                        <img src="/public/assets/img/productos/Cafés Especiales/flat-white.jpg" alt="Producto 2">
                        <h3 data-translate="product2-name">FLAT WHITE</h3>
                        <p data-translate="product2-description">Café con helado de vainilla, combinando el sabor intenso del café con la dulzura del helado.</p>
                        <a href="https://cafesabrosos.myvnc.com/public/productos.php?id=20"><button data-translate="viewMore">Ver Más</button></a>
                    </div>
                    <div class="product-item">
                        <img src="/public/assets/img/productos/Cafés Especiales/capuccino.jpg" alt="Producto 3">
                        <h3 data-translate="product3-name">CAFÉ CAPUCCINO</h3>
                        <p data-translate="product3-description">Café con helado de vainilla, combinando el sabor intenso del café con la dulzura del helado.</p>
                        <a href="https://cafesabrosos.myvnc.com/public/productos.php?id=19"><button data-translate="viewMore">Ver Más</button></a>
                    </div>
                    <div class="product-item">
                        <img src="/public/assets/img/productos/Cafés con Leche/latte-caramelo.jpg" alt="Producto 4">
                        <h3 data-translate="product4-name">LATTE DE CARAMELO</h3>
                        <p data-translate="product4-description">Café con helado de vainilla, combinando el sabor intenso del café con la dulzura del helado.</p>
                        <a href="https://cafesabrosos.myvnc.com/public/productos.php?id=22"><button data-translate="viewMore">Ver Más</button></a>
                    </div>
                </div>
            </div>
        </section>
        <?php 
            include 'public/templates/cookies.php';
         ?>
    </main>
    <?php include 'public/templates/footer.php' ?>
    <script src="/public/assets/js/nose.js"></script>
    <script src="/public/assets/js/index.js"></script>
    <script src="/public/assets/js/languageSelect.js"></script>
</body>
<script src="/public/assets/js/updateCartCounter.js"></script>

</html>