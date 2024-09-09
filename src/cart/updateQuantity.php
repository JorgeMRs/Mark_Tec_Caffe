<?php
session_start();
include '../db/db_connect.php';

try {
    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'No autorizado.']);
        exit;
    }

    $idProducto = $_POST['producto_id'] ?? null;
    $nuevaCantidad = $_POST['cantidad'] ?? null;
    $idCliente = $_SESSION['user_id'];

    if (!$idProducto || !$nuevaCantidad || !is_numeric($nuevaCantidad) || $nuevaCantidad <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
        exit;
    }

    $conn = getDbConnection();

    // Actualizar la cantidad del producto en el carrito
    $queryUpdate = $conn->prepare('UPDATE carritodetalle SET cantidad = ? WHERE idProducto = ? AND idCarrito = (SELECT idCarrito FROM carrito WHERE idCliente = ?)');
    $queryUpdate->bind_param('iii', $nuevaCantidad, $idProducto, $idCliente);
    $queryUpdate->execute();

    if ($queryUpdate->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Cantidad actualizada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la cantidad.']);
    }

    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
