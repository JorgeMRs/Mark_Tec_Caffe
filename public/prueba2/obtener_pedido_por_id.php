<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

if (!isset($_GET['id'])) {
    die('Error: ID de pedido no especificado.');
}

$idPedido = intval($_GET['id']);

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

$query = "SELECT p.idPedido, p.fechaPedido, p.idCliente, p.idEmpleado, p.total, p.estado 
          FROM pedido p 
          WHERE p.idPedido = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $idPedido);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $pedido = $result->fetch_assoc();
    echo json_encode($pedido);
} else {
    echo json_encode(['error' => 'Pedido no encontrado']);
}

$stmt->close();
$conn->close();
?>