<?php
session_start();
require_once '../src/db/db_connect.php';
require_once '../src/email/contrasenaEmail.php'; // Ensure this file is correctly included
require_once '../vendor/autoload.php'; // Adjust the path as necessary

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

$errorMessage = '';
$successMessage = '';

try {
    // Check request method
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

        // Validate email
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El correo ingresado no es válido.');
        }

        $conn = getDbConnection();

        // Prepare and execute the selection query
        $sql = "SELECT idCliente FROM cliente WHERE correo = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $correo);

            if ($stmt->execute()) {
                $stmt->store_result();
                $stmt->bind_result($idCliente);
                if ($stmt->fetch()) {
                    // Generate a token
                    $token = bin2hex(random_bytes(16));

                    // Prepare and execute the update query
                    $sqlUpdate = "UPDATE cliente SET tokenVerificacion = ? WHERE idCliente = ?";
                    if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
                        $stmtUpdate->bind_param("si", $token, $idCliente);

                        if ($stmtUpdate->execute()) {
                            // Send the email
                            $resetLink = "https://cafesabrosos.myvnc.com/public/nuevaContrasena.php?token=" . urlencode($token);
                            $emailBody = getPasswordResetEmailBody($resetLink);
                            $subject = "Restablecimiento de Contraseña";
                            if (sendPasswordResetEmail($correo, $subject, $emailBody)) {
                                $successMessage = "Se ha enviado un enlace para restablecer tu contraseña al correo.";
                            } else {
                                throw new Exception("Error al enviar el correo de restablecimiento.");
                            }
                        } else {
                            throw new Exception("Error al actualizar el token en la base de datos.");
                        }
                        $stmtUpdate->close();
                    } else {
                        throw new Exception("Error preparando la consulta de actualización: " . $conn->error);
                    }
                } else {
                    throw new Exception("No se encontró un usuario con ese correo.");
                }
                $stmt->free_result(); // Clear the result set
            } else {
                throw new Exception("Error ejecutando la consulta: " . $stmt->error);
            }
            $stmt->close();
        } else {-
            throw new Exception("Error preparando la consulta: " . $conn->error);
        }
        $conn->close();
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
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
    <title>Solicitar Restablecimiento de Contraseña</title>
</head>

<body>
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
            display: flex;
            margin: 300px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex-direction: column;
            align-items: center;
            justify-content: center;
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

        input[type="email"] {
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

        .error-message,
        .success-message {
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
    <div class="container">
        <h1>Solicitar Restablecimiento de Contraseña</h1>
        <form action="cambiarContrasena.php" method="POST">
            <label for="correo">Correo Electrónico:</label>
            <input type="email" name="correo" id="correo" required>
            <button type="submit">Enviar Enlace de Restablecimiento</button>
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