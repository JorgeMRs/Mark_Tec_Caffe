<?php
session_start();
require '../src/db/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
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


        // Obtener datos del formulario
        $correo = $_POST['correo'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $fechaNacimiento = $_POST['fechaNacimiento'] ?? '';

        // Sanitizar y validar datos
        $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);
        $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        $apellido = htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8');
        $telefono = preg_replace('/[^0-9+]/', '', $telefono);
        $fechaNacimiento = htmlspecialchars($fechaNacimiento, ENT_QUOTES, 'UTF-8');

        // Verificar contraseña
        $sqlCheckPassword = "SELECT contrasena FROM cliente WHERE idCliente=?";
        if ($stmtCheckPassword = $conn->prepare($sqlCheckPassword)) {
            $stmtCheckPassword->bind_param("i", $user_id);

            if ($stmtCheckPassword->execute()) {
                $stmtCheckPassword->bind_result($hashedPassword);
                if ($stmtCheckPassword->fetch()) {
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
                    $errorMessage = "No se encontraron datos para el usuario especificado.";
                }
            } else {
                $errorMessage = "Error ejecutando la consulta: " . $stmtCheckPassword->error;
            }
        } else {
            $errorMessage = "Error preparando la consulta: " . $conn->error;
        }
    }

    // Recuperar la información del usuario
    $sql = "SELECT nombre, apellido, tel, fechaNacimiento, correo, avatar, contrasena FROM cliente WHERE idCliente=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $stmt->bind_result($nombre, $apellido, $telefono, $fechaNacimiento, $correo, $avatar, $hashedPassword);
            if ($stmt->fetch()) {
                // Datos ya están disponibles en las variables
                $contraseña = $hashedPassword; // Guardar la contraseña en una variable para mostrarla en el formulario
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
    <link rel="stylesheet" href="assets/css/cuenta.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-64x64.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
</head>

<body>
    <header>
        <?php include 'templates/nav.php'; ?>
    </header>
    <main>
        <section class="profile-container">
            <h1>Configuración de Cuenta</h1>
            <form action="cuenta.php" method="POST" enctype="multipart/form-data">
                <div class="profile-info">
                    <div class="avatar-section">
                        <label for="avatar" class="avatar-label">Foto de Perfil:</label>
                        <div class="avatar-preview">
                            <img src="<?php echo isset($avatar) ? '/public/assets/img/avatars/' . htmlspecialchars($avatar) . '?t=' . time() : '/public/assets/img/user-circle-svgrepo-com.svg'; ?>" alt="Avatar" class="avatar-image">
                        </div>
                        <input type="file" name="avatar" id="avatar" accept="image/*">
                        <div class="error-avatar" style="display: none;"></div>
                        <div class="success-avatar" style="display: none;"></div>
                        <button type="button" id="deleteAvatarBtn" class="delete-avatar-btn" style="display: <?php echo !empty($avatar) ? 'block' : 'none'; ?>;">Eliminar Avatar</button>
                    </div>
                    <div class="name-fields">
                        <input type="text" name="nombre" placeholder="Nombre:" maxlength="50" required value="<?php echo htmlspecialchars($nombre); ?>">
                        <input type="text" name="apellido" placeholder="Apellido:" maxlength="50" required value="<?php echo htmlspecialchars($apellido); ?>">
                    </div>
                    <div class="input-container">
                        <input type="email" name="correo" id="correo" placeholder="Correo:" value="<?php echo htmlspecialchars($correo); ?>">
                    </div>
                    <div class="input-container">
                        <a href="cambiarContrasena.php"><i class="fa-solid fa-pen-to-square"></i></a>
                        <input type="password" name="contraseña" id="password" placeholder="Contraseña" maxlength="64" value="contraseña" readonly>
                    </div>

                    <input type="tel" name="telefono" id="telefono" placeholder="Teléfono:" maxlength="9" value="<?php echo htmlspecialchars($telefono); ?>">
                    <input type="date" name="fechaNacimiento" id="cumpleaños"
                        <?php echo isset($fechaNacimiento) && $fechaNacimiento ? 'readonly' : ''; ?>
                        value="<?php echo htmlspecialchars($fechaNacimiento); ?>">
                        <button type="button" id="viewPedidosBtn" class="view-pedidos-btn">Ver Mis Pedidos</button>
                </div>
                <button type="submit" class="save-btn">Guardar Cambios</button>
            </form>
        
            <div class="action-buttons">
                <form action="/src/db/logout.php" method="POST" class="logout-form">
                    <button type="submit" class="logout-btn">Cerrar Sesión</button>
                </form>
                <form id="deleteAccountForm" method="POST">
                    <button type="button" class="delete-btn" id="deleteAccountBtn">Eliminar Cuenta</button>
                </form>
            </div>
            <?php if ($errorMessage): ?>
                <div id="errorMessage" class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <div id="successMessage" class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
        </section>
    </main>
    <div id="pedidosModal" class="modal">
        <div class="modal-content2">
            <span class="close">&times;</span>
            <h2>Mis Pedidos</h2>
            <div id="pedidosList">
                <!-- Aquí se cargarán los pedidos mediante AJAX -->
            </div>
            <div id="pagination">
                <!-- Controles de paginación -->
            </div>
        </div>
    </div>
    <div id="cancelConfirmationModal" class="modal">
        <div class="modal-content2">
            <span class="close cancel-close">&times;</span>
            <h2>Cancelar Pedido</h2>
            <p>¿Estás seguro de que deseas cancelar este pedido?</p>
            <textarea id="cancelNotes" placeholder="Añadir notas adicionales (opcional)"></textarea>
            <button id="confirmCancel" class="view-pedidos-btn">Confirmar Cancelación</button>
            <button id="cancelCancel" class="view-pedidos-btn2">Volver Atrás</button>
        </div>
    </div>
    <style>
        #cancelConfirmationModal {
            display: none;
            /* Ocultar modal por defecto */
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        #cancelConfirmationModal .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 500px;
            margin: auto;
            position: relative;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        #cancelConfirmationModal h2 {
            margin-top: 0;
        }

        #cancelConfirmationModal textarea {
            width: 100%;
            height: 100px;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            resize: none;
            box-sizing: border-box;
        }

        #cancelConfirmationModal button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 5px;
            text-align: center;
            text-decoration: none;
        }

        #confirmCancel {
            background-color: #e74c3c;
            /* Dorado */
        }

        #cancelCancel {
            background-color: #daa520;
            /* Rojo */
        }


        #cancelConfirmationModal .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
    <style>

    </style>
    <!-- Modal para Cropper.js -->
    <div id="cropperModal" class="cropper-modal">
        <div class="cropper-modal-content">
            <button type="button" class="cropper-close-button">&times;</button>
            <img id="cropperImage" class="cropper-image" src="" alt="Imagen para Recortar">
            <div class="cropper-buttons">
                <button type="button" id="cropImageBtn" class="cropper-crop-button">Recortar y Subir</button>
                <button type="button" id="cancelCropBtn" class="cropper-cancel-button">Cancelar</button>
            </div>
        </div>
    </div>
    <!-- First Modal: Confirm Deletion -->
    <div id="deleteAccountModal" class="modal">
        <div class="modal-content">
            <h2>¿Estás seguro de que deseas eliminar tu cuenta?</h2>
            <p>Esta acción no se puede deshacer.</p>
            <p><a href="politicas-de-eliminacion-de-cuenta.html" target="_blank">Haz clic aquí para conocer las políticas de eliminación de datos.</a></p>
            <div class="modal-buttons">
                <form id="deleteAccountForm" method="POST">
                    <input type="hidden" name="eliminarCuenta" value="verdadero">
                    <button type="button" id="confirmDeleteBtn">Sí, eliminar cuenta</button>
                </form>
                <button type="button" id="cancelDeleteBtn">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Second Modal: Code Verification -->
    <div id="codeVerificationModal" class="modal">
        <div class="modal-content">
            <button type="button" id="backToDeleteModalBtn" class="back-button">
                &larr; Regresar
            </button>
            <h2>Verificación de Código</h2>
            <p>Ingresa el código de verificación para proceder con la eliminación:</p>
            <p id="generatedCode"></p>
            <input type="text" id="userInputCode" placeholder="Ingresa el código aquí">
            <div class="modal-buttons">
                <button type="button" id="verifyCodeBtn">Verificar Código</button>
            </div>
        </div>
    </div>
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
<script src="/public/assets/js/updateCartCounter.js"></script>
<script src="/public/assets/js/cuenta.js"></script>

</html>