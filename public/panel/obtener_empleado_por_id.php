<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

include '../../src/db/db_connect.php';

$idEmpleado = $_GET['id'] ?? '';

if (empty($idEmpleado)) {
    echo json_encode(['error' => 'ID de empleado no proporcionado']);
    exit;
}

$conn = getDbConnection();

// Hacemos un JOIN para obtener el salario del puesto y el nombre de la sucursal
$query = "
    SELECT 
        e.idEmpleado, 
        e.correo, 
        e.nombre, 
        e.apellido, 
        e.ci, 
        e.idPuesto, 
        e.idSucursal, 
        e.fechaIngreso, 
        p.salario, 
        e.tel, 
        e.fechaNacimiento, 
        e.estadoActivacion 
    FROM 
        empleado e
    JOIN 
        puesto p ON e.idPuesto = p.idPuesto 
    JOIN 
        sucursal s ON e.idSucursal = s.idSucursal
    WHERE 
        e.idEmpleado = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $idEmpleado);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $empleado = $result->fetch_assoc();
    echo json_encode($empleado);
} else {
    echo json_encode(['error' => 'Empleado no encontrado']);
}

$stmt->close();
$conn->close();
?>
