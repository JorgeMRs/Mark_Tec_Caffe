<?php
session_start();
require '../db/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['message' => 'No autorizado']);
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = getDbConnection();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// funcion para borrar avatar del usuario

try {
    // Directory where avatars are stored
    $uploadDir = '/var/www/cafesabrosos/public/assets/img/avatars/';

    // Query to get the current avatar name
    $sql = "SELECT avatar FROM cliente WHERE idCliente=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            throw new Exception('Error ejecutando la consulta: ' . $stmt->error);
        }

        $stmt->bind_result($currentAvatar);
        if (!$stmt->fetch()) {
            throw new Exception('No se encontró un avatar para el usuario.');
        }
        $stmt->close();
    } else {
        throw new Exception('Error preparando la consulta: ' . $conn->error);
    }

    // Check if there is an avatar to delete
    if ($currentAvatar) {
        $fileToDelete = $uploadDir . $currentAvatar;

        // Try to delete the file
        if (file_exists($fileToDelete)) {
            if (!unlink($fileToDelete)) {
                throw new Exception('Error eliminando el archivo del avatar.');
            }
        } else {
            throw new Exception('Archivo del avatar no encontrado.');
        }
    }

    // Update database to remove avatar reference
    $sqlUpdate = "UPDATE cliente SET avatar=NULL WHERE idCliente=?";
    if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
        $stmtUpdate->bind_param("i", $user_id);
        if (!$stmtUpdate->execute()) {
            throw new Exception('Error al actualizar el registro en la base de datos.');
        }
        $stmtUpdate->close();
    } else {
        throw new Exception('Error preparando la consulta de actualización: ' . $conn->error);
    }

    // If everything is successful, set the response
    $response['success'] = true;
    $response['message'] = 'Avatar eliminado correctamente.';

} catch (Exception $e) {
    // Handle any exceptions by setting the error message
    $response['message'] = $e->getMessage();
}

// Return the response as JSON
echo json_encode($response);

$conn->close();
?>
