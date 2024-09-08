<?php
session_start();
include '../db/db_connect.php';

// Función para agregar un producto al carrito

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

    // Verificar si el carrito actual del cliente ya está vinculado a un pedido
    $queryPedido = $conn->prepare('SELECT idPedido FROM pedido WHERE idCarrito = (SELECT idCarrito FROM carrito WHERE idCliente = ? ORDER BY fechaCreacion DESC LIMIT 1) AND estado IN ("Pendiente", "En Preparación", "Listo para Recoger", "Completado")');
    $queryPedido->bind_param('i', $idCliente);
    $queryPedido->execute();
    $resultPedido = $queryPedido->get_result();

    if ($resultPedido->num_rows > 0) {
        // Si el carrito ya está vinculado a un pedido, crear un nuevo carrito
        $queryInsertCarrito = $conn->prepare('INSERT INTO carrito (idCliente, fechaCreacion) VALUES (?, NOW())');
        $queryInsertCarrito->bind_param('i', $idCliente);
        $queryInsertCarrito->execute();
        $idCarrito = $conn->insert_id;
    } else {
        // Si no está vinculado, reutilizar el carrito existente
        $queryCarrito = $conn->prepare('SELECT idCarrito FROM carrito WHERE idCliente = ? ORDER BY fechaCreacion DESC LIMIT 1');
        $queryCarrito->bind_param('i', $idCliente);
        $queryCarrito->execute();
        $resultCarrito = $queryCarrito->get_result();
        $carrito = $resultCarrito->fetch_assoc();

        if ($carrito) {
            $idCarrito = $carrito['idCarrito'];
        } else {
            // Si no existe carrito, crear uno nuevo
            $queryInsertCarrito = $conn->prepare('INSERT INTO carrito (idCliente, fechaCreacion) VALUES (?, NOW())');
            $queryInsertCarrito->bind_param('i', $idCliente);
            $queryInsertCarrito->execute();
            $idCarrito = $conn->insert_id;
        }
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
