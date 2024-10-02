
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

$query = "SELECT idEmpleado AS id, nombre AS firstName, apellido AS lastName, correo AS email, tel AS phone FROM empleado";
$result = $conn->query($query);

$empleados = [];
while ($row = $result->fetch_assoc()) {
    $empleados[] = $row;
}

echo json_encode($empleados);
$conn->close();
?>
