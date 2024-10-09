<?php
include '../db/db_connect.php';
require '../../vendor/autoload.php';
require '../auth/verifyToken.php';

$response = checkToken();
$email = $response['correoEmpleado'];

$passwordResponse = ['validPassword' => false, 'message' => ''];

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
            $passwordResponse['validPassword'] = true;
            $passwordResponse['message'] = 'Contraseña verificada correctamente.';
        } else {
            $passwordResponse['message'] = 'Contraseña incorrecta.';
        }
    } else {
        $passwordResponse['message'] = 'Empleado no encontrado.';
    }

    $stmt->close();
    $mysqli->close();
} catch (Exception $e) {
    // En caso de error, se captura la excepción y se devuelve el mensaje de error
    $passwordResponse['message'] = 'Error: ' . $e->getMessage();
}

// Devolver la respuesta de la verificación de contraseña en formato JSON
echo json_encode($passwordResponse);
?>
