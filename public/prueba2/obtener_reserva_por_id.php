<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Verificar si se ha proporcionado un ID de reserva
if (!isset($_GET['id'])) {
    die('Error: ID de reserva no proporcionado');
}

$idReserva = intval($_GET['id']);

// Consulta para obtener la reserva por ID
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
    WHERE 
        r.idReserva = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $idReserva);
$stmt->execute();
$result = $stmt->get_result();

$reserva = $result->fetch_assoc();

if ($reserva) {
    echo json_encode($reserva);
} else {
    echo json_encode(['error' => 'Reserva no encontrada']);
}

$stmt->close();
$conn->close();
?>