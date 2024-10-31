<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener el historial de pedidos con estados "Completado" y "Cancelado"
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
    WHERE 
        p.estado IN ('Completado', 'Cancelado') -- Filtrar solo los estados deseados
    ORDER BY 
        p.fechaPedido DESC
";

$result = $conn->query($query);

// Inicializar un array para almacenar el historial de pedidos
$historialPedidos = [];
while ($row = $result->fetch_assoc()) {
    // Reemplazar valores NULL con "Sin datos"
    $row['customer'] = $row['customer'] ?? 'Sin datos';
    $row['total'] = $row['total'] ?? 'Sin datos'; // Solo si el total puede ser NULL
    $row['estado'] = $row['estado'] ?? 'Sin datos'; // Solo si el estado puede ser NULL

    $historialPedidos[] = $row; // Agregar cada fila al historial de pedidos
}

// Devolver el historial en formato JSON
echo json_encode($historialPedidos);
$conn->close();
?>
