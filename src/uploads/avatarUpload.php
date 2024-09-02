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

function uploadAvatar($user_id, $file, $conn): array
{
    $response = ['success' => false, 'message' => ''];

    try {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error en la subida del archivo.');
        }
        
        $maxFileSize = 3 * 1024 * 1024; // 3 MB
        if ($file['size'] > $maxFileSize) {
            throw new Exception('El tamaño del archivo excede el límite permitido de 3 MB.');
        }

        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido. Solo se permiten imágenes JPEG y PNG.');
        }

        $tempDir = '/var/www/cafesabrosos/tmp/';
        if (!is_dir($tempDir) && !mkdir($tempDir, 0777, true)) {
            throw new Exception('No se pudo crear el directorio temporal: ' . $tempDir);
        }

        $uploadDir = '/var/www/cafesabrosos/public/assets/img/avatars/';
        if (!is_dir($uploadDir)) {
            throw new Exception('Directorio de subida no encontrado o ruta incorrecta: ' . $uploadDir);
        }

        $fileName = $user_id . '_avatar.jpg';
        $tempFile = $tempDir . $file['name'];
        $uploadFile = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $tempFile)) {
            throw new Exception('Error moviendo el archivo al directorio temporal.');
        }

        if (!copy($tempFile, $uploadFile)) {
            throw new Exception('Error moviendo el archivo al directorio de destino.');
        }

        if (!unlink($tempFile)) {
            throw new Exception('Error eliminando el archivo temporal.');
        }

        $sql = "UPDATE cliente SET avatar=? WHERE idCliente=?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $fileName, $user_id);
            if ($stmt->execute()) {
                $stmt->close();
                $response = ['success' => true, 'avatar' => $fileName, 'message' => 'Avatar actualizado correctamente.'];
            } else {
                throw new Exception('Error al actualizar el avatar en la base de datos: ' . $stmt->error);
            }
        } else {
            throw new Exception('Error preparando la consulta de actualización: ' . $conn->error);
        }
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => $e->getMessage()];
    }

    return $response;
}

if (isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];
    $response = uploadAvatar($user_id, $file, $conn);
} else {
    $response = ['success' => false, 'message' => 'No se ha enviado un archivo.'];
}

echo json_encode($response);
$conn->close();
