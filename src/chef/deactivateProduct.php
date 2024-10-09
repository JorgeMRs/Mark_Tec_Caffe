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

    if (isset($_POST['idProducto'])) {
        $idProducto = $_POST['idProducto'];

        // Desactivar el producto
        $updateQuery = "UPDATE producto SET estadoActivacion = 0 WHERE idProducto = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $idProducto);
        $updateStmt->execute();
        $updateStmt->close();

        $response['success'] = true;
        $response['message'] = 'El producto ha sido desactivado y no está disponible para la venta.';
    }
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage()); // Registra el error
    $response['message'] = $e->getMessage();
} finally {
    $conn->close();
    echo json_encode($response);
}
