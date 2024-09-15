<?php
session_start();

include '../db/db_connect.php';

$response = array('success' => false, 'message' => '');

try {
    $conn = getDbConnection();
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos.');
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminarProducto') {
        $idProducto = $_POST['idProducto'];

        // Preparar la consulta para eliminar el producto
        $query = "DELETE FROM producto WHERE idProducto = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta para eliminar el producto.');
        }
        $stmt->bind_param('i', $idProducto);
        $stmt->execute();
        $stmt->close();

        $response['success'] = true;
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    $conn->close();
    echo json_encode($response);
}
?>