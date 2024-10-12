<?php
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

// Cargar el archivo de configuración
$config = include __DIR__ . '/../config/config.php'; 
include __DIR__ . '/../utils/encryptData.php';

function verifyToken($secretKey, $encryptionKey) {
    $response = array('success' => false, 'message' => '');

    try {
        // Verificar el token de usuario
        if (isset($_COOKIE['user_token'])) {
            $jwt = $_COOKIE['user_token'];
            $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));

            // Descifrar el idCliente y el email
            $idCliente = decryptData($decoded->idCliente, $encryptionKey);
            $email = decryptData($decoded->email, $encryptionKey);
            $uid = decryptData($decoded->uid, $encryptionKey);

            $response['success'] = true;
            $response['role'] = 'client'; // Indicar que es un cliente
            $response['idCliente'] = $idCliente;
            $response['email'] = $email;
            $response['uid'] = $uid; // Agregar uid a la respuesta
            
            return $response; 
        }
        
        // Verificar el token de empleado
        if (isset($_COOKIE['employee_token'])) {
            $jwt_employee = $_COOKIE['employee_token'];
            $decoded = JWT::decode($jwt_employee, new Key($secretKey, 'HS256'));

            // Descifrar el idEmpleado y el correo
            $idEmpleado = decryptData($decoded->idEmpleado, $encryptionKey);
            $rol = $decoded->rol; // El rol no estaba cifrado
            $correo = decryptData($decoded->correo, $encryptionKey);

            $response['success'] = true;
            $response['role'] = 'employee'; // Indicar que es un empleado
            $response['idEmpleado'] = $idEmpleado;
            $response['rol'] = $rol; 
            $response['correoEmpleado'] = $correo;
            return $response; 
        }

        $response['message'] = "Token no proporcionado.";

    } catch (ExpiredException $e) {
        $response['message'] = "El token ha expirado.";
    } catch (Exception $e) {
        $response['message'] = "Token inválido: " . $e->getMessage();
    }

    return $response;
}

function checkToken() {
    global $config; 
    $secretKey = $config['secretKey'];
    $encryptionKey = $config['encryptionKey'];
    
    $tokenResponse = verifyToken($secretKey, $encryptionKey);
    if (!$tokenResponse['success']) {
        header('Location: /public/login.php');
        exit();
    }
    return $tokenResponse; // Retorna todo el array de respuesta
}
?>
