<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $idPedido = isset($_POST['idPedido']) ? intval($_POST['idPedido']) : null;
    $fechaPedido = isset($_POST['fechaPedido']) ? $_POST['fechaPedido'] : null;
    $idCliente = isset($_POST['idClientePedido']) ? intval($_POST['idClientePedido']) : null;
    $idEmpleado = isset($_POST['idEmpleadoPedido']) ? intval($_POST['idEmpleadoPedido']) : null;
    $total = isset($_POST['totalPedido']) ? floatval($_POST['totalPedido']) : null;
    $estado = isset($_POST['estadoPedido']) ? $_POST['estadoPedido'] : null;
    $metodoPago = isset($_POST['metodoPago']) ? $_POST['metodoPago'] : null;

    // Mensajes de depuración
    error_log("idPedido: " . $idPedido);
    error_log("fechaPedido: " . $fechaPedido);
    error_log("idCliente: " . $idCliente);
    error_log("idEmpleado: " . $idEmpleado);
    error_log("total: " . $total);
    error_log("estado: " . $estado);
    error_log("metodoPago: " . $metodoPago);

    // Validar los datos recibidos
    if (empty($idPedido) || empty($fechaPedido) || empty($idCliente) || empty($idEmpleado) || $total < 0 || empty($estado) || empty($metodoPago)) {
        $response['error'] = 'Faltan datos necesarios';
        echo json_encode($response);
        exit;
    }

    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        $response['error'] = 'Error al conectar a la base de datos: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }

    // Actualizar los datos del pedido en la base de datos
    $query = "UPDATE pedido SET fechaPedido = ?, idCliente = ?, idEmpleado = ?, total = ?, estado = ?, metodoPago = ? WHERE idPedido = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('siiissi', $fechaPedido, $idCliente, $idEmpleado, $total, $estado, $metodoPago, $idPedido);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Pedido actualizado correctamente.';
        $response['id'] = $idPedido;
        $response['camposModificados'] = [
            'fechaPedido' => $fechaPedido,
            'idCliente' => $idCliente,
            'idEmpleado' => $idEmpleado,
            'total' => $total,
            'estado' => $estado,
            'metodoPago' => $metodoPago
        ];
    } else {
        $response['error'] = 'Error al actualizar el pedido.';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['error'] = 'Método no permitido';
}

echo json_encode($response);
?>