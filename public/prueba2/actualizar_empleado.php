<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

include '../../src/db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEmpleado = $_POST['idEmpleado'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmarContrasena = $_POST['confirmarContrasena'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $ci = $_POST['ci'] ?? '';
    $idPuesto = $_POST['idPuesto'] ?? '';
    $idSucursal = $_POST['idSucursal'] ?? '';
    $fechaIngreso = $_POST['fechaIngreso'] ?? '';
    $salario = $_POST['salario'] ?? '';
    $tel = $_POST['tel'] ?? '';
    $fechaNacimiento = $_POST['fechaNacimiento'] ?? '';

    if (empty($idEmpleado) || empty($correo) || empty($nombre) || empty($apellido) || empty($ci) || empty($idPuesto) || empty($idSucursal) || empty($fechaIngreso) || empty($salario) || empty($tel) || empty($fechaNacimiento)) {
        die(json_encode(['error' => 'Todos los campos son obligatorios.']));
    }

    if (!empty($contrasena) && $contrasena !== $confirmarContrasena) {
        die(json_encode(['error' => 'Las contraseñas no coinciden.']));
    }

    $conn = getDbConnection();

    $query = "UPDATE empleado SET correo = ?, nombre = ?, apellido = ?, ci = ?, idPuesto = ?, idSucursal = ?, fechaIngreso = ?, salario = ?, tel = ?, fechaNacimiento = ? WHERE idEmpleado = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssiiissssi", $correo, $nombre, $apellido, $ci, $idPuesto, $idSucursal, $fechaIngreso, $salario, $tel, $fechaNacimiento, $idEmpleado);

    if ($stmt->execute()) {
        if (!empty($contrasena)) {
            $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);
            $query = "UPDATE empleado SET contrasena = ? WHERE idEmpleado = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $hashedPassword, $idEmpleado);
            $stmt->execute();
        }
        echo json_encode(['status' => 'success', 'message' => 'Empleado actualizado correctamente.']);
    } else {
        echo json_encode(['error' => 'Error al actualizar el empleado.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Método de solicitud no permitido.']);
}
?>