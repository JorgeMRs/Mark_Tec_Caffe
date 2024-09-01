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

    // Obtener datos del producto desde la solicitud POST
    $idProducto = $_POST['producto_id'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;

    if (!$idProducto || !$cantidad || !is_numeric($cantidad) || $cantidad <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
        exit;
    }

    // Obtener ID del cliente desde la sesión
    $idCliente = $_SESSION['user_id'];

    // Obtener conexión a la base de datos
    $conn = getDbConnection();

    // Iniciar transacción
    $conn->begin_transaction();

    // Verificar si el carrito del cliente ya existe
    $queryCarrito = $conn->prepare('SELECT idCarrito FROM carrito WHERE idCliente = ?');
    $queryCarrito->bind_param('i', $idCliente);
    $queryCarrito->execute();
    $resultCarrito = $queryCarrito->get_result();
    $carrito = $resultCarrito->fetch_assoc();

    if ($carrito) {
        // Si el carrito ya existe, usar su ID
        $idCarrito = $carrito['idCarrito'];
    } else {
        // Crear un nuevo carrito
        $queryInsertCarrito = $conn->prepare('INSERT INTO carrito (idCliente, fechaCreacion) VALUES (?, NOW())');
        $queryInsertCarrito->bind_param('i', $idCliente);
        $queryInsertCarrito->execute();
        $idCarrito = $conn->insert_id;
    }

    // Verificar si el producto ya está en el carrito
    $queryDetalle = $conn->prepare('SELECT idCarritoDetalle FROM carritodetalle WHERE idCarrito = ? AND idProducto = ?');
    $queryDetalle->bind_param('ii', $idCarrito, $idProducto);
    $queryDetalle->execute();
    $resultDetalle = $queryDetalle->get_result();
    $detalle = $resultDetalle->fetch_assoc();

    if ($detalle) {
        // Actualizar la cantidad si el producto ya está en el carrito
        $queryUpdate = $conn->prepare('UPDATE carritodetalle SET cantidad = cantidad + ? WHERE idCarrito = ? AND idProducto = ?');
        $queryUpdate->bind_param('iii', $cantidad, $idCarrito, $idProducto);
        $queryUpdate->execute();
    } else {
        // Insertar nuevo detalle del carrito
        $queryInsertDetalle = $conn->prepare('INSERT INTO carritodetalle (idCarrito, idProducto, cantidad, precio) VALUES (?, ?, ?, (SELECT precio FROM producto WHERE idProducto = ?))');
        $queryInsertDetalle->bind_param('iiii', $idCarrito, $idProducto, $cantidad, $idProducto);
        $queryInsertDetalle->execute();
    }

    // Confirmar transacción
    $conn->commit();

    echo json_encode(['status' => 'success', 'message' => 'Producto agregado al carrito.']);
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
