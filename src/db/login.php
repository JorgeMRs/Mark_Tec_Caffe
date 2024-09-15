<?php
session_start();
require_once 'db_connect.php';

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

            // Verificar si el correo existe en la tabla de clientes
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $hashed_password, $estado_activacion);
                $stmt->fetch();

                // Verificar el estado de activación
                if ($estado_activacion == 0) {
                    $response['message'] = 'La cuenta no está activada. Por favor, verifica tu correo para activar tu cuenta.';
                } else {
                    // Verificar si la contraseña es correcta
                    if (password_verify($contraseña, $hashed_password)) {
                        // El usuario es un cliente, eliminar las variables de sesión relacionadas con empleados
                        unset($_SESSION['employee_id']);
                        unset($_SESSION['role']);

                        // Guardar el ID del cliente y el correo electrónico en la sesión
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['user_email'] = $correo;

                        $response['success'] = true;
                        $response['redirect'] = '../../index.php';
                    } else {
                        $response['message'] = 'Contraseña incorrecta.';
                    }
                }
            } else {
                // Verificar en la tabla de empleados si el correo no existe en la tabla de clientes
                $stmt->close(); // Cerrar el primer stmt

                if ($stmt = $conn->prepare("
                    SELECT e.idEmpleado, e.contrasena, p.nombre AS puesto
                    FROM empleado e
                    JOIN puesto p ON e.idPuesto = p.idPuesto
                    WHERE e.correo = ?
                ")) {
                    $stmt->bind_param("s", $correo);
                    $stmt->execute();
                    $stmt->store_result();

                    // Verificar si el correo existe en la tabla de empleados
                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($employee_id, $hashed_password, $puesto);
                        $stmt->fetch();

                        // Verificar si la contraseña es correcta
                        if (password_verify($contraseña, $hashed_password)) {
                            // El usuario es un empleado, eliminar las variables de sesión relacionadas con clientes
                            unset($_SESSION['user_id']);

                            // Guardar datos del empleado en la sesión
                            $_SESSION['employee_id'] = $employee_id;
                            $_SESSION['role'] = $puesto;

                            // Redirigir según el rol
                            if ($puesto === 'Mozo') {
                                $response['success'] = true;
                                $response['redirect'] = '../../public/mozo/mozo.php';
                            } else {
                                $response['success'] = true;
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
