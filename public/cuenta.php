<?php
session_start();
require '../src/db/db_connect.php';
require '../src/account/accountDelete.php';

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

        // Procesar eliminación de cuenta
        if (isset($_POST['eliminarCuenta']) && $_POST['eliminarCuenta'] === 'verdadero') {
            $deleteAccountMessage = deleteAccount($user_id); // Llamar a la función
            if ($deleteAccountMessage) {
                $errorMessage = $deleteAccountMessage;
            } else {
                // Redirigir después de eliminar la cuenta
                header('Location: /index.php');
                exit();
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
    <link rel="stylesheet" href="assets/css/cuenta.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-64x64.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>

<body>
    <header>
    <?php include 'templates/nav-blur.php'; ?>
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
                    <!-- Resto del formulario... -->
                    <div class="name-fields">
                        <input type="text" name="nombre" placeholder="Nombre:" maxlength="50" required value="<?php echo htmlspecialchars($nombre); ?>">
                        <input type="text" name="apellido" placeholder="Apellido:" maxlength="50" required value="<?php echo htmlspecialchars($apellido); ?>">
                    </div>
                    <div class="input-container">
                        <input type="email" name="correo" id="correo" placeholder="Correo:" value="<?php echo htmlspecialchars($correo); ?>">
                    </div>
                    <div class="input-container">
                        <a href="cambiarContrasena.php"><i class="fa-solid fa-pen-to-square"></i></a>
                        <input type="password" name="contraseña" id="password" placeholder="Contraseña" maxlength="64">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatar');
            const avatarImage = document.querySelector('.avatar-image');
            const successAvatarDiv = document.querySelector('.success-avatar');
            const errorAvatarDiv = document.querySelector('.error-avatar');
            const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');
            const saveBtn = document.querySelector('.save-btn');
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');

            function clearGeneralMessages() {
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
                if (successMessage) {
                    successMessage.style.display = 'none';
                }
            }
            saveBtn.addEventListener('click', function(event) {
                if (errorAvatarDiv.textContent.trim() !== '' || successAvatarDiv.textContent.trim() !== '') {
                    successAvatarDiv.style.display = 'none';
                    errorAvatarDiv.style.display = 'none';
                }
            });

            successAvatarDiv.style.display = 'none';
            errorAvatarDiv.style.display = 'none';

            if (avatarInput) {
                avatarInput.addEventListener('change', function(event) {
                    clearGeneralMessages();
                    const file = event.target.files[0];

                    if (file) {
                        const formData = new FormData();
                        formData.append('avatar', file);

                        fetch('/src/uploads/avatarUpload.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    avatarImage.src = '/public/assets/img/avatars/' + encodeURIComponent(data.avatar) + '?t=' + new Date().getTime();

                                    avatarInput.value = '';

                                    successAvatarDiv.textContent = data.message;
                                    successAvatarDiv.style.display = 'block';
                                    errorAvatarDiv.textContent = '';
                                    errorAvatarDiv.style.display = 'none';
                                    deleteAvatarBtn.style.display = 'block';
                                } else {
                                    avatarInput.value = '';
                                    errorAvatarDiv.textContent = data.message;
                                    errorAvatarDiv.style.display = 'block';
                                    successAvatarDiv.textContent = ''; 
                                    successAvatarDiv.style.display = 'none';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                avatarInput.value = '';

                                errorAvatarDiv.textContent = 'Error al subir el avatar. Por favor, intenta de nuevo.';
                                errorAvatarDiv.style.display = 'block'; 
                                successAvatarDiv.textContent = '';
                                successAvatarDiv.style.display = 'none';
                            });
                    }
                });
            }



            if (deleteAvatarBtn) {
                deleteAvatarBtn.addEventListener('click', function(event) {
                    event.preventDefault();
                    clearGeneralMessages();

                    if (confirm('¿Estás seguro de que deseas eliminar tu avatar?')) {
                        fetch('/src/uploads/avatarDelete.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    action: 'deleteAvatar'
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    avatarImage.src = '/public/assets/img/user-circle-svgrepo-com.svg';

                                    deleteAvatarBtn.style.display = 'none';

                                    successAvatarDiv.textContent = data.message;
                                    successAvatarDiv.style.display = 'block';
                                    errorAvatarDiv.textContent = '';
                                    errorAvatarDiv.style.display = 'none'; 
                                } else {
                                    errorAvatarDiv.textContent = data.message;
                                    errorAvatarDiv.style.display = 'block';
                                    successAvatarDiv.textContent = '';
                                    successAvatarDiv.style.display = 'none'; 
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                errorAvatarDiv.textContent = 'Error al eliminar el avatar. Por favor, intenta de nuevo.';
                                errorAvatarDiv.style.display = 'block';
                                successAvatarDiv.textContent = '';
                                successAvatarDiv.style.display = 'none';
                            });
                    }
                });
            }
        });
    </script>
    <style>
        /* Existing styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
            border-radius: 10px;
        }

        .modal-content h2 {
            color: #b8860b;
        }

        .modal-buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .modal-buttons button {
            padding: 10px 20px;
            border: none;
            white-space: nowrap;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            width: 195px;
        }

        #confirmDeleteBtn {
            background-color: #e74c3c;
            color: white;
        }

        #cancelDeleteBtn {
            background-color: #b8860b;
            color: white;
        }

        form#deleteAccountForm {
            margin-right: 1vh;
            display: flex;
            justify-content: space-between;
        }

        .modal-content p a {
            color: #b8860b;
        }

        .modal-content p a:hover {
            color: #333;
        }

        @media (max-width: 768px) {
            .modal-content {
                margin: 50% auto;
                width: 85%;
            }

            .modal-buttons {
                flex-direction: column;
            }

            .modal-buttons button {
                width: 100%;
                max-width: none;
                margin-top: 10px;
            }

            form#deleteAccountForm {
                margin-right: 0;
                display: flex;
            }
        }

        .profile-container {
            padding: 18px;
        }

        /* Styles for the second modal */
        #codeVerificationModal .modal-content {
            background-color: #f9f9f9;
        }

        #codeVerificationModal input[type="text"] {
            width: 80%;
            padding: 10px;
            margin-top: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        #codeVerificationModal #verifyCodeBtn {
            background-color: #b8860b;
            color: white;
        }

        #codeVerificationModal #cancelCodeVerificationBtn {
            background-color: #e74c3c;
            color: white;
        }

        #generatedCode {
            user-select: none;
            /* Evita que el texto sea seleccionado */
        }

        .back-button {
            border: none;
            font-size: 18px;
            color: #b8860b;
            background-color: #f9f9f9;
            cursor: pointer;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .back-button:hover {
            background-color: #f9f9f9;
            color: #b8860b;
        }
    </style>

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

    <script>
        // Handle the first modal
        document.getElementById('deleteAccountBtn').onclick = function() {
            document.getElementById('deleteAccountModal').style.display = 'block';
        };

        document.getElementById('cancelDeleteBtn').onclick = function() {
            document.getElementById('deleteAccountModal').style.display = 'none';
        };

        // Random code generation
        function generateRandomCode() {
            return Math.floor(100000 + Math.random() * 900000); // Generates a 6-digit random number
        }

        // Handle the second modal
        document.getElementById('confirmDeleteBtn').onclick = function() {
            // Close the first modal
            document.getElementById('deleteAccountModal').style.display = 'none';

            // Generate and display the random code in the second modal
            var randomCode = generateRandomCode();
            document.getElementById('generatedCode').textContent = 'Código: ' + randomCode;

            // Show the second modal
            document.getElementById('codeVerificationModal').style.display = 'block';

            // Verify the code
            document.getElementById('verifyCodeBtn').onclick = function() {
                var userCode = document.getElementById('userInputCode').value;
                if (userCode == randomCode) {
                    // Proceed with account deletion
                    document.getElementById('deleteAccountForm').submit();
                } else {
                    alert('Código incorrecto. Inténtalo de nuevo.');
                }
            };
        };

        document.getElementById('backToDeleteModalBtn').onclick = function() {
            document.getElementById('codeVerificationModal').style.display = 'none';
            document.getElementById('deleteAccountModal').style.display = 'block';
        };
    </script>
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