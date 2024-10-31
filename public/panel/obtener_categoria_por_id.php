<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Verificar si se ha proporcionado un ID de categoría
if (!isset($_GET['id'])) {
    die('Error: ID de categoría no proporcionado');
}

$idCategoria = intval($_GET['id']);

// Consulta para obtener la categoría por ID
$query = "
    SELECT 
        c.idCategoria AS idCategoria,
        c.nombre AS nombre
    FROM 
        categoria c
    WHERE 
        c.idCategoria = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $idCategoria);
$stmt->execute();
$result = $stmt->get_result();

$categoria = $result->fetch_assoc();

if ($categoria) {
    echo json_encode($categoria);
} else {
    echo json_encode(['error' => 'Categoría no encontrada']);
}

$stmt->close();
$conn->close();
?>