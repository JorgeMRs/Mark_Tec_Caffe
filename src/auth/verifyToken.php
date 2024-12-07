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
        // Verificar el token de usuario (cliente)
        if (isset($_COOKIE['user_token'])) {
            $jwt = $_COOKIE['user_token'];
            $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));

            // Descifrar idCliente y email
            if (isset($decoded->idCliente) && isset($decoded->email)) {
                $idCliente = decryptData($decoded->idCliente, $encryptionKey);
                $email = decryptData($decoded->email, $encryptionKey);

                $response['success'] = true;
                $response['role'] = 'client';
                $response['idCliente'] = $idCliente;
                $response['email'] = $email;
                return $response;
            } else {
                throw new Exception("El token no contiene los datos necesarios para un cliente.");
            }
        }

        // Verificar el token de empleado
        if (isset($_COOKIE['employee_token'])) {
            $jwt_employee = $_COOKIE['employee_token'];
            $decoded = JWT::decode($jwt_employee, new Key($secretKey, 'HS256'));

            // Descifrar idEmpleado, rol y correo
            if (isset($decoded->idEmpleado) && isset($decoded->rol) && isset($decoded->correo)) {
                $idEmpleado = decryptData($decoded->idEmpleado, $encryptionKey);
                $rol = $decoded->rol;
                $correo = decryptData($decoded->correo, $encryptionKey);

                $response['success'] = true;
                $response['role'] = 'employee';
                $response['idEmpleado'] = $idEmpleado;
                $response['rol'] = $rol;
                $response['correoEmpleado'] = $correo;
                return $response;
            } else {
                throw new Exception("El token no contiene los datos necesarios para un empleado.");
            }
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
    return $tokenResponse; // Retorna todo el array de respuesta
}
?>
