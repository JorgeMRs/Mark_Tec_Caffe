<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../src/db/db_connect.php';

try {
    $conn = getDbConnection();
} catch (Exception $e) {
    die(json_encode(['error' => $e->getMessage()]));
}

// Ajustamos la consulta SQL para que incluya el JOIN con las tablas 'puesto' y 'sucursal'
$query = "
    SELECT e.idEmpleado, e.correo, e.contrasena, e.nombre, e.apellido, e.ci, 
           e.idPuesto, e.idSucursal, e.fechaIngreso, e.tel, e.fechaNacimiento, 
           p.nombre AS nombrePuesto, p.salario, s.nombre AS nombreSucursal
    FROM empleado e
    JOIN puesto p ON e.idPuesto = p.idPuesto
    JOIN sucursal s ON e.idSucursal = s.idSucursal
";

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
