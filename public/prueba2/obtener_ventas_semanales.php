<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener las ventas diarias de la semana actual
$queryVentasSemanales = "
    SELECT 
        WEEK(fechaPedido, 1) - WEEK(DATE_SUB(fechaPedido, INTERVAL DAYOFMONTH(fechaPedido) - 1 DAY), 1) + 1 AS semanaMes, 
        SUM(total) AS ventas
    FROM 
        pedido
    WHERE 
        MONTH(fechaPedido) = MONTH(CURDATE()) 
        AND YEAR(fechaPedido) = YEAR(CURDATE())
    GROUP BY 
        semanaMes
    ORDER BY 
        semanaMes
";


$resultVentasSemanales = $conn->query($queryVentasSemanales);

if (!$resultVentasSemanales) {
    die('Error en la consulta: ' . $conn->error);
}

$ventasSemanales = [];
while ($row = $resultVentasSemanales->fetch_assoc()) {
    $ventasSemanales[] = $row;
}

echo json_encode($ventasSemanales);
$conn->close();
?>