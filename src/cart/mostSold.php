<?php
require_once '../db/db_connect.php';

header("Content-Type: application/json");

try {
    // Obtener la conexión a la base de datos
    $conn = getDbConnection();

    // Consulta para obtener los 4 productos más vendidos
    $query = "
        SELECT p.idProducto, p.nombre, p.imagen, p.precio, SUM(pd.cantidad) AS total_vendido
        FROM producto p
        JOIN pedidodetalle pd ON p.idProducto = pd.idProducto
        JOIN pedido pe ON pd.idPedido = pe.idPedido
        WHERE pe.estado = 'Completado'
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
        $topProducts[] = [
            'idProducto' => $row['idProducto'],
            'nombre' => $row['nombre'],
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
