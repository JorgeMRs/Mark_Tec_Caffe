<?php
session_start();
require '../db/db_connect.php'; // Asegúrate de tener la conexión correcta

$response = ['validPassword' => false, 'message' => ''];
$email = $_SESSION['employee_email'];
$password = $_POST['password'];

try {
    // Obtener la conexión a la base de datos
    $mysqli = getDbConnection();

    // Prepara la consulta para buscar el empleado por correo
    $stmt = $mysqli->prepare("SELECT contrasena FROM empleado WHERE correo = ?");
    
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $mysqli->error);
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si se encontró un empleado con el correo proporcionado
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($password, $hashedPassword)) {
            $response['validPassword'] = true;
            $response['message'] = 'Contraseña verificada correctamente.';
        } else {
            $response['message'] = 'Contraseña incorrecta.';
        }
    } else {
        $response['message'] = 'Empleado no encontrado.';
    }

    $stmt->close();
    $mysqli->close();
} catch (Exception $e) {
    // En caso de error, se captura la excepción y se devuelve el mensaje de error
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>
