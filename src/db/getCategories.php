<?php
include 'db_connect.php';

try {
    $conn = getDbConnection();

    $sql = "SELECT idCategoria, nombre, imagen FROM categoria WHERE estadoActivacion = 1";
    $result = $conn->query($sql);

    $categorias = [];
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
    
    echo json_encode($categorias);

    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
