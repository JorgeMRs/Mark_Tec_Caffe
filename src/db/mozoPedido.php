<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Verifica si el empleado está autenticado
if (!isset($_SESSION['employee_id'])) {
    $response['message'] = 'No estás autenticado.';
    echo json_encode($response);
    exit;
}

try {
    $conn = getDbConnection();

    $employeeId = $_SESSION['employee_id'];
    $tipoPedido = $_POST['tipoPedido'] ?? '';
    $horaRecogida = $_POST['horaRecogida'] ?? null;
    $numeroMesa = $_POST['numeroMesa'] ?? null;
    $notas = $_POST['notas'] ?? '';
    $total = 0.00;

    // Validar el valor de tipoPedido
    $valoresValidos = ['En el local', 'Para llevar'];
    if (!in_array($tipoPedido, $valoresValidos)) {
        $response['message'] = 'Tipo de pedido no válido.';
        echo json_encode($response);
        exit;
    }

    // Obtener el idSucursal del empleado
    $sql = "SELECT idSucursal FROM empleado WHERE idEmpleado = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $employeeId);
    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar la consulta para obtener idSucursal.');
    }
    $stmt->bind_result($idSucursal);
    $stmt->fetch();
    $stmt->close();

    if (!$idSucursal) {
        $response['message'] = 'Sucursal no encontrada para el empleado.';
        echo json_encode($response);
        exit;
    }

    // Obtener idMesa si el pedido es para comer en el local
    $idMesa = null;
    if ($tipoPedido === 'En el local') {
        $sql = "SELECT idMesa FROM mesa WHERE numero = ? AND idSucursal = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $numeroMesa, $idSucursal);
        if (!$stmt->execute()) {
            throw new Exception('Error al ejecutar la consulta para obtener idMesa.');
        }
        $stmt->bind_result($idMesa);
        $stmt->fetch();
        $stmt->close();
    }

    if ($tipoPedido === 'En el local' && !$idMesa) {
        $response['message'] = 'Mesa no encontrada para la sucursal.';
        echo json_encode($response);
        exit;
    }

    // Insertar el pedido en la tabla pedido
    $sql = "INSERT INTO pedido (idEmpleado, idCliente, idCarrito, idMesa, idSucursal, estado, notas, horaRecogida, metodoPago, tipoPedido, total) 
    VALUES (?, NULL, NULL, ?, ?, 'Pendiente', ?, ?, 'Efectivo', ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iissssd', $employeeId, $idMesa, $idSucursal, $notas, $horaRecogida, $tipoPedido, $total);
    if (!$stmt->execute()) {
        throw new Exception('Error en la inserción del pedido.');
    }
    $idPedido = $stmt->insert_id;
    $stmt->close();

    $subtotal = 0.00;
    $ivaRate = 0.20;

    $productos = $_POST['productos'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];

    foreach ($productos as $productId) {
        $cantidad = $cantidades[$productId] ?? 0;

        $sql = "SELECT precio FROM producto WHERE idProducto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $productId);
        if (!$stmt->execute()) {
            throw new Exception('Error al ejecutar la consulta para obtener precio del producto.');
        }
        $stmt->bind_result($precio);
        $stmt->fetch();
        $stmt->close();

        $subtotal += $precio * $cantidad;

        $sql = "INSERT INTO pedidodetalle (idPedido, idProducto, cantidad, precio) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiid', $idPedido, $productId, $cantidad, $precio);
        if (!$stmt->execute()) {
            throw new Exception('Error al insertar detalles del pedido.');
        }
        $stmt->close();
    }

    $tax = $subtotal * $ivaRate;
    $total = $subtotal + $tax;

    // Actualizar el total del pedido
    $sql = "UPDATE pedido SET total = ? WHERE idPedido = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('di', $total, $idPedido);
    if (!$stmt->execute()) {
        throw new Exception('Error al actualizar el total del pedido.');
    }
    $stmt->close();

    $conn->close();

    $response['success'] = true;
    $response['message'] = 'Pedido creado exitosamente.';
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
