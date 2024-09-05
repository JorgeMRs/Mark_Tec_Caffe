<?php
session_start();
require_once 'db_connect.php'; // Ajusta el path a tu conexión a la base de datos

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener datos del formulario
    $correo = $_POST['email'] ?? '';
    $contraseña = $_POST['password'] ?? '';

    // Validar datos de entrada
    if (empty($correo) || empty($contraseña)) {
        echo json_encode(['success' => false, 'message' => 'Correo y contraseña son obligatorios.']);
        exit();
    }

    // Preparar la consulta para obtener el id, la contraseña cifrada y el estado de activación del cliente
    try {
        $conn = getDbConnection();
    } catch (Exception $e) {

    }
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
                echo json_encode(['success' => false, 'message' => 'La cuenta no está activada. Por favor, verifica tu correo para activar tu cuenta.']);
            } else {
                // Verificar si la contraseña es correcta
                if (password_verify($contraseña, $hashed_password)) {
                    $_SESSION['user_id'] = $user_id; // Guardar el ID del usuario en la sesión
                    echo json_encode(['success' => true, 'redirect' => '../../index.html']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'El correo ingresado no existe.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en la consulta.']);
    }

    $conn->close();
}
