<?php
session_start();
include '../db/db_connect.php';

$response = array('success' => false, 'message' => '');

try {
    $conn = getDbConnection();
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos.');
    }

    if (isset($_POST['idProducto'])) {
        $idProducto = $_POST['idProducto'];

        // Verificar si el producto está vinculado a algún pedido
        $checkProductLinkQuery = "SELECT COUNT(*) as count FROM pedidodetalle WHERE idProducto = ?";
        $checkProductLinkStmt = $conn->prepare($checkProductLinkQuery);
        $checkProductLinkStmt->bind_param('i', $idProducto);
        $checkProductLinkStmt->execute();
        $linkResult = $checkProductLinkStmt->get_result();
        $linkData = $linkResult->fetch_assoc();
        $checkProductLinkStmt->close();

        if ($linkData['count'] > 0) {
            // El producto está vinculado a pedidos
            $response['message'] = 'El producto está asociado a pedidos y no puede ser eliminado.';
        } else {
            // Obtener la información del producto antes de eliminarlo
            $getProductQuery = "SELECT * FROM producto WHERE idProducto = ?";
            $getProductStmt = $conn->prepare($getProductQuery);
            $getProductStmt->bind_param('i', $idProducto);
            $getProductStmt->execute();
            $productResult = $getProductStmt->get_result();
            $product = $productResult->fetch_assoc();
            $getProductStmt->close();

            if ($product) {
                // Eliminar el producto completamente
                $deleteQuery = "DELETE FROM producto WHERE idProducto = ?";
                $deleteStmt = $conn->prepare($deleteQuery);
                $deleteStmt->bind_param('i', $idProducto);
                $deleteStmt->execute();
                $deleteStmt->close();

                // Guardar una copia de la información del producto en un archivo JSON
                $backupDir = '../../backups';
                if (!is_dir($backupDir)) {
                    mkdir($backupDir, 0755, true);
                }
                $backupFile = $backupDir . '/product_' . $idProducto . '.json';
                file_put_contents($backupFile, json_encode($product, JSON_PRETTY_PRINT));

                // Copiar la imagen del producto a la carpeta tmp
                $imagePath = $_SERVER['DOCUMENT_ROOT'] . $product['imagen'];
                $imageTmpPath = '../../tmp/' . basename($imagePath);
                if (file_exists($imagePath)) {
                    copy($imagePath, $imageTmpPath);
                }

                $response['success'] = true;
                $response['message'] = 'El producto ha sido eliminado exitosamente.';
            } else {
                $response['message'] = 'Error: El producto no existe o no se pudo obtener la información.';
            }
        }
    }
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage()); // Registra el error
    $response['message'] = $e->getMessage();
} finally {
    $conn->close();
    echo json_encode($response);
}
