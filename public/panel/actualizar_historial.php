<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $idHistorial = isset($_POST['idHistorial']) ? intval($_POST['idHistorial']) : null;
    $fecha = isset($_POST['fechaHistorial']) ? $_POST['fechaHistorial'] : null;
    $clienteNombre = isset($_POST['clienteNombreHistorial']) ? $_POST['clienteNombreHistorial'] : null;
    $total = isset($_POST['totalHistorial']) ? floatval($_POST['totalHistorial']) : null;
    $estado = isset($_POST['estadoHistorial']) ? $_POST['estadoHistorial'] : null;

    // Mensajes de depuración
    error_log("idHistorial: " . $idHistorial);
    error_log("fecha: " . $fecha);
    error_log("clienteNombre: " . $clienteNombre);
    error_log("total: " . $total);
    error_log("estado: " . $estado);

    // Validar los datos recibidos
    if (empty($idHistorial) || empty($fecha) || empty($clienteNombre) || $total < 0 || empty($estado)) {
        $response['error'] = 'Faltan datos necesarios';
        echo json_encode($response);
        exit;
    }

    try {
        $conn = getDbConnection();
    } catch (Exception $e) {
        $response['error'] = 'Error al conectar a la base de datos: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }

    // Actualizar los datos en la base de datos
    $query = "UPDATE pedido SET fechaPedido = ?, total = ?, estado = ? WHERE idPedido = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sdsi', $fecha, $total, $estado, $idHistorial);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Historial actualizado correctamente.';
        $response['id'] = $idHistorial;
        $response['campoModificado'] = 'estado'; // Cambia esto según el campo que se haya modificado
        $response['valorModificado'] = $estado; // Cambia esto según el valor que se haya modificado
    } else {
        $response['error'] = 'Error al actualizar el historial.';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['error'] = 'Método no permitido';
}

echo json_encode($response);
?>