<?php
session_start();
require_once './db_connect.php';

$response = ['status' => 'error', 'message' => ''];

try {
    // Crear conexión
    $conn = getDbConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $rating = $data['rating'] ?? '';
        $comment = $data['comment'] ?? '';

        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            throw new Exception('No estás autenticado.');
        }

        if (empty($rating) || empty($comment)) {
            throw new Exception('Todos los campos son obligatorios.');
        }

        if ($rating < 1 || $rating > 5) {
            throw new Exception('Calificación no válida.');
        }

        $userId = $_SESSION['user_id'];

        // Convertir el rating en un formato adecuado para la base de datos
        $ratingLevels = ['Muy bajo', 'Bajo', 'Medio', 'Alto', 'Muy alto'];
        $ratingLevel = $ratingLevels[$rating - 1];

        $stmt = $conn->prepare('INSERT INTO retroalimentacion (idCliente, nivelSatisfaccion, comentario) VALUES (?, ?, ?)');
        if ($stmt) {
            $stmt->bind_param('iss', $userId, $ratingLevel, $comment);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Retroalimentación enviada correctamente.';
            } else {
                throw new Exception('Error al enviar la retroalimentación: ' . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception('Error al preparar la consulta: ' . $conn->error);
        }

        $conn->close();
    } else {
        throw new Exception('Método de solicitud no permitido.');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>