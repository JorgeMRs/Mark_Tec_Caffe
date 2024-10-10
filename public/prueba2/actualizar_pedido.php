<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPedido = intval($_POST['idPedido']);
    $fechaPedido = $_POST['fechaPedido'];
    $idCliente = isset($_POST['idCliente']) ? intval($_POST['idCliente']) : null;
    $idEmpleado = isset($_POST['idEmpleado']) ? intval($_POST['idEmpleado']) : null;
    $total = floatval($_POST['total']);
    $estado = $_POST['estado'];

    if ($idCliente === null || $idEmpleado === null) {
        die(json_encode(['success' => false, 'error' => 'ID de cliente o empleado no definido']));
    }

    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }

    // Actualizar los datos del pedido en la base de datos
    $query = "UPDATE pedido SET fechaPedido = ?, idCliente = ?, idEmpleado = ?, total = ?, estado = ? WHERE idPedido = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('siiisi', $fechaPedido, $idCliente, $idEmpleado, $total, $estado, $idPedido);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>