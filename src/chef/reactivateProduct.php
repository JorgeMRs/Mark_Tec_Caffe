<?php
session_start();

include '../db/db_connect.php';

$response = array('success' => false, 'message' => '');

try {
    $conn = getDbConnection();
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos.');
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'reactivarProducto') {
        $idProducto = $_POST['idProducto'];

        // Reactivar el producto
        $updateQuery = "UPDATE producto SET estadoActivacion = 1 WHERE idProducto = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $idProducto);
        $updateStmt->execute();
        $updateStmt->close();
        
        $response['success'] = true;
        $response['message'] = 'Producto reactivado exitosamente.';
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    $conn->close();
    echo json_encode($response);
}
?>
