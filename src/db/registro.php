<?php
header('Content-Type: application/json');
$response = array();

require_once './db_connect.php';

try {
    $conn = getDbConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener datos del formulario
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['passwordConfirm'] ?? '';

        // Validar los datos
        if (empty($email) || empty($password) || empty($passwordConfirm)) {
            throw new Exception('Todos los campos son obligatorios.');
        }

        if ($password !== $passwordConfirm) {
            throw new Exception('Las contraseñas no coinciden.');
        }

        // Verificar si el correo ya está registrado
        $sql = "SELECT COUNT(*) as count FROM cliente WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row['count'] > 0) {
                throw new Exception('Correo ya registrado.');
            }
            $stmt->close();
        } else {
            throw new Exception('Error al preparar la consulta: ' . $conn->error);
        }

        // Encriptar contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertar en la base de datos
        $sql = "INSERT INTO cliente (correo, contrasena) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $email, $hashedPassword);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Datos insertados correctamente';
            } else {
                throw new Exception('Error al insertar datos: ' . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception('Error al preparar la consulta: ' . $conn->error);
        }

        $conn->close();
    } else {
        throw new Exception('Método de solicitud no permitido.');
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>