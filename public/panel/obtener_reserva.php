<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Consulta para obtener las reservas
$query = "
    SELECT 
        r.idReserva AS id,
        r.fechaReserva AS fecha,
        r.idCliente AS cliente,
        r.idMesa AS mesa,
        r.estado AS estado,
        r.idEmpleado AS empleado
    FROM 
        reserva r
    ORDER BY 
        r.fechaReserva ASC
";

$result = $conn->query($query);

$reservas = [];
while ($row = $result->fetch_assoc()) {
    $reservas[] = $row;
}

echo json_encode($reservas);
$conn->close();
?>