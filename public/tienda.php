<!DOCTYPE html>
<?php 

$pageTitle = 'Café Sabrosos - Compra los Mejores Cafés';

$customCSS = [
    '/public/assets/css/tienda.css',
    '/public/assets/css/nav-blur.css',
    '/public/assets/css/footer.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'
];
$customJS = [
  '/public/assets/js/languageSelect.js',
  '/public/assets/js/updateCartCounter.js'
];
include 'templates/head.php' ?>

<body>
    <header>
        <?php include 'templates/nav-blur.php' ?>
        <div class="header-content">
        <h2 class="top-subtitle" data-translate="topSubtitle">Café Sabrosos</h2>
<h2 class="subtitle" data-translate="subtitle">Siempre el mejor café</h2>
<div class="header-buttons">
    <a href="/public/menu.html" class="btn" data-translate="menuButton">Menú</a>
    <a href="/public/reservas.html" class="btn" data-translate="reservationsButton">Reservas</a>
</div>

            </div>
        </div>
    </header>
    <main>       
        <div id="category-details" class="content-container">


        <div class="sidebar" id="sidebar">
        <!-- Las categorías se cargarán aquí -->
         
    </div>
        </div>

    </main>
    <style>
.product-grid {
    position: relative; /* Asegúrate de que este contenedor tenga posición relativa */
    /* Otras propiedades como el tamaño y el margen pueden ir aquí */
}
.loader {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: none; /* Inicialmente oculto */
    position: relative;
    border: 3px solid;
    border-color: #1a0c0b #1a0c0b transparent transparent;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
    margin: 300px auto; /* Centrar con margen automático */
    margin-left: 400px;
}
.loader::after,
.loader::before {
  content: '';  
  box-sizing: border-box;
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  margin: auto;
  border: 3px solid;
  border-color: transparent transparent #DAA520 #DAA520;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  box-sizing: border-box;
  animation: rotationBack 0.5s linear infinite;
  transform-origin: center center;
}
.loader::before {
  width: 32px;
  height: 32px;
  border-color: #FFF #FFF transparent transparent;
  animation: rotation 1.5s linear infinite;
}
    
@keyframes rotation {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
} 
@keyframes rotationBack {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(-360deg);
  }
}
.sidebar-loader {
    border: 6px solid #f3f3f3; /* Color de fondo */
    border-radius: 50%;
    border-top: 6px solid #DAA520; /* Color de la animación */
    width: 50px;
    height: 50px;
    animation: spin 1.5s linear infinite;

    /* Posición absoluta para centrar */
    position: absolute;
    top: 50%; /* Mitad de la altura del sidebar */
    left: 40%; /* Mitad de la anchura del sidebar */
    transform: translate(-50%, -50%); /* Ajusta el centro del loader */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}


</style>

    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <?php include 'templates/footer.php'; ?>
    <script src="/public/assets/js/tienda.js"></script>
</body>

</html>