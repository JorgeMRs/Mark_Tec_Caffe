<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

include '../../src/db/db_connect.php';

$response = ['success' => false];

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
        $response['error'] = 'Todos los campos son obligatorios.';
        echo json_encode($response);
        exit;
    }

    if (!empty($contrasena) && $contrasena !== $confirmarContrasena) {
        $response['error'] = 'Las contraseñas no coinciden.';
        echo json_encode($response);
        exit;
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
        $response['success'] = true;
        $response['message'] = 'Empleado actualizado correctamente.';
        $response['id'] = $idEmpleado;
        $response['campoModificado'] = 'nombre'; // Cambia esto según el campo que se haya modificado
        $response['valorModificado'] = $nombre; // Cambia esto según el valor que se haya modificado
    } else {
        $response['error'] = 'Error al actualizar el empleado.';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['error'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);
?>