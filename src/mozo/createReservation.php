<?php
include '../../src/db/db_connect.php';
require '../../vendor/autoload.php';
require '../../src/auth/verifyToken.php';

$response = checkToken();
$employee_id = $response['idEmpleado'];  // ID del empleado autenticado

function generarCodigoReservaUnico($conn)
{
    do {
        // Genera un código único basado en un hash
        $codigoReserva = substr(md5(uniqid(rand(), true)), 0, 10);

        // Verificar si el código ya existe en la base de datos
        $sqlVerificarCodigo = "SELECT COUNT(*) FROM reserva WHERE codigoReserva = ?";
        $stmtVerificarCodigo = $conn->prepare($sqlVerificarCodigo);
        $stmtVerificarCodigo->bind_param("s", $codigoReserva);
        $stmtVerificarCodigo->execute();
        $resultado = $stmtVerificarCodigo->get_result()->fetch_row()[0];
    } while ($resultado > 0); // Repetir hasta que el código sea único

    return $codigoReserva;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mesa_id = $_POST['mesa_id'] ?? null;
    $sucursal_id = $_POST['sucursal_id'] ?? null;
    $fechaReserva = $_POST['fechaReserva'] ?? null;
    $horaReserva = $_POST['horaReserva'] ?? null;
    $cantidadPersonas = $_POST['cantidadPersonas'] ?? null;

    if (!$mesa_id || !$sucursal_id || !$fechaReserva || !$cantidadPersonas || !$employee_id) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos para realizar la reserva.']);
        exit;
    }

    try {
        // Conexión a la base de datos
        $conn = getDbConnection();

        // Combina la fecha y la hora en un solo campo de tipo datetime
        $fechaCompleta = $fechaReserva . ' ' . $horaReserva;

        // Generar un código de reserva único
        $codigoReserva = generarCodigoReservaUnico($conn);

        // Insertar la reserva en la base de datos
        $sql = "INSERT INTO reserva (idMesa, idEmpleado, fechaReserva, cantidadPersonas, estado, codigoReserva) 
        VALUES (?, ?, ?, ?, 'reservado', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $mesa_id, $employee_id, $fechaCompleta, $cantidadPersonas, $codigoReserva);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al realizar la reserva.']);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
