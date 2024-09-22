<?php
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

function verifyToken() {
    $response = array('success' => false, 'message' => '');

    try {
        // Verificar el token de usuario
        if (isset($_COOKIE['user_token'])) {
            $jwt = $_COOKIE['user_token'];
            $secretKey = $_ENV['JWT_SECRET'];

            $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
            $idCliente = $decoded->idCliente;
            $email = $decoded->email;

            $response['success'] = true;
            $response['role'] = 'client'; // Indicar que es un cliente
            $response['idCliente'] = $idCliente;
            $response['email'] = $email;
            return $response; 
        }
        
        // Verificar el token de empleado
        if (isset($_COOKIE['employee_token'])) {
            $jwt_employee = $_COOKIE['employee_token'];
            $secretKey = $_ENV['JWT_SECRET']; // Asegúrate de usar la clave correcta

            $decoded = JWT::decode($jwt_employee, new Key($secretKey, 'HS256'));
            $idEmpleado = $decoded->idEmpleado;
            $rol = $decoded->rol;

            $response['success'] = true;
            $response['role'] = 'employee'; // Indicar que es un empleado
            $response['idEmpleado'] = $idEmpleado;
            $response['rol'] = $rol; // Agregar el rol a la respuesta
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
    $tokenResponse = verifyToken();
    if (!$tokenResponse['success']) {
        // Redirigir al login
        header('Location: /public/login.php');
        exit();
    }
    return $tokenResponse; // Retorna todo el array de respuesta
}
?>
