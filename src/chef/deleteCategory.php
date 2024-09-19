<?php
session_start();
include '../db/db_connect.php';

$response = array('success' => false, 'message' => '');

try {
    $conn = getDbConnection();
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos.');
    }

    if (isset($_POST['idCategoria'])) {
        $idCategoria = $_POST['idCategoria'];

        // Verificar si alguno de los productos de la categoría está vinculado a un pedido
        $checkCategoryLinkQuery = "
            SELECT COUNT(*) as count 
            FROM producto p 
            JOIN pedidodetalle pd ON p.idProducto = pd.idProducto 
            WHERE p.idCategoria = ?";
        $checkCategoryLinkStmt = $conn->prepare($checkCategoryLinkQuery);
        $checkCategoryLinkStmt->bind_param('i', $idCategoria);
        $checkCategoryLinkStmt->execute();
        $linkResult = $checkCategoryLinkStmt->get_result();
        $linkData = $linkResult->fetch_assoc();
        $checkCategoryLinkStmt->close();

        if ($linkData['count'] > 0) {
            // La categoría tiene productos vinculados a pedidos
            $response['message'] = 'La categoría tiene productos asociados a pedidos y no puede ser eliminada. Solo se puede desactivar.';
        } else {
            // Obtener la información de la categoría y productos antes de eliminarla
            $getCategoryQuery = "SELECT * FROM categoria WHERE idCategoria = ?";
            $getCategoryStmt = $conn->prepare($getCategoryQuery);
            $getCategoryStmt->bind_param('i', $idCategoria);
            $getCategoryStmt->execute();
            $categoryResult = $getCategoryStmt->get_result();
            $category = $categoryResult->fetch_assoc();
            $getCategoryStmt->close();

            if ($category) {
                // Obtener todos los productos asociados a esta categoría
                $getProductsQuery = "SELECT * FROM producto WHERE idCategoria = ?";
                $getProductsStmt = $conn->prepare($getProductsQuery);
                $getProductsStmt->bind_param('i', $idCategoria);
                $getProductsStmt->execute();
                $productsResult = $getProductsStmt->get_result();
                $products = $productsResult->fetch_all(MYSQLI_ASSOC);
                $getProductsStmt->close();

                // Iniciar la transacción
                $conn->begin_transaction();

                try {
                    // Eliminar los productos vinculados a la categoría
                    $deleteProductsQuery = "DELETE FROM producto WHERE idCategoria = ?";
                    $deleteProductsStmt = $conn->prepare($deleteProductsQuery);
                    $deleteProductsStmt->bind_param('i', $idCategoria);
                    $deleteProductsStmt->execute();
                    $deleteProductsStmt->close();

                    // Eliminar la categoría
                    $deleteCategoryQuery = "DELETE FROM categoria WHERE idCategoria = ?";
                    $deleteCategoryStmt = $conn->prepare($deleteCategoryQuery);
                    $deleteCategoryStmt->bind_param('i', $idCategoria);
                    $deleteCategoryStmt->execute();
                    $deleteCategoryStmt->close();

                    // Guardar una copia de la información de la categoría en un archivo JSON
                    $backupDir = '../../backups';
                    if (!is_dir($backupDir)) {
                        mkdir($backupDir, 0755, true);
                    }
                    $backupFile = $backupDir . '/category_' . $idCategoria . '.json';
                    file_put_contents($backupFile, json_encode($category, JSON_PRETTY_PRINT));

                    // Guardar una copia de la información de los productos en un archivo JSON
                    $backupFileProducts = $backupDir . '/category_' . $idCategoria . '_products.json';
                    file_put_contents($backupFileProducts, json_encode($products, JSON_PRETTY_PRINT));

                    // Copiar la imagen de la categoría a la carpeta tmp
                    if (!empty($category['imagen'])) {
                        $categoryImagePath = $_SERVER['DOCUMENT_ROOT'] . $category['imagen'];
                        $categoryImageTmpPath = '../../tmp/' . basename($categoryImagePath);
                        if (file_exists($categoryImagePath)) {
                            copy($categoryImagePath, $categoryImageTmpPath);
                        }
                    }

                    // Copiar las imágenes de los productos a la carpeta tmp
                    foreach ($products as $product) {
                        if (!empty($product['imagen'])) {
                            $productImagePath = $_SERVER['DOCUMENT_ROOT'] . $product['imagen'];
                            $productImageTmpPath = '../../tmp/' . basename($productImagePath);
                            if (file_exists($productImagePath)) {
                                copy($productImagePath, $productImageTmpPath);
                            }
                        }
                    }

                    // Confirmar la transacción
                    $conn->commit();

                    $response['success'] = true;
                    $response['message'] = 'La categoría y los productos asociados han sido eliminados exitosamente.';
                } catch (Exception $e) {
                    // Revertir la transacción en caso de error
                    $conn->rollback();
                    throw new Exception('Error al eliminar la categoría y productos: ' . $e->getMessage());
                }
            } else {
                $response['message'] = 'Error: La categoría no existe o no se pudo obtener la información.';
            }
        }
    }
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage()); // Registrar el error
    $response['message'] = $e->getMessage();
} finally {
    $conn->close();
    echo json_encode($response);
}
