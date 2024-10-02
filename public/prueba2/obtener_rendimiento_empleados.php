<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener el rendimiento de empleados
$queryRendimientoEmpleados = "
    SELECT 
        empleado.nombre AS empleado, 
        SUM(pedido.total) AS ventas
    FROM 
        pedido
    JOIN 
        empleado ON pedido.idEmpleado = empleado.idEmpleado
    GROUP BY 
        empleado.nombre
    ORDER BY 
        ventas DESC
    LIMIT 5
";

$resultRendimientoEmpleados = $conn->query($queryRendimientoEmpleados);

$rendimientoEmpleados = [];
while ($row = $resultRendimientoEmpleados->fetch_assoc()) {
    $rendimientoEmpleados[] = $row;
}

echo json_encode($rendimientoEmpleados);
$conn->close();
?>