<?php
include '../../src/db/db_connect.php';
require '../../vendor/autoload.php';
require '../../src/auth/verifyToken.php';

$response = checkToken();
$employee_id = $response['idEmpleado']; // ID del empleado que cancela la reserva

// Obtener los datos de la petición
$data = json_decode(file_get_contents('php://input'), true);
$idReserva = $data['idReserva'] ?? null;
$notas = $data['notes'] ?? null; // Obtener las notas de la petición

// Validar que se ha proporcionado el ID de reserva y las notas
if (!$idReserva) {
    echo json_encode(['message' => 'ID de reserva no proporcionado.']);
    exit();
}

if (!$notas) {
    echo json_encode(['message' => 'Las notas son obligatorias.']);
    exit();
}

try {
    $conn = getDbConnection();

    // Cambiar el estado de la reserva a "cancelado"
    $sql = "UPDATE reserva SET estado = 'cancelado' WHERE idReserva = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idReserva);
    if (!$stmt->execute()) {
        throw new Exception('Error al cancelar la reserva: ' . $stmt->error);
    }

    // Guardar la cancelación en la tabla cancelacionreserva
    $sqlCancelacion = "INSERT INTO cancelacionreserva (idReserva, idEmpleado, notas, tipoCancelacion)
                        VALUES (?, ?, ?, 'Empleado')";
    $stmtCancelacion = $conn->prepare($sqlCancelacion);
    $stmtCancelacion->bind_param("iis", $idReserva, $employee_id, $notas); // Usar las notas proporcionadas
    if (!$stmtCancelacion->execute()) {
        throw new Exception('Error al registrar la cancelación: ' . $stmtCancelacion->error);
    }

    echo json_encode(['message' => 'Reserva cancelada con éxito.']);
} catch (Exception $e) {
    echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
}
?>
