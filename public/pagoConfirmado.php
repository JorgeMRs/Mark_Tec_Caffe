<?php
require '../src/email/pedidoEmail.php';
session_start();
require '../src/db/db_connect.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar si el ID del pedido se ha pasado en la URL
if (!isset($_GET['order_id'])) {
    header('Location: carrito.php');
    exit();
}

$userId = $_SESSION['user_id'];
$email = $_SESSION['user_email'];
$orderId = intval($_GET['order_id']);

$orderDetails = getOrderDetails($orderId);

if (!$orderDetails) {
    header('Location: carrito.php');
    exit();
}

$numeroPedidoUsuario = $orderDetails['numeroPedidoUsuario'];


// Llamada a la función para enviar el correo
sendOrderConfirmationEmail($orderId, $email);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Confirmación de pedido">
    <meta name="author" content="MarkTec">
    <title>Confirmación de Pedido</title>
    <link rel="icon" href="assets/img/icons/favicon-32x32.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .confirmation-container {
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-radius: 8px;
            max-width: 400px;
            margin: auto;
        }

        h2 {
            color: #DAA520;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
            color: #ffffff;
            background-color: #DAA520;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            color: #1B0D0B;
        }

        a:hover {
            background-color: #1B0D0B;
            color: #DAA520;
        }

        strong {
            color: #000;
        }

        @media (max-width: 600px) {
            .confirmation-container {
                padding: 20px;
                width: 100%;
                box-shadow: none;
            }

            h2 {
                font-size: 20px;
            }

            p, a {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="confirmation-container">
        <h2>¡Gracias por tu pedido!</h2>
        <p>Tu pedido ha sido realizado con éxito. El ID de tu pedido es <strong><?php echo htmlspecialchars($numeroPedidoUsuario); ?></strong>.</p>
        <p>Recibirás un correo electrónico de confirmación con los detalles de tu pedido.</p>
        <a href="/">Volver a la página principal</a>
    </div>
</body>
</html>
