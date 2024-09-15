<?php
session_start();
include '../db/db_connect.php';

$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminarCategoria') {
    $idCategoria = $_POST['idCategoria'];

    // Iniciar transacción
    $conn->begin_transaction();
    try {
        // Obtener el nombre y la imagen actual de la categoría de la base de datos
        $stmt = $conn->prepare("SELECT nombre, imagen FROM categoria WHERE idCategoria = ?");
        $stmt->bind_param('i', $idCategoria);
        $stmt->execute();
        $stmt->bind_result($categoryName, $currentImage);
        $stmt->fetch();
        $stmt->close();

        // Obtener las imágenes de todos los productos asociados a la categoría
        $stmt = $conn->prepare("SELECT imagen FROM producto WHERE idCategoria = ?");
        $stmt->bind_param('i', $idCategoria);
        $stmt->execute();
        $stmt->bind_result($productImage);

        // Eliminar las imágenes de los productos en el servidor
        while ($stmt->fetch()) {
            if (!empty($productImage)) {
                $productImagePath = $_SERVER['DOCUMENT_ROOT'] . $productImage;
                if (file_exists($productImagePath)) {
                    unlink($productImagePath);
                }
            }
        }
        $stmt->close();

        // Eliminar todos los productos asociados a la categoría
        $stmt = $conn->prepare("DELETE FROM producto WHERE idCategoria = ?");
        $stmt->bind_param('i', $idCategoria);
        $stmt->execute();
        $stmt->close();

        // Eliminar la categoría
        $stmt = $conn->prepare("DELETE FROM categoria WHERE idCategoria = ?");
        $stmt->bind_param('i', $idCategoria);
        $stmt->execute();
        $stmt->close();

        // Eliminar la imagen de la categoría si existe
        $currentImagePath = $_SERVER['DOCUMENT_ROOT'] . $currentImage;
        if (!empty($currentImage) && file_exists($currentImagePath)) {
            unlink($currentImagePath);
        }

        // Eliminar la carpeta de la categoría (usando el nombre de la categoría)
        $categoryFolder = $_SERVER['DOCUMENT_ROOT'] . '/public/assets/img/productos/' . $categoryName;
        if (is_dir($categoryFolder)) {
            // Solo eliminar la carpeta si está vacía
            rmdir($categoryFolder);
        }

        // Confirmar transacción
        $conn->commit();
        $response['success'] = true;
        $response['message'] = 'Categoría y productos eliminados exitosamente.';
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conn->rollback();
        $response['message'] = 'Error al eliminar la categoría: ' . $e->getMessage();
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
