<?php
session_start();
include '../db/db_connection.php'; // Asegúrate de ajustar el path a tu conexión a la base de datos


if ($_SERVER["REQUEST_METHOD"] == "POST") {

// Obtener datos del cuerpo de la solicitud JSON
$input = json_decode(file_get_contents('php://input'), true);
$correo = $input['correo'] ?? '';
$contraseña = $input['contrasena'] ?? '';

// Validar datos de entrada
if (empty($correo) || empty($contraseña)) {
    echo json_encode(['success' => false, 'message' => 'Correo y contraseña son obligatorios.']);
    exit();
}

// Preparar la consulta para obtener el id y la contraseña cifrada del cliente
if ($stmt = $conn->prepare("SELECT idCliente, contrasena FROM cliente WHERE correo = ?")) {
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    // Verificar si el correo existe y la contraseña es correcta
    if ($stmt->num_rows > 0 && password_verify($contraseña, $hashed_password)) {
        $_SESSION['user_id'] = $user_id; // Guardar el ID del usuario en la sesión
        echo json_encode(['success' => true]);
                // Redirigir a index.php
                header('Location: ../../index.html');
                exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta.']);
}

$conn->close();
}
?>