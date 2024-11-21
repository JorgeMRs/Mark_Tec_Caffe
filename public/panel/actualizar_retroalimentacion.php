<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $idRetroalimentacion = isset($_POST['idRetroalimentacion']) ? intval($_POST['idRetroalimentacion']) : null;
    $idCliente = isset($_POST['idCliente']) ? intval($_POST['idCliente']) : null;
    $nivelSatisfaccion = isset($_POST['nivelSatisfaccion']) ? $_POST['nivelSatisfaccion'] : null;
    $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : null;

    // Mensajes de depuración
    error_log("idRetroalimentacion: " . $idRetroalimentacion);
    error_log("idCliente: " . $idCliente);
    error_log("nivelSatisfaccion: " . $nivelSatisfaccion);
    error_log("comentario: " . $comentario);

    // Validar los datos recibidos
    if (empty($idRetroalimentacion) || empty($idCliente) || empty($nivelSatisfaccion) || empty($comentario)) {
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

    // Actualizar los datos de la retroalimentación en la base de datos
    $query = "UPDATE retroalimentacion SET idCliente = ?, nivelSatisfaccion = ?, comentario = ? WHERE idRetroalimentacion = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('issi', $idCliente, $nivelSatisfaccion, $comentario, $idRetroalimentacion);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Retroalimentación actualizada correctamente.';
        $response['id'] = $idRetroalimentacion;
        $response['camposModificados'] = [
            'idCliente' => $idCliente,
            'nivelSatisfaccion' => $nivelSatisfaccion,
            'comentario' => $comentario
        ];
    } else {
        $response['error'] = 'Error al actualizar la retroalimentación.';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['error'] = 'Método no permitido';
}

echo json_encode($response);
?>