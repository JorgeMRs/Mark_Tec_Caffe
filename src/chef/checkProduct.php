<?php
include '../db/db_connect.php';
require '../../vendor/autoload.php';
require '../auth/verifyToken.php';

$response = checkToken();

$response = array('success' => false, 'message' => '', 'productLinked' => false);

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

        // Establecer el estado de productLinked basado en el conteo
        $response['productLinked'] = $linkData['count'] > 0;

        $response['success'] = true;
        if ($response['productLinked']) {
            $response['message'] = 'El producto está asociado a pedidos.';
        } else {
            $response['message'] = 'El producto no está asociado a ningún pedido.';
        }
    }
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage()); // Registra el error
    $response['message'] = $e->getMessage();
} finally {
    $conn->close();
    echo json_encode($response);
}
