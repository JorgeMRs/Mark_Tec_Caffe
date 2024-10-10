<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que todos los campos necesarios estén presentes
    if (!isset($_POST['idPedido'], $_POST['fechaPedido'], $_POST['idCliente'], $_POST['idEmpleado'], $_POST['total'], $_POST['estado'])) {
        echo json_encode(['success' => false, 'error' => 'Faltan datos necesarios']);
        exit;
    }

    // Obtener y validar los datos del formulario
    $idPedido = intval($_POST['idPedido']);
    $fechaPedido = $_POST['fechaPedido'];
    $idCliente = intval($_POST['idCliente']);
    $idEmpleado = intval($_POST['idEmpleado']);
    $total = floatval($_POST['total']);
    $estado = $_POST['estado'];

    // Validar que los campos no estén vacíos
    if (empty($idPedido) || empty($fechaPedido) || empty($idCliente) || empty($idEmpleado) || empty($total) || empty($estado)) {
        echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Conectar a la base de datos
    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Error al conectar a la base de datos: ' . $e->getMessage()]);
        exit;
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

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>