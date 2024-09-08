<?php
require_once '../../vendor/autoload.php'; // Ajusta la ruta según la ubicación de tu archivo
require_once '../db/db_connect.php'; // Incluir el archivo de conexión a la base de datos

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../../');
$dotenv->load();

session_start();

function generarCodigoReservaUnico($conn) {
    do {
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
$response = array('success' => false, 'message' => '');
try {
    // Obtener los parámetros de la solicitud
    $fechaReserva = $_POST['fechaReserva'];
    $horaReserva = $_POST['horaReserva'];
    $mesaId = isset($_POST['mesa_id']) ? intval($_POST['mesa_id']) : 0;
    $sucursalId = isset($_POST['sucursal_id']) ? intval($_POST['sucursal_id']) : 0;
    $cantidadPersonas = isset($_POST['cantidadPersonas']) ? intval($_POST['cantidadPersonas']) : 0;
    $userId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

    if ($mesaId <= 0 || $sucursalId <= 0 || $cantidadPersonas <= 0 || empty($fechaReserva) || empty($horaReserva)) {
        throw new Exception('Datos de reserva no válidos.');
    }

    // Combinar fecha y hora en un solo valor DATETIME
    $fechaHoraReserva = $fechaReserva . ' ' . $horaReserva;

    // Obtener la conexión a la base de datos
    $conn = getDbConnection();

    // Verificar disponibilidad de la mesa
    $sqlVerificar = "SELECT estado FROM reserva 
                     WHERE idMesa = ? AND DATE(fechaReserva) = ? 
                     AND estado IN ('reservado', 'ocupado')";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bind_param("is", $mesaId, $fechaReserva);
    $stmtVerificar->execute();
    $resultado = $stmtVerificar->get_result()->fetch_assoc();

    if ($resultado) {
        throw new Exception('La mesa no está disponible para la fecha especificada.');
    }

    // Verificar capacidad
    $sqlCapacidad = "SELECT capacidad FROM mesa WHERE idMesa = ?";
    $stmtCapacidad = $conn->prepare($sqlCapacidad);
    $stmtCapacidad->bind_param("i", $mesaId);
    $stmtCapacidad->execute();
    $capacidad = $stmtCapacidad->get_result()->fetch_assoc()['capacidad'];

    if ($cantidadPersonas > $capacidad) {
        throw new Exception('La cantidad de personas excede la capacidad de la mesa.');
    }

    // Generar código de reserva único
    $codigoReserva = generarCodigoReservaUnico($conn);

    // Registrar la reserva
    $sqlReservar = "INSERT INTO reserva (idMesa, fechaReserva, estado, idCliente, cantidadPersonas, codigoReserva) 
                    VALUES (?, ?, 'reservado', ?, ?, ?)";
    $stmtReservar = $conn->prepare($sqlReservar);
    $stmtReservar->bind_param("issis", $mesaId, $fechaHoraReserva, $userId, $cantidadPersonas, $codigoReserva);
    $stmtReservar->execute();

    // Redirigir a la página de confirmación
    $response['success'] = true;
    $response['codigoReserva'] = $codigoReserva;
} catch (Exception $e) {
    // En caso de error, enviar un mensaje adecuado
    $response['message'] = $e->getMessage();
}

// Devolver respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>
