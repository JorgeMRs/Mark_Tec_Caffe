<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Modificamos la consulta para filtrar los pedidos activos
$query = "SELECT p.idPedido, p.fechaPedido, c.nombre AS clienteNombre, e.nombre AS empleadoNombre, p.total, p.estado 
        FROM pedido p 
        JOIN cliente c ON p.idCliente = c.idCliente 
        LEFT JOIN empleado e ON p.idEmpleado = e.idEmpleado 
        WHERE p.estado IN ('Pendiente', 'En Preparación', 'Listo para Recoger')";

// Consulta para obtener los pedidos
$result = $conn->query($query);

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    // Reemplazar valores NULL con "Sin datos"
    $row['clienteNombre'] = $row['clienteNombre'] ?? 'Sin datos';
    $row['empleadoNombre'] = $row['empleadoNombre'] ?? 'Sin datos';
    $row['total'] = $row['total'] ?? 'Sin datos'; // Si el total podría ser NULL, si no, omite esto

    $pedidos[] = $row;
}

echo json_encode($pedidos);
$conn->close();
?>
