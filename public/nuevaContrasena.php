<?php
session_start();
require_once '../src/db/db_connect.php';
require_once '../src/email/contrasenaEmail.php'; // Asegúrate de tener la función para enviar correos
require_once '../vendor/autoload.php'; 

$errorMessage = '';
$successMessage = '';

$token = $_GET['token'] ?? '';


if (!isset($_SESSION['token']) || $token !== $_SESSION['token']) {
    // Token no válido o no coincide, redirigir o mostrar un error
    header("Location: error/404.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'] ?? '';
    $nuevaContrasena = $_POST['nuevaContrasena'] ?? '';
    $confirmarContrasena = $_POST['confirmarContrasena'] ?? '';

    if ($nuevaContrasena === $confirmarContrasena) {
        $conn = getDbConnection();

        // Preparar la consulta para seleccionar el cliente por token
        $sql = "SELECT idCliente FROM cliente WHERE tokenVerificacion = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $token);

            if ($stmt->execute()) {
                $stmt->store_result(); // Store the result to check if there are rows
                $stmt->bind_result($idCliente);

                if ($stmt->num_rows > 0 && $stmt->fetch()) {
                    // Encriptar la nueva contraseña
                    $hashedPassword = password_hash($nuevaContrasena, PASSWORD_BCRYPT);

                    // Preparar y ejecutar la consulta para actualizar la contraseña
                    $sqlUpdate = "UPDATE cliente SET contrasena = ?, tokenVerificacion = NULL WHERE idCliente = ?";
                    if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
                        $stmtUpdate->bind_param("si", $hashedPassword, $idCliente);

                        if ($stmtUpdate->execute()) {
                            $successMessage = "Contraseña restablecida exitosamente.";
                        } else {
                            $errorMessage = "Error al actualizar la contraseña: " . $stmtUpdate->error;
                        }
                        $stmtUpdate->close();
                    } else {
                        $errorMessage = "Error preparando la consulta de actualización: " . $conn->error;
                    }
                } else {
                    $errorMessage = "Token de restablecimiento inválido.";
                }
                $stmt->free_result(); // Clear the result set
                $stmt->close();
            } else {
                $errorMessage = "Error ejecutando la consulta: " . $stmt->error;
            }
        } else {
            $errorMessage = "Error preparando la consulta: " . $conn->error;
        }
        $conn->close();
    } else {
        $errorMessage = "Las contraseñas no coinciden.";
    }
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
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 300px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #B9860A;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 20px;
        }
        button {
            background-color: #B9860A;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #a57a00;
        }
        .error-message, .success-message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Restablecer Contraseña</h1>
        <form action="nuevaContrasena.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label for="nuevaContrasena">Nueva Contraseña:</label>
            <input type="password" name="nuevaContrasena" id="nuevaContrasena" required>
            <label for="confirmarContrasena">Confirmar Nueva Contraseña:</label>
            <input type="password" name="confirmarContrasena" id="confirmarContrasena" required>
            <button type="submit">Restablecer Contraseña</button>
        </form>
        <?php if ($errorMessage): ?>
            <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
