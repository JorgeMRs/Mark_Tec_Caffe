<?php
require '../vendor/autoload.php';
require '../src/db/db_connect.php';
require '../src/auth/verifyToken.php';

$response = checkToken();

if ($response['success']) {
    $user_id = $response['idCliente'];
    $uid = $response['uid'];
    // Aquí puedes usar $user_id según sea necesario
} else {
    // Manejar el caso en que la verificación del token falló
    echo $response['message']; // Muestra un mensaje de error
    // O redirige a otra página
    header('Location: /public/login.php');
    exit();
}
$uid = $response['uid'];

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
        $fechaNacimiento = $_POST['fechaNacimiento'] ?? ''; // Puede estar vacío

        // Sanitizar y validar datos
        $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);
        $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        $apellido = htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8');
        $telefono = preg_replace('/[^0-9+]/', '', $telefono);
        
        // Verificar contraseña
        $sqlCheckPassword = "SELECT contrasena FROM cliente WHERE idCliente=?";
        if ($stmtCheckPassword = $conn->prepare($sqlCheckPassword)) {
            $stmtCheckPassword->bind_param("i", $user_id);

            if ($stmtCheckPassword->execute()) {
                $stmtCheckPassword->bind_result($hashedPassword);
                if ($stmtCheckPassword->fetch()) {
                    $stmtCheckPassword->close();

                    // Construir la consulta de actualización
                    $sqlUpdate = "UPDATE cliente SET nombre=?, apellido=?, tel=?, correo=?"; // Base de la consulta
                    $types = "ssss"; // Tipos de los parámetros
                    $params = [$nombre, $apellido, $telefono, $correo]; // Parámetros a incluir

                    // Solo agregar fecha de nacimiento si está presente
                    if (!empty($fechaNacimiento)) {
                        $fechaNacimiento = htmlspecialchars($fechaNacimiento, ENT_QUOTES, 'UTF-8');
                        $sqlUpdate .= ", fechaNacimiento=?";
                        $types .= "s"; // Agregar tipo para fecha
                        $params[] = $fechaNacimiento; // Agregar valor para fecha
                    }

                    $sqlUpdate .= " WHERE idCliente=?";
                    $types .= "i"; // Tipo para el ID del usuario
                    $params[] = $user_id; // Agregar ID del usuario

                    // Preparar la consulta
                    if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
                        $stmtUpdate->bind_param($types, ...$params); // Usar los tipos y parámetros dinámicamente

                        if ($stmtUpdate->execute()) {
                            $successMessage = 'Datos actualizados correctamente';
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
} catch (Exception $e) {
    $errorMessage = 'Excepción capturada: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<?php 

$pageTitle = 'Café Sabrosos - Cuenta';

$customCSS = [
    '/public/assets/css/cuenta.css',
    '/public/assets/css/nav.css',
    '/public/assets/css/footer.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css'

];
$customJS = [
  '/public/assets/js/updateCartCounter.js',
 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js',
];

include 'templates/head.php' ?>
<body>
    <header>
        <?php include 'templates/nav.php'; ?>
    </header>
    <main>
        <?php if (!empty($uid)): ?>
            <div class="google-signin-container" style="display: flex; justify-content: center; margin-top: 20px;">
                <div class="google-signin-message">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="96px" height="96px">
                        <path fill="#fbc02d" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12	s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20	s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
                        <path fill="#e53935" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039	l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
                        <path fill="#4caf50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36	c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
                        <path fill="#1565c0" d="M43.611,20.083L43.595,20L42,20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571	c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
                    </svg>
                    <span id="googleSignInMessage">Has iniciado sesión con Google</span>
                </div>
            </div>
        <?php endif; ?>
        <section class="profile-container">
            <h1 id="account-settings-title">Configuración de Cuenta</h1> <!-- Cambiado a id correcto -->
            <form action="cuenta.php" method="POST" enctype="multipart/form-data">
                <div class="profile-info">
                    <div class="avatar-section">
                        <label for="avatar" class="avatar-label" id="profilePicture">Foto de Perfil:</label>
                        <div class="avatar-preview">
                            <img src="<?php echo isset($avatar) ? '/public/assets/img/avatars/' . htmlspecialchars($avatar) . '?t=' . time() : '/public/assets/img/user-circle-svgrepo-com.svg'; ?>" alt="Avatar" class="avatar-image">
                        </div>
                        <input type="file" name="avatar" id="avatar" accept="image/*">
                        <div class="error-avatar" style="display: none;" id="avatarError"></div>
                        <div class="success-avatar" style="display: none;" id="avatarSuccess"></div>
                        <button type="button" id="deleteAvatarBtn" class="delete-avatar-btn" style="display: <?php echo !empty($avatar) ? 'block' : 'none'; ?>;">Eliminar Avatar</button>
                    </div>
                    <div class="name-fields">
                        <input type="text" name="nombre" placeholder="Nombre:" maxlength="50" required value="<?php echo htmlspecialchars($nombre); ?>" id="first-name"> <!-- Cambiado a id correcto -->
                        <input type="text" name="apellido" placeholder="Apellido:" maxlength="50" required value="<?php echo htmlspecialchars($apellido); ?>" id="last-name"> <!-- Cambiado a id correcto -->
                    </div>
                    <div class="input-container">
                        <input type="email" name="correo" id="email" placeholder="Correo:" value="<?php echo htmlspecialchars($correo); ?>">
                    </div>
                    <div class="input-container">
                        <a href="cambiarContrasena.php" id="changePasswordLink"><i class="fa-solid fa-pen-to-square"></i></a>
                        <input type="password" name="contraseña" id="password" placeholder="Contraseña" maxlength="64" value="contraseña" readonly id="passwordField">
                    </div>

                    <input type="tel" name="telefono" id="phone" placeholder="Teléfono:" maxlength="9" value="<?php echo htmlspecialchars($telefono); ?>">
                    <input type="date" name="fechaNacimiento" id="birthdate" <?php echo isset($fechaNacimiento) && $fechaNacimiento ? 'readonly' : ''; ?> value="<?php echo htmlspecialchars($fechaNacimiento); ?>">
                    <button type="button" id="viewPedidosBtn" class="view-pedidos-btn">Ver Mis Pedidos</button>
                </div>
                <button type="submit" class="save-btn" id="save-changes-button">Guardar Cambios</button> <!-- Cambiado a id correcto -->
            </form>

            <div class="action-buttons">
                <form action="/src/auth/logout.php" method="POST" class="logout-form">
                    <button type="submit" class="logout-btn" id="log-out-button">Cerrar Sesión</button> <!-- Cambiado a id correcto -->
                </form>
                <form id="deleteAccountForm" method="POST">
                    <button type="button" class="delete-btn" id="deleteAccountBtn">Eliminar Cuenta</button> <!-- Cambiado a id correcto -->
                </form>
            </div>
            <?php if ($errorMessage): ?>
                <div id="error-message" class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div> <!-- Cambiado a id correcto -->
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <div id="success-message" class="success-message"><?php echo htmlspecialchars($successMessage); ?></div> <!-- Cambiado a id correcto -->
            <?php endif; ?>
        </section>
    </main>
    <div id="pedidosModal" class="modal">
        <div class="modal-content2">
            <span class="close" id="closeOrdersModal">&times;</span>
            <h2 id="myOrdersTitle">Mis Pedidos</h2>
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
            <span class="close cancel-close" id="cancelClose">&times;</span>
            <h2 id="cancelOrderTitle">Cancelar Pedido</h2>
            <p id="cancelOrderMessage">¿Estás seguro de que deseas cancelar este pedido?</p>
            <textarea id="cancelNotes" placeholder="Añadir notas adicionales (opcional)"></textarea>
            <button id="confirmCancel" class="view-pedidos-btn" id="confirmCancelButton">Confirmar Cancelación</button>
            <button id="cancelCancel" class="view-pedidos-btn2" id="cancelCancelButton">Volver Atrás</button>
        </div>
    </div>
    <!-- Modal para Cropper.js -->
    <div id="cropperModal" class="cropper-modal">
        <div class="cropper-modal-content">
            <button type="button" class="cropper-close-button" id="closeCropperModal">&times;</button>
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
            <h2 id="deleteAccountConfirmationTitle">¿Estás seguro de que deseas eliminar tu cuenta?</h2>
            <p id="deleteAccountWarning">Esta acción no se puede deshacer.</p>
            <p id="deletionPolicyLink"><a href="politicas-de-eliminacion-de-cuenta.html" target="_blank">Haz clic aquí para conocer las políticas de eliminación de datos.</a></p>
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
            <button type="button" id="backToDeleteModalBtn" class="back-button" id="backToDeleteModalButton">
                &larr; Regresar
            </button>
            <h2 id="codeVerificationTitle">Verificación de Código</h2>
            <p id="enterCodeMessage">Ingresa el código de verificación para proceder con la eliminación:</p>
            <p id="generatedCode">Código: </p>
            <input type="text" id="userInputCode" placeholder="Ingresa el código aquí">
            <div class="modal-buttons">
                <button type="button" id="verifyCodeBtn">Verificar Código</button>
            </div>
        </div>
    </div>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <?php include 'templates/footer.php'; ?>
</body>
<script src="/public/assets/js/cuenta.js" ></script>
<script src="/public/assets/js/languageSelect.js"></script>
</html>
