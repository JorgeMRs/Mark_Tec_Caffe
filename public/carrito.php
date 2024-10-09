<!DOCTYPE html>
<?php 

$pageTitle = 'Café Sabrosos - Tú Carrito';

$customCSS = [
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
    </header>
    <div class="container">
    <div class="header">
        <h1 id="cart-title">Tu Carrito</h1>
        <a href="/public/tienda.php">
            <button class="btn outline" id="back-to-store">
                <i class="fas fa-arrow-left"></i> Volver a la Tienda
            </button>
        </a>
    </div>

    <div id="cart-items" class="products">
        <table>
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th id="product-header">Producto</th>
                    <th id="price-header">Precio</th>
                    <th id="quantity-header">Cantidad</th>
                    <th id="total-header">Total</th>
                    <th id="actions-header">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se mostrarán los productos -->
            </tbody>
        </table>
    </div>

    <div id="empty-cart-message" style="display: none;">
        <p id="empty-cart-text">Tu carrito está vacío. ¡Vuelve a la tienda y agrega productos!</p>
    </div>

    <div class="total">
        <div id="subtotal-text">Subtotal: <span id="subtotal">$0.00</span></div>
        <div id="tax-text">Tax (20%): <span id="tax">$0.00</span></div>
        <div class="total-main" id="total-text">Total: <span id="total">$0.00</span></div>
        <button id="checkout-button" class="btn primary" disabled>Proceder con el pago</button>
    </div>
</div>

    <?php include 'templates/footer.php' ?>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
    background-image: url('assets/img/index/bg_4.jpg');
    background-repeat: no-repeat;
    background-size: cover;
}

.container {
    max-width: 950px;
    margin: 0 auto;
    margin-top: 210px;
    margin-bottom: 300px;
    padding: 20px;
    border-radius: 8px;
    color: white;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h1 {
    font-size: 2rem;
}

.btn {
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    background-color: transparent;
    transition: 0.4s;
    font-size: 12px;
}

.btn.outline {
    background-color: #1b0c0a;
    color: #daa520;
}


.btn.primary {
    background-color: #DAA520;
    color: #1B0C0A;
    transition: 0.5s;
}

.btn.primary:hover {
    background-color: #1B0C0A;
    color: #DAA520;
}

.btn.ghost {
    background: none;
    color: gray;
}

.products {
    margin-top: 20px;
}

.products table {
    width: 100%;
    border-collapse: collapse;
}

.products th, .products td {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #282828;
}

.products th {
    background-color: #1b0c0a;
    color: #ffffff;
}

.products td {
    color: white;
}

.product img {
    width: 140px; /* Cambia el ancho aquí para hacerlo más pequeño */
    height: auto; /* Mantiene la proporción de la imagen */
    border-radius: 4px;
}


.details {
    display: flex;
    flex-direction: column;
    text-align: center;
}

.controls {
    display: flex;
    align-items: center;
    border: solid 1px #272727;
    padding: 5px;
    width: 90%; /* Ajusta este ancho según sea necesario */
}

.quantity {
    width: 30px; /* Mantén un ancho fijo para la cantidad */
    text-align: center;
}

.total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
}

.total-main,
#total {
    font-size: 28px;
    color: white;
}

hr {
    margin: 20px 0;
}

h2 {
    margin-bottom: 0;
    font-family: 'Poppins';
    font-weight: 400;
    font-size: 18px;

}

p {
    margin-top: 5px;
    color: #474748;
    width: 80%;
    margin: 10px auto;
}

.btn.favorite i {
    color: red;
    transition: color 0.3s, transform 0.3s;
}

.btn.ghost i {
    transition: color 0.3s, transform 0.3s;
}

.btn.ghost:hover i {
    color: #DAA520; /* Cambiar a dorado en hover */
    transform: scale(1.2); /* Agrandar ligeramente */
}

.btn.favorite.selected i {
    color: #DAA520; /* Dorado */
}
/* Estilos para pantallas pequeñas */
@media (max-width: 600px) {
    .container {
        margin-top: 100px; /* Reduce el margen superior */
        margin-bottom: 50px; /* Reduce el margen inferior */
        padding: 10px; /* Reduce el padding */
    }

    .header h1 {
        font-size: 1.5rem; /* Reduce el tamaño de fuente del título */
    }

    .products th, .products td {
        padding: 10px; /* Reduce el padding en la tabla */
        font-size: 12px; /* Reduce el tamaño de la fuente */
    }

    .products img {
        width: 100px; /* Reduce el tamaño de las imágenes */
    }

    .controls {
        flex-direction: column; /* Apila los controles verticalmente */
        align-items: flex-start; /* Alinea a la izquierda */
    }

    .quantity {
        margin: 5px 0; /* Añade un margen entre los elementos */
    }

    .total {
        flex-direction: column; /* Apila los elementos en columna */
        align-items: flex-start; /* Alinea a la izquierda */
        font-size: 18px; /* Reduce el tamaño de la fuente */
    }

    .btn {
        font-size: 10px; /* Reduce el tamaño de los botones */
    }
}

    </style>
</body>
<script>

</script>
<script src="/public/assets/js/carrito.js"></script>
<script src="/public/assets/js/updateCartCounter.js"></script>
<script src="/public/assets/js/languageSelect.js"></script>
</html>