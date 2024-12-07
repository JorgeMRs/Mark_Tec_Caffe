<?php
include '../db/db_connect.php';
require '../../vendor/autoload.php';
require '../auth/verifyToken.php';

header('Content-Type: application/json');

$response = checkToken();

$employee_id = $response['idEmpleado']; 
$role = $response['rol'];

$responseCancelOrder = array('success' => false, 'message' => '');

if (!isset($_POST['id'])) {
    $responseCancelOrder['message'] = 'ID del pedido no proporcionado.';
    echo json_encode($responseCancelOrder);
    exit();
}

// Verificar si las notas están presentes en la solicitud
if (!isset($_POST['notas'])) {
    $responseCancelOrder['message'] = 'Notas de cancelación no proporcionadas.';
    echo json_encode($responseCancelOrder);
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
        $responseCancelOrder['message'] = 'El pedido no se encuentra en estado Pendiente y no puede ser cancelado.';
    } else {

        // Cancelar el pedido
        $query = "UPDATE pedido SET estado = 'Cancelado', idEmpleado = ? WHERE idPedido = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $employee_id, $orderId);
        if ($stmt->execute()) {
            // Insertar en la tabla cancelacionpedido
            $query = "INSERT INTO cancelacionpedido (idPedido, idEmpleado, notas, tipoCancelacion) VALUES (?, ?, ?, 'Empleado')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iis', $orderId, $employee_id, $notas);
            if ($stmt->execute()) {
                $responseCancelOrder['success'] = true;
                $responseCancelOrder['message'] = 'Pedido cancelado con éxito.';
            } else {
                $responseCancelOrder['message'] = 'Error al registrar la cancelación: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $responseCancelOrder['message'] = 'Error al cancelar el pedido: ' . $stmt->error;
        }
    }

    $conn->close();
} catch (Exception $e) {
    $responseCancelOrder['message'] = 'Error en la base de datos: ' . $e->getMessage();
}

// Enviar la respuesta en formato JSON
echo json_encode($responseCancelOrder);
?>
