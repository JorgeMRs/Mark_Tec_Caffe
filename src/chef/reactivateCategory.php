<?php
session_start();

include '../db/db_connect.php';

$response = array('success' => false, 'message' => '');

try {
    $conn = getDbConnection();
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos.');
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'reactivarCategoria') {
        $idCategoria = $_POST['idCategoria'];

        // Reactivar la categoría
        $updateCategoriaQuery = "UPDATE categoria SET estadoActivacion = 1 WHERE idCategoria = ?";
        $updateCategoriaStmt = $conn->prepare($updateCategoriaQuery);
        $updateCategoriaStmt->bind_param('i', $idCategoria);
        $updateCategoriaStmt->execute();
        $updateCategoriaStmt->close();

        // Reactivar todos los productos asociados a esa categoría
        $updateProductosQuery = "UPDATE producto SET estadoActivacion = 1 WHERE idCategoria = ?";
        $updateProductosStmt = $conn->prepare($updateProductosQuery);
        $updateProductosStmt->bind_param('i', $idCategoria);
        $updateProductosStmt->execute();
        $updateProductosStmt->close();
        
        $response['success'] = true;
        $response['message'] = 'Categoría y productos asociados reactivados exitosamente.';
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    $conn->close();
    echo json_encode($response);
}
?>
