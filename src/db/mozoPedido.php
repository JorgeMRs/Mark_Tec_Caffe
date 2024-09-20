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
    if ($tipoPedido === 'Para llevar' && $horaRecogida) {
        // Validar que el formato sea HH:MM
        if (!preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $horaRecogida)) {
            $response['message'] = 'Formato de hora de recogida inválido. Debe ser HH:MM.';
            echo json_encode($response);
            error_log($horaRecogida);
            exit;
        }
    }
    $idMesa = $_POST['idMesa'] ?? null;  // Renombrado de numeroMesa a idMesa
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

    // Verificar y obtener el idMesa si es necesario
    if ($tipoPedido === 'En el local') {
        if (!$idMesa) {
            $response['message'] = 'Número de mesa requerido para pedidos en el local.';
            echo json_encode($response);
            exit;
        }
        // Validar que el idMesa es válido para la sucursal
        $sql = "SELECT idMesa FROM mesa WHERE idMesa = ? AND idSucursal = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $idMesa, $idSucursal);
        if (!$stmt->execute()) {
            throw new Exception('Error al verificar idMesa.');
        }
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            $response['message'] = 'Número de mesa inválido para esta sucursal.';
            echo json_encode($response);
            exit;
        }
        $stmt->close();
    } else {
        $idMesa = null; // No se requiere idMesa para pedidos para llevar
    }

    // Comenzar la transacción
    $conn->begin_transaction();

    // Obtener el siguiente número de pedido para la sucursal
    $sql = "SELECT numeroPedido FROM numeropedidosucursal WHERE idSucursal = ? FOR UPDATE";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idSucursal);
    if (!$stmt->execute()) {
        throw new Exception('Error al obtener el número de pedido de la sucursal.');
    }
    $stmt->bind_result($numeroPedidoSucursal);
    $stmt->fetch();
    $stmt->close();

    if ($numeroPedidoSucursal === null) {
        // Si no existe un registro para la sucursal, inicializar en 1
        $numeroPedidoSucursal = 1;
        $sql = "INSERT INTO numeropedidosucursal (idSucursal, numeroPedido) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $idSucursal, $numeroPedidoSucursal);
        if (!$stmt->execute()) {
            throw new Exception('Error al insertar el número de pedido inicial.');
        }
    } else {
        // Incrementar el número de pedido
        $numeroPedidoSucursal++;
        $sql = "UPDATE numeropedidosucursal SET numeroPedido = ? WHERE idSucursal = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $numeroPedidoSucursal, $idSucursal);
        if (!$stmt->execute()) {
            throw new Exception('Error al actualizar el número de pedido.');
        }
    }

    // Insertar el pedido en la tabla pedido
    $sql = "INSERT INTO pedido (idEmpleado, idCliente, idCarrito, idMesa, idSucursal, estado, notas, horaRecogida, metodoPago, tipoPedido, total, numeroPedidoSucursal) 
    VALUES (?, NULL, NULL, ?, ?, 'Pendiente', ?, ?, 'Efectivo', ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iissssdi', $employeeId, $idMesa, $idSucursal, $notas, $horaRecogida, $tipoPedido, $total, $numeroPedidoSucursal);

    if (!$stmt->execute()) {
        throw new Exception('Error en la inserción del pedido.');
    }
    $idPedido = $stmt->insert_id; // Obtén el ID del pedido insertado
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

    // Calcula el total y actualiza el pedido
    $tax = $subtotal * $ivaRate;
    $total = $subtotal + $tax;

    // Actualizar el total del pedido
    $sql = "UPDATE pedido SET total = ? WHERE idPedido = ? AND idSucursal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('dii', $total, $idPedido, $idSucursal);
    if (!$stmt->execute()) {
        throw new Exception('Error al actualizar el total del pedido.');
    }
    $stmt->close();

    $conn->commit();
    $conn->close();

    $response['success'] = true;
    $response['message'] = 'Pedido creado exitosamente.';
} catch (Exception $e) {
    $conn->rollback();
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
