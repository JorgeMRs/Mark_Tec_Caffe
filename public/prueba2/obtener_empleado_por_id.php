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

$query = "SELECT idEmpleado, correo, nombre, apellido, ci, idPuesto, idSucursal, fechaIngreso, salario, tel, fechaNacimiento FROM empleado WHERE idEmpleado = ?";
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