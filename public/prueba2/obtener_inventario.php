<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener el inventario
$query = "
    SELECT 
        p.idProducto AS id,
        p.nombre AS item,
        p.stock AS quantity,
        p.precio AS price
    FROM 
        producto p
    ORDER BY 
        p.idProducto ASC
";

$result = $conn->query($query);

$inventario = [];
while ($row = $result->fetch_assoc()) {
    $inventario[] = $row;
}

echo json_encode($inventario);
$conn->close();
?>