<?php
session_start();
require '../src/db/db_connect.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html'); // Redirigir a la página de inicio de sesión si no está autenticado
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = getDbConnection();

// Inicializa variables
$successMessage = '';
$nombre = '';
$apellido = '';
$telefono = '';
$fechaNacimiento = '';
$correo = '';
$contraseña = '';
$errorMessage = '';

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener datos del formulario
        $correo = $_POST['correo'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $fechaNacimiento = $_POST['fechaNacimiento'] ?? '';
        $contraseña = $_POST['contraseña'] ?? '';

        // Sanitizar y validar datos
        $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);
        $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        $apellido = htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8');
        $telefono = preg_replace('/[^0-9+]/', '', $telefono);
        $fechaNacimiento = htmlspecialchars($fechaNacimiento, ENT_QUOTES, 'UTF-8');
        $contraseña = htmlspecialchars($contraseña, ENT_QUOTES, 'UTF-8');

        // Verificar contraseña
        $sqlCheckPassword = "SELECT contrasena FROM cliente WHERE idCliente=?";
        if ($stmtCheckPassword = $conn->prepare($sqlCheckPassword)) {
            $stmtCheckPassword->bind_param("i", $user_id);

            if ($stmtCheckPassword->execute()) {
                $stmtCheckPassword->bind_result($hashedPassword);
                if ($stmtCheckPassword->fetch()) {
                    // Verificar la contraseña ingresada
                    if (password_verify($contraseña, $hashedPassword)) {
                        $stmtCheckPassword->close(); // Cerrar la declaración anterior

                        // Actualizar datos
                        $sqlUpdate = "UPDATE cliente SET nombre=?, apellido=?, tel=?, fechaNacimiento=?, correo=? WHERE idCliente=?";
                        if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
                            $stmtUpdate->bind_param("sssssi", $nombre, $apellido, $telefono, $fechaNacimiento, $correo, $user_id);

                            if ($stmtUpdate->execute()) {
                                $_SESSION['successMessage'] = 'Datos actualizados correctamente';
                                header('Location: cuenta.php');
                                exit();
                            } else {
                                $errorMessage = "Error actualizando los datos: " . $stmtUpdate->error;
                            }

                            $stmtUpdate->close();
                        } else {
                            $errorMessage = "Error preparando la consulta de actualización: " . $conn->error;
                        }
                    } else {
                        $errorMessage = "La contraseña ingresada es incorrecta.";
                    }
                } else {
                    $errorMessage = "No se encontraron datos para el usuario especificado.";
                }
                $stmtCheckPassword->close();
            } else {
                $errorMessage = "Error ejecutando la consulta: " . $stmtCheckPassword->error;
            }
        } else {
            $errorMessage = "Error preparando la consulta: " . $conn->error;
        }
    }

    // Recuperar la información del usuario
    $sql = "SELECT nombre, apellido, tel, fechaNacimiento, correo FROM cliente WHERE idCliente=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            $stmt->bind_result($nombre, $apellido, $telefono, $fechaNacimiento, $correo);
            if ($stmt->fetch()) {
                // Datos ya están disponibles en las variables
            } else {
                $errorMessage = "No se encontraron datos para el usuario especificado.";
            }
            $stmt->close();
        } else {
            $errorMessage = "Error ejecutando la consulta: " . $stmt->error;
        }
    } else {
        $errorMessage = "Error preparando la consulta: " . $conn->error;
    }

    $conn->close();

    // Obtener el mensaje de éxito y borrarlo de la sesión
    if (isset($_SESSION['successMessage'])) {
        $successMessage = $_SESSION['successMessage'];
        unset($_SESSION['successMessage']);
    }

} catch (Exception $e) {
    $errorMessage = 'Excepción capturada: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Cuenta</title>
    <link rel="stylesheet" href="../public/assets/css/cuenta.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="/public/assets/img/logo-removebg-preview.png" alt="Logo" class="logo-image">
                <h1>Café Sabroso</h1>
            </div>
            <div class="menu">
                <a href="/index.html">Inicio</a>
                <a href="#">Pedidos</a>
                <a href="#">Productos</a>
                <a href="/public/contactos.html">Contacto</a>
            </div>
        </nav>
    </header>
    <main>
        <section class="profile-container">
            <h1>Configuración de Cuenta</h1>
            <form action="cuenta.php" method="POST">
                <div class="profile-info">
                    <div class="name-fields">
                        <input type="text" name="nombre" placeholder="Nombre:" maxlength="50" required value="<?php echo htmlspecialchars($nombre); ?>">
                        <input type="text" name="apellido" placeholder="Apellido:" maxlength="50" required value="<?php echo htmlspecialchars($apellido); ?>">
                    </div>
                    <div class="input-container">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <input type="email" name="correo" id="correo" placeholder="Correo:" value="<?php echo htmlspecialchars($correo); ?>">
                    </div>
                    <div class="input-container">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <input type="password" name="contraseña" id="password" placeholder="Contraseña:" maxlength="64" required>
                    </div>
                    <input type="tel" name="telefono" id="telefono" placeholder="Teléfono:" maxlength="9" value="<?php echo htmlspecialchars($telefono); ?>">
                    <input type="date" name="fechaNacimiento" id="cumpleaños" 
                           <?php echo isset($fechaNacimiento) && $fechaNacimiento ? 'readonly' : ''; ?> 
                           value="<?php echo htmlspecialchars($fechaNacimiento); ?>">
                </div>
                <button type="submit" class="save-btn">Guardar Cambios</button>
            </form>
            <div class="action-buttons">
                <button class="logout-btn">Cerrar Sesión</button>
                <button class="delete-btn">Eliminar Cuenta</button>
            </div>
            <?php if ($errorMessage): ?>
            <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <?php if ($successMessage): ?>
            <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Mi Perfil. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
