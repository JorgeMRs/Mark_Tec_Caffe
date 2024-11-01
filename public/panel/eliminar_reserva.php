<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]));
}

$idReserva = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idReserva <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de reserva inválido']);
    exit;
}

// Prepara la consulta para eliminar la reserva
$query = "DELETE FROM reserva WHERE idReserva = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idReserva);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Reserva eliminada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró la reserva o ya estaba eliminada']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la reserva: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
