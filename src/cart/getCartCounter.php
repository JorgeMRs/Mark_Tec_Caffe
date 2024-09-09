<?php
session_start();
include '../db/db_connect.php';

// Obtener la cantidad del carrito del usuario autenticado

try {
    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        // No autorizado
        echo json_encode(['status' => 'error', 'message' => 'No autorizado.']);
        exit;
    }

    // Obtener ID del cliente desde la sesión
    $idCliente = $_SESSION['user_id'];

    // Obtener conexión a la base de datos
    $conn = getDbConnection();

    // Obtener la cantidad total de productos en el carrito en una sola consulta
    $queryCantidad = $conn->prepare('
        SELECT SUM(cd.cantidad) AS totalQuantity
        FROM carrito c
        LEFT JOIN carritodetalle cd ON c.idCarrito = cd.idCarrito
        WHERE c.idCliente = ?
    ');
    $queryCantidad->bind_param('i', $idCliente);
    $queryCantidad->execute();
    $resultCantidad = $queryCantidad->get_result();
    $cantidad = $resultCantidad->fetch_assoc();

    // Devolver la cantidad total o 0 si es nulo
    echo json_encode(['status' => 'success', 'totalQuantity' => $cantidad['totalQuantity'] ?? 0]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>