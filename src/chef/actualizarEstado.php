<?php
session_start();

if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'Chef') {
    echo json_encode(['status' => 'error', 'message' => 'Acceso denegado']);
    exit();
}

include '../db/db_connect.php'; // Ajusta la ruta según tu estructura de directorios

// Obtener los datos del formulario
$idPedido = isset($_POST['idPedido']) ? intval($_POST['idPedido']) : 0;
$nuevoEstado = isset($_POST['estado']) ? $_POST['estado'] : '';
$notasCancelacion = isset($_POST['notasCancelacion']) ? $_POST['notasCancelacion'] : '';

if ($idPedido <= 0 || !in_array($nuevoEstado, ['Pendiente', 'En Preparación', 'Listo para Recoger', 'Completado', 'Cancelado'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos del formulario no válidos.']);
    exit();
}

$conn = getDbConnection();
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión a la base de datos: ' . $conn->connect_error]);
    exit();
}

// Consultar el estado actual del pedido
$queryEstadoActual = "SELECT estado FROM pedido WHERE idPedido = ?";
$stmt = $conn->prepare($queryEstadoActual);
$stmt->bind_param('i', $idPedido);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

if (!$pedido) {
    echo json_encode(['status' => 'error', 'message' => 'No se encontró el pedido.']);
    exit();
}

$estadoActual = $pedido['estado'];
$stmt->close();

// Validar la transición del estado
$transicionesPermitidas = [
    'Pendiente' => ['En Preparación'],
    'En Preparación' => ['Listo para Recoger', 'Cancelado'],
    'Listo para Recoger' => ['Completado', 'Cancelado'],
    'Completado' => [],
    'Cancelado' => []
];

if (!in_array($nuevoEstado, $transicionesPermitidas[$estadoActual])) {
    echo json_encode(['status' => 'error', 'message' => 'Transición de estado no permitida.']);
    exit();
}

// Iniciar transacción
$conn->begin_transaction();

try {
    // Actualizar el estado del pedido
    $queryUpdate = "UPDATE pedido SET estado = ?, fechaModificacion = NOW() WHERE idPedido = ?";
    $stmt = $conn->prepare($queryUpdate);
    $stmt->bind_param('si', $nuevoEstado, $idPedido);
    $stmt->execute();
    $stmt->close();

    if ($nuevoEstado === 'Cancelado') {
        // Insertar en la tabla de cancelaciones
        $tipoCancelacion = 'Empleado';
        $idEmpleadoCancelador = $_SESSION['employee_id'];
        $queryCancelacion = "INSERT INTO cancelacionpedido (idPedido, idEmpleado, fechaCancelacion, notas, tipoCancelacion)
        VALUES (?, ?, NOW(), ?, ?)";
        $stmt = $conn->prepare($queryCancelacion);
        $stmt->bind_param('iiss', $idPedido, $idEmpleadoCancelador, $notasCancelacion, $tipoCancelacion);
        $stmt->execute();
        $stmt->close();
    }

    // Preparar la respuesta
    $response = [
        'status' => 'success',
        'message' => $nuevoEstado === 'Cancelado' ? 'El pedido ha sido cancelado con éxito.' : 'Estado actualizado con éxito.',
        'estadosPermitidos' => $transicionesPermitidas[$nuevoEstado],
        'redirect' => $nuevoEstado === 'Cancelado' ? '/public/chef/cocina.php' : ''
    ];

    // Añadir mensaje adicional y redirección si el estado es "Completado"
    if ($nuevoEstado === 'Completado') {
        $response['message'] = 'El pedido ha sido completado con éxito.';
        $response['redirect'] = '/public/chef/cocina.php'; // URL a la que redirigir
    }

    // Confirmar transacción
    $conn->commit();
} catch (Exception $e) {
    // Deshacer transacción en caso de error
    $conn->rollback();
    $response = ['status' => 'error', 'message' => 'Error al actualizar el estado: ' . $e->getMessage()];
}

// Enviar respuesta JSON
echo json_encode($response);

$conn->close();
