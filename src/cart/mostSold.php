<?php
require_once '../db/db_connect.php';
require_once '../../vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta
use Stichoza\GoogleTranslate\GoogleTranslate;

header("Content-Type: application/json");

try {
    // Obtener la conexión a la base de datos
    $conn = getDbConnection();

    // Obtener el idioma de la sesión o por defecto "es" (español)
    session_start();
    $idioma = isset($_SESSION['idioma']) ? $_SESSION['idioma'] : 'es';

    // Consulta para obtener los 4 productos de café más vendidos
    $query = "
        SELECT p.idProducto, p.nombre, p.imagen, p.precio, SUM(pd.cantidad) AS total_vendido
        FROM producto p
        JOIN pedidodetalle pd ON p.idProducto = pd.idProducto
        JOIN pedido pe ON pd.idPedido = pe.idPedido
        JOIN categoria c ON p.idCategoria = c.idCategoria
        WHERE pe.estado = 'Completado' AND c.nombre LIKE 'Café%'
        GROUP BY p.idProducto
        ORDER BY total_vendido DESC
        LIMIT 4;
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $topProducts = [];
    while ($row = $result->fetch_assoc()) {
        // Traducir el nombre del producto solo si el idioma no es español
        if ($idioma !== 'es') {
            $translatedName = GoogleTranslate::trans($row['nombre'], $idioma);
        } else {
            $translatedName = $row['nombre']; // Mantener el nombre original
        }

        $topProducts[] = [
            'idProducto' => $row['idProducto'],
            'nombre' => $translatedName, // Nombre traducido o original
            'imagen' => $row['imagen'],
            'precio' => $row['precio'],
            'total_vendido' => $row['total_vendido']
        ];
    }

    echo json_encode([
        'status' => 'success',
        'data' => $topProducts
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
