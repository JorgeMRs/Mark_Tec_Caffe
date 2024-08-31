<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

require_once './db_connect.php';
require_once '../email/verificationEmail.php';
$recaptchaSecret = $_ENV['recaptchaSecret'];

$response = array();

try {
    // Crear conexión
    $conn = getDbConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['passwordConfirm'] ?? '';
        
        
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        
        // Verificar el token de reCAPTCHA
        $recaptchaVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptchaResponse = file_get_contents($recaptchaVerifyUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
        $recaptchaResponseKeys = json_decode($recaptchaResponse, true);

        // Validar los datos
        if (empty($email) || empty($password) || empty($passwordConfirm)) {
            throw new Exception('Todos los campos son obligatorios.');
        }

        if ($password !== $passwordConfirm) {
            throw new Exception('Las contraseñas no coinciden.');
        }

        if (strlen($password) < 8) {
            throw new Exception('La contraseña debe tener al menos 8 caracteres.');
        }

        if (!$recaptchaResponseKeys['success']) {
            throw new Exception('Error en la verificación de reCAPTCHA. Inténtalo de nuevo.');
        }

        // Verificar si el correo ya está registrado
        $sql = "SELECT COUNT(*) as count FROM cliente WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row['count'] > 0) {
                throw new Exception('Correo ya registrado.');
            }
            $stmt->close();
        } else {
            throw new Exception('Error al preparar la consulta: ' . $conn->error);
        }

        // Encriptar contraseña
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Generar un token de verificación
        $verificationToken = bin2hex(random_bytes(16)); // Genera un token de 32 caracteres en hexadecimal

        // Insertar en la base de datos (sin activar la cuenta)
        $sql = "INSERT INTO cliente (correo, contrasena, estadoActivacion, tokenVerificacion) VALUES (?, ?, 0, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $email, $hashedPassword, $verificationToken);

            if ($stmt->execute()) {
                // Enviar el correo de verificación
                $verificationLink = "https://cafesabrosos.myvnc.com/public/registro.php?token=" . $verificationToken;
                $emailSubject = "Verifica tu cuenta";
                $emailBody = getVerificationEmailBody($verificationLink);

                if (sendEmail($email, $emailSubject, $emailBody)) {
                    $response['status'] = 'success';
                    $response['redirect'] = 'https://cafesabrosos.myvnc.com/index.php'; // Redirige al usuario a index.php
                } else {
                    throw new Exception('No se pudo enviar el correo de verificación.');
                }

            } else {
                throw new Exception('Error al insertar datos: ' . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception('Error al preparar la consulta: ' . $conn->error);
        }

        $conn->close();
    } else {
        throw new Exception('Método de solicitud no permitido.');
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
