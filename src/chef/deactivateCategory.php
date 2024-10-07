<?php
include '../db/db_connect.php';
require '../../vendor/autoload.php';
require '../auth/verifyToken.php';

$response = checkToken();

$response = array('success' => false, 'message' => '');

try {
    $conn = getDbConnection();
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos.');
    }

    if (isset($_POST['idCategoria'])) {
        $idCategoria = $_POST['idCategoria'];

        // Iniciar una transacción
        $conn->begin_transaction();

        try {
            // Desactivar la categoría
            $updateCategoryQuery = "UPDATE categoria SET estadoActivacion = 0 WHERE idCategoria = ?";
            $updateCategoryStmt = $conn->prepare($updateCategoryQuery);
            $updateCategoryStmt->bind_param('i', $idCategoria);
            $updateCategoryStmt->execute();
            $updateCategoryStmt->close();

            // Desactivar todos los productos asociados a la categoría
            $updateProductsQuery = "UPDATE producto SET estadoActivacion = 0 WHERE idCategoria = ?";
            $updateProductsStmt = $conn->prepare($updateProductsQuery);
            $updateProductsStmt->bind_param('i', $idCategoria);
            $updateProductsStmt->execute();
            $updateProductsStmt->close();

            // Confirmar la transacción
            $conn->commit();

            $response['success'] = true;
            $response['message'] = 'La categoría y todos los productos asociados han sido desactivados con éxito.';
        } catch (Exception $e) {
            // Revertir la transacción si hay un error
            $conn->rollback();
            throw new Exception('Error al desactivar la categoría y productos: ' . $e->getMessage());
        }
    }
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage()); // Registrar el error
    $response['message'] = $e->getMessage();
} finally {
    $conn->close();
    echo json_encode($response);
}
