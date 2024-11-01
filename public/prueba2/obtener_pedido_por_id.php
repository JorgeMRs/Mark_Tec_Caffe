<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $idPedido = intval($_GET['id']);

    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        $response['error'] = 'Error al conectar a la base de datos: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }

    $query = "SELECT p.idPedido, p.fechaPedido, c.idCliente, c.nombre AS clienteNombre, c.apellido AS clienteApellido, e.idEmpleado, p.total, p.estado, p.metodoPago 
              FROM pedido p 
              JOIN cliente c ON p.idCliente = c.idCliente 
              LEFT JOIN empleado e ON p.idEmpleado = e.idEmpleado 
              WHERE p.idPedido = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $idPedido);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response = $result->fetch_assoc();
        $response['success'] = true;
    } else {
        $response['error'] = 'Pedido no encontrado';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['error'] = 'Método no permitido o ID no proporcionado';
}

echo json_encode($response);
?>