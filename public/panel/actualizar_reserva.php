<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Verificar si se han proporcionado todos los datos necesarios
if (!isset($_POST['idReserva']) || !isset($_POST['fechaReserva']) || !isset($_POST['idCliente']) || !isset($_POST['idMesa']) || !isset($_POST['estado']) || !isset($_POST['idEmpleado'])) {
    die('Error: Datos incompletos');
}

$idReserva = intval($_POST['idReserva']);
$fechaReserva = $_POST['fechaReserva'];
$idCliente = intval($_POST['idCliente']);
$idMesa = intval($_POST['idMesa']);
$estado = $_POST['estado'];
$idEmpleado = intval($_POST['idEmpleado']);

// Consulta para actualizar la reserva
$query = "
    UPDATE reserva
    SET 
        fechaReserva = ?,
        idCliente = ?,
        idMesa = ?,
        estado = ?,
        idEmpleado = ?
    WHERE 
        idReserva = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('siissi', $fechaReserva, $idCliente, $idMesa, $estado, $idEmpleado, $idReserva);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Inventario actualizado correctamente.']);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>