<?php
require_once '../src/db/db_connect.php';

$token = $_GET['token'] ?? '';
$message = ''; // Mensaje por defecto

if ($token) {
    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        $message = "Error al conectar con la base de datos. Por favor, inténtalo de nuevo más tarde.";
        echo $message;
        exit;
    }

    // Verificar el token
    $sql = "SELECT idCliente FROM cliente WHERE tokenVerificacion = ? AND estadoActivacion = 0";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            // Activar la cuenta
            $sql = "UPDATE cliente SET estadoActivacion = 1, tokenVerificacion = NULL WHERE tokenVerificacion = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $token);
                if ($stmt->execute()) {
                    $message = "¡Tu cuenta ha sido activada exitosamente!";
                } else {
                    $message = "No se pudo activar tu cuenta. Intenta de nuevo más tarde.";
                }
            } else {
                $message = "Error al preparar la consulta para activar la cuenta.";
            }
        } else {
            $message = "El token es inválido o la cuenta ya ha sido activada.";
        }
        $stmt->close();
    } else {
        $message = "Error al preparar la consulta para verificar el token.";
    }

    $conn->close();
} else {
    $message = "No se proporcionó un token de activación.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-64x64.png">
    <title>Confirmación de Registro</title>
    <link rel="stylesheet" href="assets/css/registroExitoso.css">
</head>
<body>
    <div class="header">
    <h1 data-translate="register_confirmation.title">Confirmación de Registro</h1>
<div class="container">
    <h1><?php echo htmlspecialchars($message); ?></h1>
    <p data-translate="register_confirmation.message">¡Hola!<br><br>
    Gracias por registrarte en Café Sabrosos. Tu cuenta ha sido activada exitosamente. Ahora puedes iniciar sesión y disfrutar de todas las funcionalidades que ofrecemos.<br><br>
    Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.<br><br>
    ¡Bienvenido a Café Sabrosos!</p>
    <a href="/" class="btn-back" data-translate="register_confirmation.back_button">Volver a Inicio</a>
</div>
<div class="footer">
    <p>&copy; 2024 Café Sabrosos. <span data-translate="register_confirmation.footer">Todos los derechos reservados.</span></p>
</div>

    </div>
</body>
</html>
