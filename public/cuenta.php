<?php
session_start();
require '../src/db/db_connect.php';
require '../src/uploads/avatarUpload.php';
require '../src/uploads/avatarDelete.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html'); // Redirigir a la página de inicio de sesión si no está autenticado
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = getDbConnection();

$successMessage = '';
$errorMessage = '';
$nombre = '';
$apellido = '';
$telefono = '';
$fechaNacimiento = '';
$correo = '';
$contraseña = '';
$avatar = '';

try {
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Procesar subida del avatar
        if (isset($_FILES['avatar'])) {
            $avatarUploadMessage = uploadAvatar($user_id, $_FILES['avatar'], $conn);
            if (strpos($avatarUploadMessage, 'Error') !== false) {
                $errorMessage = $avatarUploadMessage;
            } else {
                $successMessage = $avatarUploadMessage;
            }
        }
        // Procesar eliminación del avatar
        if (isset($_POST['deleteAvatar']) && $_POST['deleteAvatar'] === 'true') {
            $deleteAvatarMessage = deleteAvatar($user_id, $conn);
            if (str_contains($deleteAvatarMessage, 'Error')) {
                $errorMessage = $deleteAvatarMessage;
            } else {
                $successMessage = $deleteAvatarMessage;
            }
        }
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
                    if (password_verify($contraseña, $hashedPassword)) {
                        $stmtCheckPassword->close();

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
    $sql = "SELECT nombre, apellido, tel, fechaNacimiento, correo, avatar FROM cliente WHERE idCliente=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $stmt->bind_result($nombre, $apellido, $telefono, $fechaNacimiento, $correo, $avatar);
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>

<body>
<header>
        <nav>
            <div class="logo">
                <a href="/" class="logo-link">
                    <img src="/public/assets/img/logo-removebg-preview.png" alt="Logo" class="logo-image" />
                    <h1>Café Sabrosos</h1>
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="/public/local.html">Locales</a></li>
                <li><a href="/public/tienda.html">Productos</a></li>
                <li><a href="#">Ofertas</a></li>
                <li><a href="#">Reservas</a></li>
                <li><a href="/public/contactos.html">Contacto</a></li>
                <li>
                    <a href="/public/cuenta.php"><img src="/public/assets/img/image.png" alt="Usuario"
                            class="user-icon" /></a>
                </li>
                <div class="cart">
                    <a href="carrito.html">
                        <img src="/public/assets/img/cart.png" alt="Carrito" />
                        <span id="cart-counter" class="cart-counter">0</span>
                    </a>
                </div>
            </ul>
        </nav>
    </header>
    <main>
        <section class="profile-container">
            <h1>Configuración de Cuenta</h1>
            <form action="cuenta.php" method="POST" enctype="multipart/form-data">
                <div class="profile-info">
                    <!-- Sección para el avatar -->
                    <div class="avatar-section">
                        <label for="avatar" class="avatar-label">Foto de Perfil:</label>
                        <div class="avatar-preview">
                            <img src="<?php echo isset($avatar) ? '/public/assets/img/avatars/' . htmlspecialchars($avatar) . '?t=' . time() : '/public/assets/img/user-circle-svgrepo-com.svg'; ?>" alt="Avatar" class="avatar-image">
                        </div>
                        <input type="file" name="avatar" id="avatar" accept="image/*">
                        <?php if (!empty($avatar)): ?>
                            <!-- Formulario para eliminar el avatar, mostrado solo si hay avatar -->
                            <form action="cuenta.php" method="POST" style="display:inline;">
                                <input type="hidden" name="deleteAvatar" value="true">
                                <button type="submit" class="delete-avatar-btn">Eliminar Avatar</button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <!-- Resto del formulario... -->
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
                <form action="/src/db/logout.php" method="POST" class="logout-form">
                    <button type="submit" class="logout-btn">Cerrar Sesión</button>
                </form>
                <form action="">
                    <button class="delete-btn">Eliminar Cuenta</button>
                </form>
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
        <div class="footer-content">
            <div class="footer-section about">
                <h3>Café Sabrosos</h3>
                <p>
                    Disfruta del mejor café con nosotros. Nos preocupamos por cada
                    detalle, desde la selección de los granos hasta la preparación de tu
                    bebida.
                </p>
                <div class="socials">
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-instagram"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-section links">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="/public/local.html">Locales</a></li>
                    <li><a href="/public/tienda.html">Productos</a></li>
                    <li><a href="#">Ofertas</a></li>
                    <li><a href="#">Reservas</a></li>
                    <li><a href="/public/contactos.html">Contacto</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h3>Contáctanos</h3>
                <ul>
                    <li><i class="fa fa-map-marker"></i> 123 Calle Café, San José</li>
                    <li><i class="fa fa-phone"></i> +598 123 4567</li>
                    <li><i class="fa fa-envelope"></i> info@cafesabrosos.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Café Sabrosos. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>