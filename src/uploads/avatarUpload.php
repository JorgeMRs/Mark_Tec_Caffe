<?php
function uploadAvatar($user_id, $file, $conn): string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return 'Error en la subida del archivo.';
    }

    $allowedTypes = ['image/jpeg', 'image/png'];
    if (!in_array($file['type'], $allowedTypes)) {
        return 'Tipo de archivo no permitido. Solo se permiten imágenes JPEG y PNG.';
    }

    // Directorio temporal
    $tempDir = '/var/www/html/Mark_Tec_Caffe/tmp/';
    if (!is_dir($tempDir) && !mkdir($tempDir, 0777, true)) {
        return 'No se pudo crear el directorio temporal: ' . $tempDir;
    }

    // Directorio de subida
    $uploadDir = '/var/www/html/Mark_Tec_Caffe/public/assets/img/avatars/';
    if (!is_dir($uploadDir)) {
        return 'Directorio de subida no encontrado o ruta incorrecta: ' . $uploadDir;
    }

    // Nombre fijo para el archivo de avatar
    $fileName = $user_id . '_avatar.jpg';
    $tempFile = $tempDir . $file['name'];
    $uploadFile = $uploadDir . $fileName;

    // Mover el archivo al directorio temporal primero
    if (!move_uploaded_file($file['tmp_name'], $tempFile)) {
        return 'Error moviendo el archivo al directorio temporal. Archivo temporal: ' . $file['tmp_name'] . ' Ruta de destino temporal: ' . $tempFile;
    }

    // Intentar copiar el archivo desde el directorio temporal al directorio de destino final
    if (!copy($tempFile, $uploadFile)) {
        return 'Error moviendo el archivo desde el directorio temporal al directorio de destino. Archivo temporal: ' . $tempFile . ' Ruta de destino: ' . $uploadFile;
    }

    // Eliminar el archivo temporal
    if (!unlink($tempFile)) {
        return 'Error eliminando el archivo temporal: ' . $tempFile;
    }

    // Actualizar en la base de datos
    $sql = "UPDATE cliente SET avatar=? WHERE idCliente=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $fileName, $user_id);
        if ($stmt->execute()) {
            $stmt->close();  // Mueve esto antes del return
            return 'Avatar actualizado correctamente.';
        } else {
            $stmt->close();  // Asegúrate de cerrar el statement en caso de error
            return "Error al actualizar el avatar en la base de datos.";
        }
    } else {
        return "Error preparando la consulta de actualización: " . $conn->error;
    }
}
