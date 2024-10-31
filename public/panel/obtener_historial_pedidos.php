<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener el historial de pedidos
$query = "
    SELECT 
        p.idPedido AS id,
        p.fechaPedido AS date,
        CONCAT(c.nombre, ' ', c.apellido) AS customer,
        p.total,
        p.estado
    FROM 
        pedido p
    JOIN 
        cliente c ON p.idCliente = c.idCliente
    ORDER BY 
        p.fechaPedido DESC
";

$result = $conn->query($query);

$historialPedidos = [];
while ($row = $result->fetch_assoc()) {
    $historialPedidos[] = $row;
}

echo json_encode($historialPedidos);
$conn->close();
?>