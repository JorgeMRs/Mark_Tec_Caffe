<?php
include '../db/db_connect.php'; // Ajusta la ruta según la ubicación de tu archivo

header('Content-Type: application/json');
session_start();

$response = array('success' => false, 'message' => '');

// Verificar si el ID del pedido está presente en la solicitud
if (!isset($_POST['id'])) {
    $response['message'] = 'ID del pedido no proporcionado.';
    echo json_encode($response);
    exit();
}

// Verificar si las notas están presentes en la solicitud
if (!isset($_POST['notas'])) {
    $response['message'] = 'Notas de cancelación no proporcionadas.';
    echo json_encode($response);
    exit();
}

$orderId = intval($_POST['id']);
$notas = $_POST['notas'];

try {
    // Obtener la conexión a la base de datos
    $conn = getDbConnection();

    // Verificar si el pedido está en estado "Pendiente"
    $query = "SELECT estado FROM pedido WHERE idPedido = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $stmt->bind_result($estado);
    $stmt->fetch();
    $stmt->close();

    if ($estado !== 'Pendiente') {
        $response['message'] = 'El pedido no se encuentra en estado Pendiente y no puede ser cancelado.';
    } else {
        // Obtener el ID del empleado que está haciendo la cancelación
        $employeeId = $_SESSION['employee_id']; // Asegúrate de que la sesión esté iniciada y el ID esté disponible

        // Cancelar el pedido
        $query = "UPDATE pedido SET estado = 'Cancelado', idEmpleado = ? WHERE idPedido = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $employeeId, $orderId);
        if ($stmt->execute()) {
            // Insertar en la tabla cancelacionpedido
            $query = "INSERT INTO cancelacionpedido (idPedido, idEmpleado, notas, tipoCancelacion) VALUES (?, ?, ?, 'Empleado')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iis', $orderId, $employeeId, $notas);
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Pedido cancelado con éxito.';
            } else {
                $response['message'] = 'Error al registrar la cancelación: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $response['message'] = 'Error al cancelar el pedido: ' . $stmt->error;
        }
    }

    $conn->close();
} catch (Exception $e) {
    $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
}

// Enviar la respuesta en formato JSON
echo json_encode($response);
?>
