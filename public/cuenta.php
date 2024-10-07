<?php
require '../vendor/autoload.php';
require '../src/db/db_connect.php';
require '../src/auth/verifyToken.php';

$response = checkToken();

// Verificar si la respuesta fue exitosa antes de acceder a 'idCliente'
if ($response['success']) {
    $user_id = $response['idCliente'];
    // Aquí puedes usar $user_id según sea necesario
} else {
    // Manejar el caso en que la verificación del token falló
    echo $response['message']; // Muestra un mensaje de error
    // O redirige a otra página
    header('Location: /public/login.php');
    exit();
}

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
  '/public/assets/js/languageSelect.js',
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
                    <span>Has iniciado sesión con Google</span>
                </div>
            </div>
        <?php endif; ?>
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
                <form action="/src/auth/logout.php" method="POST" class="logout-form">
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
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <?php include 'templates/footer.php'; ?>
</body>
<script src="/public/assets/js/cuenta.js"></script>
<script src="/public/assets/js/updateCartCounter.js"></script>
</html>