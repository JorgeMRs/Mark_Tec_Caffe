<?php
header('Content-Type: application/json');
require '../db/db_connect.php';

$response = array('success' => false, 'message' => '');

try {
    date_default_timezone_set('America/Montevideo');

    // Conexión a la base de datos
    $conn = getDbConnection();
    if ($conn->connect_error) {
        throw new Exception("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener la hora actual y agregar 30 minutos
    $now = new DateTime();
    $now->add(new DateInterval('PT30M'));
    $currentFormattedTime = $now->format('H:i');

    // Definir el rango de horas (08:00 a 20:00)
    $start = new DateTime('08:00:00');
    $end = new DateTime('20:00:00');
    $interval = new DateInterval('PT30M');

    // Consulta para obtener los horarios ocupados con exactamente 3 pedidos
    $query = "SELECT horaRecogida FROM pedido GROUP BY horaRecogida HAVING COUNT(*) = 3";
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Error en la consulta: " . $conn->error);
    }
    
    $horasOcupadas = [];
    while ($row = $result->fetch_assoc()) {
        $horasOcupadas[] = $row['horaRecogida'];
    }

    // Inicializar el array para horas disponibles
    $horasDisponibles = [];

    while ($start <= $end) {
        $time24 = $start->format('H:i');

        // Comprobar si la hora está ocupada o si es menor que la hora actual
        if (!in_array($time24, $horasOcupadas) && $start >= $now) {
            $horasDisponibles[] = $time24;
        }

        $start->add($interval);
    }

    // Si se encontraron horas disponibles, se actualiza la respuesta
    if (!empty($horasDisponibles)) {
        $response['success'] = true;
        $response['horasDisponibles'] = $horasDisponibles;
    } else {
        $response['message'] = 'No hay horas disponibles.';
    }

} catch (Exception $e) {
    // Enviar el error como JSON en caso de excepción
    $response['message'] = $e->getMessage();
} finally {
    // Cerrar la conexión si está establecida
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}

// Devolver el JSON con la respuesta
echo json_encode($response);
?>
