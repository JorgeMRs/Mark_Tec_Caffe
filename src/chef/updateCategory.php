<?php
include '../db/db_connect.php';
require '../../vendor/autoload.php';
require '../auth/verifyToken.php';

$response = checkToken();

$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

$response = ['success' => false, 'message' => '', 'imagen' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizarCategoria') {
    $idCategoria = $_POST['idCategoria'];
    $nombreCategoria = $_POST['nombreCategoria'];
    $categoriaImagen = $_FILES['categoriaImagen'];

    // Validar datos
    if (empty($idCategoria) || empty($nombreCategoria)) {
        $response['message'] = 'Todos los campos son obligatorios.';
    } else {
        // Obtener la imagen actual de la base de datos
        $stmt = $conn->prepare("SELECT imagen FROM categoria WHERE idCategoria = ?");
        $stmt->bind_param('i', $idCategoria);
        $stmt->execute();
        $stmt->bind_result($currentImage);
        $stmt->fetch();
        $stmt->close();

        // Ruta de la imagen actual en el servidor
        $currentImagePath = $_SERVER['DOCUMENT_ROOT'] . $currentImage;

        // Preparar la consulta inicial
        $query = "UPDATE categoria SET nombre = ?";

        // Solo añade la parte de la consulta sobre la imagen si se sube una nueva imagen
        if ($categoriaImagen['error'] === UPLOAD_ERR_OK) {
            // Eliminar la imagen anterior si existe
            if (!empty($currentImage) && file_exists($currentImagePath)) {
                unlink($currentImagePath);
            }

            $imagenPath = '/public/assets/img/categorias/' . basename($categoriaImagen['name']);
            move_uploaded_file($categoriaImagen['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $imagenPath);
            $query .= ", imagen = ?";
        }

        $query .= " WHERE idCategoria = ?";

        // Prepara y ejecuta la consulta de actualización
        $stmt = $conn->prepare($query);
        if ($categoriaImagen['error'] === UPLOAD_ERR_OK) {
            $stmt->bind_param('ssi', $nombreCategoria, $imagenPath, $idCategoria);
        } else {
            $stmt->bind_param('si', $nombreCategoria, $idCategoria);
        }

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Categoría actualizada exitosamente.';
            $response['idCategoria'] = $idCategoria; // Añade el id de la categoría actualizada

            // Obtener la imagen actual de la base de datos (puede ser la nueva)
            $stmt = $conn->prepare("SELECT imagen FROM categoria WHERE idCategoria = ?");
            $stmt->bind_param('i', $idCategoria);
            $stmt->execute();
            $stmt->bind_result($currentImage);
            $stmt->fetch();
            $response['imagen'] = $currentImage; // Añade la ruta de la imagen actual o nueva
        } else {
            $response['message'] = 'Error al actualizar la categoría: ' . $stmt->error;
        }

        // Cierra la declaración si existe
        if ($stmt) {
            $stmt->close();
        }
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
