<?php
require_once '../src/db/db_connect.php';

$token = $_GET['token'] ?? '';

if ($token) {
    $conn = getDbConnection();

    // Verificar el token
    $sql = "SELECT idCliente FROM cliente WHERE token_verificacion = ? AND estado_activacion = 0";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            // Activar la cuenta
            $sql = "UPDATE cliente SET estado_activacion = 1, token_verificacion = NULL WHERE token_verificacion = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $token);
                if ($stmt->execute()) {
                    $message = "Cuenta activada exitosamente. Ahora puedes iniciar sesión.";
                } else {
                    $message = "Error al activar la cuenta.";
                }
            } else {
                $message = "Error al preparar la consulta.";
            }
        } else {
            $message = "Token inválido o cuenta ya activada.";
        }
        $stmt->close();
    } else {
        $message = "Error al preparar la consulta.";
    }

    $conn->close();
} else {
    $message = "Token no proporcionado.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #242424;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #B9860A;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 15px;
        }
        .footer {
            color: #888;
            text-align: center;
            font-size: 14px;
            width: 100%;
            position: absolute;
            bottom: 0;
        }
        .footer p {
            color: #B9860A;
        }
        a {
            color: #B9860A;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Confirmación de Registro</h1>
    </div>
    <div class="container">
        <h1><?php echo htmlspecialchars($message); ?></h1>
        <p>Gracias por registrarte en nuestro sitio. Se ha enviado un correo electrónico a la dirección que proporcionaste con un enlace para activar tu cuenta.</p>
        <p>Por favor, revisa tu bandeja de entrada y sigue las instrucciones en el correo para activar tu cuenta.</p>
        <p>Si no ves el correo en tu bandeja de entrada, revisa tu carpeta de spam o correo no deseado.</p>
    </div>
    <div class="footer">
        <p>&copy; 2024 Café Sabrosos. Todos los derechos reservados.</p>
    </div>
</body>
</html>
