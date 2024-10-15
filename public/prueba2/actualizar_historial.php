<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $idHistorial = intval($_POST['idHistorial']);
    $fecha = $_POST['fecha'];
    $clienteNombre = $_POST['clienteNombre'];
    $total = floatval($_POST['total']);
    $estado = $_POST['estado'];

    // Validar los datos recibidos
    if (empty($idHistorial) || empty($fecha) || empty($clienteNombre) || $total < 0 || empty($estado)) {
        $response['error'] = 'Faltan datos necesarios';
        echo json_encode($response);
        exit;
    }

    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }

    // Actualizar los datos en la base de datos
    $query = "UPDATE pedido SET fechaPedido = ?, total = ?, estado = ? WHERE idPedido = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sdsi', $fecha, $total, $estado, $idHistorial);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Historial actualizado correctamente.']);
    } else {
        echo 'Error al actualizar el inventario.';
    }

    $stmt->close();
    $conn->close();
} else {
    die('Error: Método de solicitud no permitido.');
}


?>