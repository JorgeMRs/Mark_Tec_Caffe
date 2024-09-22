<?php
ini_set('session.use_cookies', '0');
use Firebase\JWT\JWT;
require_once '../db/db_connect.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Obtener datos del formulario
        $correo = $_POST['email'] ?? '';
        $contraseña = $_POST['password'] ?? '';

        // Validar datos de entrada
        if (empty($correo) || empty($contraseña)) {
            throw new Exception('Correo y contraseña son obligatorios.');
        }

        $conn = getDbConnection();

        // Verificar en la tabla de clientes
        if ($stmt = $conn->prepare("SELECT idCliente, contrasena, estadoActivacion FROM cliente WHERE correo = ?")) {
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $hashed_password, $estado_activacion);
                $stmt->fetch();

                if ($estado_activacion == 0) {
                    $response['message'] = 'La cuenta no está activada. Por favor, verifica tu correo para activar tu cuenta.';
                } else {
                    if (password_verify($contraseña, $hashed_password)) {
                        $secretKey = $_ENV['JWT_SECRET'];
                        $expirationTime = time() + 86400;
                        $payload = [
                            'iat' => time(),
                            'exp' => $expirationTime,
                            'idCliente' => $user_id,
                            'email' => $correo,
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
                        $response['redirect'] = '../../index.php';
                    } else {
                        $response['message'] = 'Contraseña incorrecta.';
                    }
                }
            } else {
                // Verificar en la tabla de empleados si el correo no existe en la tabla de clientes
                $stmt->close();

                if ($stmt = $conn->prepare("SELECT e.idEmpleado, e.contrasena, p.nombre AS puesto FROM empleado e JOIN puesto p ON e.idPuesto = p.idPuesto WHERE e.correo = ?")) {
                    $stmt->bind_param("s", $correo);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($employee_id, $hashed_password, $puesto);
                        $stmt->fetch();

                        if (password_verify($contraseña, $hashed_password)) {
                            // Generar el token JWT para el empleado
                            $secretKey = $_ENV['JWT_SECRET'];
                            $expirationTime = time() + 28800; // 8 horas de validez
                            $payload = [
                                'iat' => time(),
                                'exp' => $expirationTime,
                                'idEmpleado' => $employee_id,
                                'rol' => $puesto, // Aquí guardas el rol del empleado
                            ];
                            $jwt_employee = JWT::encode($payload, $secretKey, 'HS256');
                        
                            // Guardar el token en una cookie
                            setcookie("employee_token", $jwt_employee, $expirationTime, "/", "", true, true);

                            // Redirigir según el rol
                            $response['success'] = true;
                            if ($puesto === 'Mozo') {
                                $response['redirect'] = '../../public/mozo/mozo.php';
                            } elseif ($puesto === 'Chef') {
                                $response['redirect'] = '../../public/chef/chef.php';
                            } else {
                                $response['redirect'] = '../../index.php';
                            }
                        } else {
                            $response['message'] = 'Contraseña incorrecta.';
                        }
                    } else {
                        $response['message'] = 'El correo ingresado no existe.';
                    }

                    $stmt->close();
                } else {
                    throw new Exception('Error al preparar la consulta.');
                }
            }
        } else {
            throw new Exception('Error al preparar la consulta.');
        }

        $conn->close();
    } else {
        throw new Exception('Método de solicitud no permitido.');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
