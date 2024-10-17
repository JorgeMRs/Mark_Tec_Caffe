<?php
header('Content-Type: application/json');
require_once '../db/db_connect.php';
require '../utils/encryptData.php';
$config = include __DIR__ . '/../config/config.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

require_once __DIR__ . '/../../vendor/autoload.php'; // Ajusta la ruta según la ubicación de tu archivo

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$response = array();

try {
    $conn = getDbConnection();

    // Leer la solicitud JSON
    $data = json_decode(file_get_contents("php://input"), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $uid = $data['uid'] ?? null;
        $correo = $data['email'] ?? null;

        // Verificar que los datos requeridos estén presentes
        if (empty($uid) || empty($correo)) {
            throw new Exception('Datos faltantes');
        }

        // Verificar en la tabla de clientes
        if ($stmt = $conn->prepare("SELECT idCliente, estadoActivacion FROM cliente WHERE uid = ? OR correo = ?")) {
            $stmt->bind_param("ss", $uid, $correo);
            $stmt->execute();
            $stmt->store_result();

            // Verificar si el correo existe en la tabla de clientes
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $estado_activacion);
                $stmt->fetch();

                // Verificar el estado de activación
                if ($estado_activacion == 0) {
                    $response['message'] = 'La cuenta no está activada. Verifica tu correo o contactanos para más información';
                } else {

                $encryptedEmail = encryptData($correo, $encryptionKey);
                $encryptedUserId = encryptData($user_id, $encryptionKey);
                    
                    $secretKey = $_ENV['JWT_SECRET'];
                    $expirationTime = time() + 3600;
                    $payload = [
                        'iat' => time(),
                        'exp' => $expirationTime,
                        'idCliente' => $encryptedUserId,
                        'email' => $encryptedEmail,
                        'uid' => $uid,
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

                    $response['success'] = true;
                    $response['redirect'] = '../../index.php'; // Cambia la redirección según sea necesario
                }
            } else {
                $response['message'] = 'Usuario no registrado.';
            }

            $stmt->close();
        } else {
            throw new Exception('Error en la consulta.');
        }
    } else {
        throw new Exception('Método de solicitud no permitido.');
    }

    $conn->close();
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
