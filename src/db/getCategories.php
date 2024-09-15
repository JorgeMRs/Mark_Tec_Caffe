<?php
include 'db_connect.php';

try {
    $conn = getDbConnection();

    // Consulta para obtener las categorías, incluyendo la columna imagen
    $sql = "SELECT idCategoria, nombre, imagen FROM categoria";
    $result = $conn->query($sql);

    $categorias = [];
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
    
    // Devolver las categorías en formato JSON
    echo json_encode($categorias);

    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
