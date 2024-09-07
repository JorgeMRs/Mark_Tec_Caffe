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

        // Preparar la consulta para obtener el id, la contraseña cifrada y el estado de activación del cliente
        $conn = getDbConnection();

        if ($stmt = $conn->prepare("SELECT idCliente, contrasena, estadoActivacion FROM cliente WHERE correo = ?")) {
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $stmt->store_result();

            // Verificar si el correo existe
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $hashed_password, $estado_activacion);
                $stmt->fetch();

                // Verificar el estado de activación
                if ($estado_activacion == 0) {
                    $response['message'] = 'La cuenta no está activada. Por favor, verifica tu correo para activar tu cuenta.';
                } else {
                    // Verificar si la contraseña es correcta
                    if (password_verify($contraseña, $hashed_password)) {
                        $_SESSION['user_id'] = $user_id; // Guardar el ID del usuario en la sesión
                        $response['success'] = true;
                        $response['redirect'] = '../../index.php';
                    } else {
                        $response['message'] = 'Contraseña incorrecta.';
                    }
                }
            } else {
                $response['message'] = 'El correo ingresado no existe.';
            }

            $stmt->close();
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
