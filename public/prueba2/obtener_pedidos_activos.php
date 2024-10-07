
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

$query = "SELECT p.idPedido, p.fechaPedido, c.nombre AS clienteNombre, e.nombre AS empleadoNombre, p.total, p.estado 
        FROM pedido p 
        JOIN cliente c ON p.idCliente = c.idCliente 
        LEFT JOIN empleado e ON p.idEmpleado = e.idEmpleado";

// Consulta para obtener los pedidos
$result = $conn->query($query);

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

echo json_encode($pedidos);
$conn->close();
?>