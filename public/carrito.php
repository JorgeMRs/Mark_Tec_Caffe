<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/assets/css/footer.css">
    <link rel="stylesheet" href="/public/assets/css/nav-blur.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Tu Carrito</title>
</head>

<body>
    <header>
        <?php include 'templates/nav-blur.php' ?>
    </header>
    <div class="container">
    <div class="header">
        <h1>Tu Carrito</h1>
        <a href="/public/tienda.php">
            <button class="btn outline">
                <i class="fas fa-arrow-left"></i> Volver a la Tienda
            </button>
        </a>
    </div>

    <!-- Contenedor donde se insertarán los productos dinámicamente -->
    <div id="cart-items" class="products">
        <table>
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se mostrarán los productos -->
            </tbody>
        </table>
    </div>

    <!-- Mensaje de carrito vacío (oculto por defecto) -->
    <div id="empty-cart-message" style="display: none;">
        <p>Tu carrito está vacío. ¡Vuelve a la tienda y agrega productos!</p>
    </div>

    <div class="total">
        <div>Subtotal: <span id="subtotal">$0.00</span></div>
        <div>Tax (20%): <span id="tax">$0.00</span></div>
        <div class="total-main">Total: <span id="total">$0.00</span></div>
        <button id="checkout-button" class="btn primary" disabled>Proceed to Checkout</button>
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
        /* Media Queries para hacer la página responsive */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .product {
                flex-direction: column;
                /* align-items: flex-start; */
                text-align: center;
            }

            .product img {
                width: 70px;
                height: 70px;
            }

            .details {
                padding: 10px 0;
                width: 100%;
            }

            .controls {
                /* width: 100%; */
                justify-content: space-between;
            }

            .total {
                flex-direction: column;
                align-items: flex-start;
            }

            .total div,
            .total button {
                width: 100%;
                margin-bottom: 10px;
                text-align: center;
            }

            .total-main,
            #total {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .product img {
                width: 60px;
                height: 60px;
            }

            .details h2 {
                font-size: 1rem;
            }

            .details p {
                font-size: 0.9rem;
            }

            .total-main,
            #total {
                font-size: 20px;
            }
        }
    </style>
</body>
<script>

</script>
<script src="/public/assets/js/carrito.js"></script>
<script src="/public/assets/js/updateCartCounter.js"></script>

</html>