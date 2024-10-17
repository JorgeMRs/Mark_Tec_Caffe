<?php
header('Content-Type: application/json');
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
require_once '../db/db_connect.php';
require '../utils/encryptData.php';
$config = include __DIR__ . '/../config/config.php'; 
require_once __DIR__ . '/../../vendor/autoload.php'; // Ajusta la ruta según la ubicación de tu archivo

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$response = array();

try {
    $conn = getDbConnection();

    $data = json_decode(file_get_contents("php://input"), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $uid = $data['uid'] ?? null;
        $email = $data['email'] ?? null;
        $displayName = $data['displayName'] ?? null;
        $photoURL = $data['photoURL'] ?? null; 
        $termsAccepted = $data['termsAccepted'] ?? null; // Obtener el estado del checkbox
        $recaptchaResponse = $data['recaptchaResponse'] ?? null; // Obtener la respuesta de reCAPTCHA

        // Verificar que los datos requeridos estén presentes
        if (empty($uid) || empty($email)) {
            throw new Exception('Datos faltantes');
        }

        // Verificar si se han aceptado los términos
        if ($termsAccepted !== true) {
            throw new Exception('Términos y condiciones y política de privacidad no aceptados');
        }

        // Verificar el token de reCAPTCHA
        $recaptchaSecret = $_ENV['recaptchaSecret'];
        $recaptchaVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $responseKeys = json_decode(file_get_contents($recaptchaVerifyUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse), true);

        if (!$responseKeys["success"]) {
            throw new Exception('Error de validación de reCAPTCHA');
        }

        // Verificar si el usuario ya está registrado
        $stmt = $conn->prepare("SELECT idCliente FROM cliente WHERE uid = ? OR correo = ?");
        $stmt->bind_param("ss", $uid, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Usuario ya existe
            $response['message'] = 'El usuario ya está registrado';
        } else {
            // Generar una contraseña temporal
            $temporaryPassword = bin2hex(random_bytes(8)); // Genera una contraseña temporal de 16 caracteres

            // Encriptar la contraseña temporal
            $hashedPassword = password_hash($temporaryPassword, PASSWORD_BCRYPT);

            // Insertar nuevo usuario con la contraseña temporal
            $sql = "INSERT INTO cliente (uid, correo, nombre, contrasena, estadoActivacion) VALUES (?, ?, ?, ?, 1)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $uid, $email, $displayName, $hashedPassword);





            if ($stmt->execute()) {
                $user_id = $conn->insert_id; 

                $encryptedEmail = encryptData($correo, $encryptionKey);
                $encryptedUserId = encryptData($user_id, $encryptionKey);
                $encryptedUserUID = encryptData($uid, $encryptionKey);

                $secretKey = $_ENV['JWT_SECRET'];
                $expirationTime = time() + 3600;
                $payload = [
                    'iat' => time(),
                    'exp' => $expirationTime,
                    'idCliente' => $encryptedUserId,
                    'email' => $encryptedEmail,
                    'uid' => $encryptedUserUID,
                ];
                $jwt = JWT::encode($payload, $secretKey, 'HS256');
            
                // Guardar el token en una cookie
                setcookie("user_token", $jwt, [
                    'expires' => $expirationTime,
                    'path' => '/',
                    'secure' => true,    
                    'httponly' => true,   
                    'samesite' => 'Strict'
                ]);

                // Descargar y guardar la imagen de perfil
                $avatarFileName = "avatar_{$user_id}.jpg";
                $avatarPath = "/public/assets/img/avatars/$avatarFileName"; 
                $fullAvatarPath = $_SERVER['DOCUMENT_ROOT'] . $avatarPath; 
                
                $imageData = file_get_contents($photoURL); 

                if ($imageData !== false) {
                    // Asegúrate de que el directorio existe
                    if (!is_dir(dirname($fullAvatarPath))) {
                        mkdir(dirname($fullAvatarPath), 0755, true); 
                    }
                    
                    file_put_contents($fullAvatarPath, $imageData);
                
                    // Actualizar la columna avatar en la base de datos
                    $updateAvatarSql = "UPDATE cliente SET avatar = ? WHERE idCliente = ?";
                    $updateStmt = $conn->prepare($updateAvatarSql);
                    $updateStmt->bind_param("si", $avatarFileName, $user_id);
                    $updateStmt->execute();
                    $updateStmt->close();
                } else {
                    throw new Exception('No se pudo descargar la imagen de perfil.');
                }

                $response['status'] = 'success';
                $response['message'] = 'Usuario registrado correctamente';
                $response['avatar'] = $avatarPath; 
                $response['redirect'] = '../../index.php'; // Agregar la URL de redirección
            } else {
                throw new Exception('Error al insertar datos: ' . $stmt->error);
            }
        }

        $stmt->close();
    } else {
        throw new Exception('Método de solicitud no permitido.');
    }

    $conn->close();
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
