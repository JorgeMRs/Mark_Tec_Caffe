<?php
session_start();
require '../db/db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = getDbConnection();

// Obtener los datos enviados por POST
$idPedido = isset($_POST['idPedido']) ? intval($_POST['idPedido']) : 0;
$notas = isset($_POST['notas']) ? trim($_POST['notas']) : '';
$idCliente = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; // Suponiendo que el ID del cliente está en la sesión


if ($idPedido && $idCliente) {
    // Iniciar la transacción
    $conn->begin_transaction();

    try {
        // Insertar la cancelación en la base de datos
        $stmt = $conn->prepare("INSERT INTO cancelacionpedido (idPedido, idCliente, notas, tipoCancelacion) VALUES (?, ?, ?, 'Cliente')");
        $stmt->bind_param("iis", $idPedido, $idCliente, $notas);
        $stmt->execute();

        // Actualizar el estado del pedido a 'Cancelado'
        $stmt = $conn->prepare("UPDATE pedido SET estado = 'Cancelado' WHERE idPedido = ?");
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();

        // Confirmar la transacción
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Deshacer la transacción en caso de error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
}
?>