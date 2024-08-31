<?php
session_start();
include '../db/db_connect.php';

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

    // Obtener el ID del carrito
    $queryCarrito = $conn->prepare('SELECT idCarrito FROM carrito WHERE idCliente = ?');
    $queryCarrito->bind_param('i', $idCliente);
    $queryCarrito->execute();
    $resultCarrito = $queryCarrito->get_result();
    $carrito = $resultCarrito->fetch_assoc();

    if ($carrito) {
        $idCarrito = $carrito['idCarrito'];

        // Obtener la cantidad total de productos en el carrito
        $queryCantidad = $conn->prepare('SELECT SUM(cantidad) AS totalQuantity FROM carritodetalle WHERE idCarrito = ?');
        $queryCantidad->bind_param('i', $idCarrito);
        $queryCantidad->execute();
        $resultCantidad = $queryCantidad->get_result();
        $cantidad = $resultCantidad->fetch_assoc();

        echo json_encode(['status' => 'success', 'totalQuantity' => $cantidad['totalQuantity'] ?? 0]);
    } else {
        echo json_encode(['status' => 'success', 'totalQuantity' => 0]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>