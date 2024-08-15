<?php
header('Content-Type: application/json');
$response = array();

require_once './db_connect.php';

try {
    // Crear conexión
    $conn = getDbConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener datos del formulario
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['passwordConfirm'] ?? '';

        // Validar los datos
        if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($passwordConfirm)) {
            throw new Exception('Todos los campos son obligatorios.');
        }

        if ($password !== $passwordConfirm) {
            throw new Exception('Las contraseñas no coinciden.');
        }

        if (strlen($password) < 8) {
            throw new Exception('La contraseña debe tener al menos 8 caracteres.');
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
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertar en la base de datos
        $sql = "INSERT INTO cliente (correo, contrasena, nombre, apellido) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $email, $hashedPassword, $nombre, $apellido);

            if ($stmt->execute()) {
                session_start(); // Iniciar sesión
                $_SESSION['user_id'] = $stmt->insert_id; // Guardar el ID del usuario en la sesión
                $response['status'] = 'success';
                $response['message'] = 'Datos insertados correctamente';
                $response['redirect'] = '/index.php'; // Redirección a la página de inicio
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
