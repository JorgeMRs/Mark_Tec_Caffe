<?php
session_start();
include '../db/db_connect.php'; // Ajusta la ruta según tu estructura de directorios

$response = array('success' => false, 'message' => '');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método de solicitud no permitido.');
    }

    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Usuario no autenticado.');
    }

    $userId = $_SESSION['user_id'];
    $orderType = $_POST['orderType'] ?? null;
    $orderNotes = $_POST['orderNotes'] ?? null;
    $pickupTime = $_POST['pickupTime'] ?? null;
    $branchId = $_POST['branch'] ?? null;

    if ($orderType === null || $orderNotes === null) {
        throw new Exception('Faltan datos del pedido.');
    }
    
    if (empty($branchId)) {
        throw new Exception('Debe seleccionar una sucursal.');
    }
    
    if ($orderType === 'Para llevar') {
        if (empty($pickupTime)) {
            throw new Exception('Debe especificar una hora de recogida para pedidos "Para llevar".');
        }
        $codigoVerificacion = 'PEDIDO' . str_pad(substr(md5(uniqid(rand(), true)), 0, 6), 6, '0', STR_PAD_LEFT);
    } else {
        $pickupTime = null;
        $codigoVerificacion = null;
    }

    $conn = getDbConnection();
    $conn->begin_transaction();

    // Obtener el último idCarrito del cliente
    $stmt = $conn->prepare("SELECT idCarrito FROM carrito WHERE idCliente = ? ORDER BY idCarrito DESC LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($cartId);
    $stmt->fetch();
    $stmt->close();

    if (!$cartId) {
        throw new Exception('No se encontró el carrito.');
    }

    // Calcular el subtotal
    $stmt = $conn->prepare("SELECT SUM(cantidad * precio) AS subtotal FROM carritodetalle WHERE idCarrito = ?");
    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    $stmt->bind_result($subtotal);
    $stmt->fetch();
    $stmt->close();

    if ($subtotal <= 0) {
        throw new Exception('El carrito está vacío o no tiene productos válidos.');
    }

    // Calcular el IVA y el total
    $ivaRate = 0.20;
    $tax = $subtotal * $ivaRate;
    $total = $subtotal + $tax;

    // Obtener el último número de pedido para este usuario
    $stmt = $conn->prepare("SELECT COALESCE(MAX(numeroPedidoUsuario), 0) AS ultimoNumero FROM pedido WHERE idCliente = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($ultimoNumeroPedido);
    $stmt->fetch();
    $stmt->close();

    $numeroPedidoUsuario = $ultimoNumeroPedido + 1;

    // Insertar el nuevo pedido con el número de pedido específico del usuario
    $stmt = $conn->prepare("INSERT INTO pedido (idCliente, idCarrito, idSucursal, tipoPedido, notas, horaRecogida, metodoPago, total, codigoVerificacion, numeroPedidoUsuario) VALUES (?, ?, ?, ?, ?, ?, 'Tarjeta', ?, ?, ?)");
    $stmt->bind_param("iiissdssi", $userId, $cartId, $branchId, $orderType, $orderNotes, $pickupTime, $total, $codigoVerificacion, $numeroPedidoUsuario);

    if (!$stmt->execute()) {
        throw new Exception('No se pudo realizar el pedido.');
    }

    $orderId = $stmt->insert_id;

    $stmt = $conn->prepare("INSERT INTO pedidodetalle (idPedido, idProducto, cantidad, precio) SELECT ?, idProducto, cantidad, precio FROM carritodetalle WHERE idCarrito = ?");
    $stmt->bind_param("ii", $orderId, $cartId);
    if (!$stmt->execute()) {
        throw new Exception('No se pudieron insertar los detalles del pedido.');
    }

    $stmt = $conn->prepare("DELETE FROM carritodetalle WHERE idCarrito = ?");
    $stmt->bind_param("i", $cartId);
    if (!$stmt->execute()) {
        throw new Exception('No se pudieron eliminar los detalles del carrito.');
    }

    $conn->commit();
    $response['success'] = true;
    $response['orderId'] = $orderId;
    $response['numeroPedidoUsuario'] = $numeroPedidoUsuario;
    $response['codigoVerificacion'] = $codigoVerificacion;

} catch (Exception $e) {
    if (isset($conn) && $conn->errno) {
        $conn->rollback();
    }
    $response['message'] = $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}

echo json_encode($response);
?>
