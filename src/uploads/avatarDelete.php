<?php
function deleteAvatar($user_id, $conn): string
{
    // Directorio de subida
    $uploadDir = '/var/www/html/Mark_Tec_Caffe/public/assets/img/avatars/';

    // Inicializar la variable
    $currentAvatar = '';

    // Consultar el nombre del avatar actual del usuario
    $sql = "SELECT avatar FROM cliente WHERE idCliente=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $stmt->bind_result($currentAvatar);
            if ($stmt->fetch()) {
                $stmt->close();

                // Verificar si hay un avatar para eliminar
                if ($currentAvatar) {
                    $fileToDelete = $uploadDir . $currentAvatar;

                    // Intentar eliminar el archivo
                    if (file_exists($fileToDelete)) {
                        if (!unlink($fileToDelete)) {
                            return 'Error eliminando el archivo del avatar.';
                        }
                    }
                }

                // Actualizar en la base de datos para eliminar el avatar
                $sqlUpdate = "UPDATE cliente SET avatar=NULL WHERE idCliente=?";
                if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
                    $stmtUpdate->bind_param("i", $user_id);
                    if ($stmtUpdate->execute()) {
                        $stmtUpdate->close();
                        return 'Avatar eliminado correctamente.';
                    } else {
                        $stmtUpdate->close();
                        return 'Error al actualizar el registro en la base de datos.';
                    }
                } else {
                    return 'Error preparando la consulta de actualización: ' . $conn->error;
                }
            } else {
                $stmt->close();
                return 'No se encontró un avatar para el usuario.';
            }
        } else {
            return 'Error ejecutando la consulta: ' . $stmt->error;
        }
    } else {
        return 'Error preparando la consulta: ' . $conn->error;
    }
}
