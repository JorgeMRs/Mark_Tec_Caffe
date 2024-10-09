<?php
use GPBMetadata\Google\Cloud\Location\Locations;
require '../src/email/pedidoEmail.php';
require '../src/db/db_connect.php';
require '../src/auth/verifyToken.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$response = checkToken();

$user_id = $response['idCliente']; 
$email = $response['email'];

if (!isset($_GET['order_id'])) {
    header('Location: carrito.php');
    exit();
}

$orderId = intval($_GET['order_id']);


$conn = getDbConnection();


$stmt = $conn->prepare("SELECT numeroPedidoCliente FROM pedido WHERE idPedido = ? AND idCliente = ?");
$stmt->bind_param("ii", $orderId, $user_id);
$stmt->execute();
$stmt->bind_result($numeroPedidoCliente);
$stmt->fetch();
$stmt->close();

if (!$numeroPedidoCliente) {
    header('Location: carrito.php');
    exit();
}

// Verificar si el correo ya ha sido enviado
if (isset($_SESSION['order_id_sent']) && $_SESSION['order_id_sent'] == $orderId) {
    header('Location: /public/error/403.html');
    exit();
}

// Enviar el correo electrónico
sendOrderConfirmationEmail($orderId, $email);

// Marcar que el correo ha sido enviado
$_SESSION['order_id_sent'] = $orderId;

$conn->close();
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
    <h2 data-translate="order_confirmation.title">¡Gracias por tu pedido!</h2>
<p data-translate="order_confirmation.success_message">Tu pedido ha sido realizado con éxito. El ID de tu pedido es <strong><?php echo htmlspecialchars($numeroPedidoCliente); ?></strong>.</p>
<p data-translate="order_confirmation.email_confirmation">Recibirás un correo electrónico de confirmación con los detalles de tu pedido.</p>
<a href="/" data-translate="order_confirmation.back_button">Volver a la página principal</a>
<br><br>
<a href="/public/opinion.php" data-translate="order_confirmation.leave_review">¡No dudes en dejar tu opinión!</a>

    </div>
</body>
</html>
