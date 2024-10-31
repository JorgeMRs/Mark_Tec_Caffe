<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Verificar si se han proporcionado todos los datos necesarios
if (!isset($_POST['idCategoria']) || !isset($_POST['nombre'])) {
    die('Error: Datos incompletos');
}

$idCategoria = intval($_POST['idCategoria']);
$nombre = $_POST['nombre'];

// Consulta para actualizar la categoría
$query = "
    UPDATE categoria
    SET 
        nombre = ?
    WHERE 
        idCategoria = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('si', $nombre, $idCategoria);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Categoría actualizada correctamente.',
        'id' => $idCategoria,
        'campoModificado' => 'nombre', // Cambia esto según el campo que se haya modificado
        'valorModificado' => $nombre // Cambia esto según el valor que se haya modificado
    ]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>