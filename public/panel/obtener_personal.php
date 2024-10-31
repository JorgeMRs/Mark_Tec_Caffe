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
// Se eliminan idPuesto e idSucursal y se añaden los nombres correspondientes
$query = "
    SELECT 
        e.idEmpleado, 
        e.correo, 
        e.contrasena, 
        e.nombre, 
        e.apellido, 
        e.ci, 
        p.nombre AS nombrePuesto, 
        s.nombre AS nombreSucursal, 
        e.fechaIngreso, 
        e.tel, 
        e.fechaNacimiento, 
        p.salario
    FROM 
        empleado e
    JOIN 
        puesto p ON e.idPuesto = p.idPuesto
    JOIN 
        sucursal s ON e.idSucursal = s.idSucursal
";

$result = $conn->query($query);

$personal = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $personal[] = $row;
    }
}

// Devolver el resultado en formato JSON
echo json_encode($personal);

$conn->close();
?>
