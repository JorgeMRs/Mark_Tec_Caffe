<?php
include '../../src/db/db_connect.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idReserva = intval($_POST['idReserva']);
    $fechaReserva = $_POST['fechaReserva'];
    $idCliente = intval($_POST['idCliente']);
    $idMesa = intval($_POST['idMesa']);
    $estado = $_POST['estado'];
    $idEmpleado = intval($_POST['idEmpleado']);

    // Establecer la conexión a la base de datos
    $conn = getDbConnection();

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
        $response['success'] = true;
        $response['message'] = 'Reserva actualizada correctamente.';
        $response['id'] = $idReserva;
        $response['camposModificados'] = [
            'fecha' => $fechaReserva,
            'cliente' => $idCliente, // Devolver el ID del cliente
            'mesa' => $idMesa,
            'estado' => $estado,
            'empleado' => $idEmpleado // Devolver el ID del empleado
        ];
    } else {
        $response['error'] = 'Error al actualizar la reserva.';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['error'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);
?>