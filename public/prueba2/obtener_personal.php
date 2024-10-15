<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die(json_encode(['error' => $e->getMessage()]));
}

$query = "SELECT idEmpleado, correo, contrasena, nombre, apellido, ci, idPuesto, idSucursal, fechaIngreso, salario, tel, fechaNacimiento FROM empleado";
$result = $conn->query($query);

$personal = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $personal[] = $row;
    }
}

echo json_encode($personal);

$conn->close();
?>