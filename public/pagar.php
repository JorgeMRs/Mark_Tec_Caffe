<?php
require '../src/db/db_connect.php';
require '../vendor/autoload.php';
require '../src/auth/verifyToken.php';

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$secretKey = $_ENV['JWT_SECRET']; // Clave secreta para firmar el JWT

$csrfToken = $_COOKIE['csrf_token'] ?? '';

if (!$csrfToken) {
    // Si no hay token, genera uno nuevo
    $csrfPayload = [
        'csrf_token' => bin2hex(random_bytes(32)), // Genera un token CSRF seguro
        'iat' => time(), // Emisión (Issued at)
        'exp' => time() + 600, // Expira en 10 minutos
    ];

    // Codificar el token CSRF en JWT
    $csrfToken = JWT::encode($csrfPayload, $secretKey, 'HS256');

    // Guardar el JWT en una cookie segura
    setcookie('csrf_token', $csrfToken, [
        'expires' => time() + 600, // Expira en 10 minutos
        'httponly' => true,
        'secure' => true,
        'samesite' => 'Strict',
        'path' => '/', // Asegúrate de que sea accesible en toda la aplicación
    ]);
}

// Asigna el token CSRF al input oculto
$csrfTokenInput = htmlspecialchars($csrfToken);


// Verificar el token de sesión
$response = checkToken();
$user_id = $response['idCliente'];

// Conectar a la base de datos
try {
    $conn = getDbConnection();
} catch (Exception $e) {
    header("Location: carrito.php");
    exit();
}

// Comprobar si el carrito tiene productos
$stmt = $conn->prepare("
    SELECT COUNT(cd.idProducto) AS product_count 
    FROM carritodetalle cd 
    JOIN carrito c ON cd.idCarrito = c.idCarrito 
    WHERE c.idCliente = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($product_count);
$stmt->fetch();
$stmt->close();

// Si el carrito está vacío, redirigir a la página del carrito
if ($product_count == 0) {
    header("Location: carrito.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="pagar">
    <meta name="author" content="MarkTec">
    <title>Café Sabrososo - Realizar Pedido</title>
    <link rel="stylesheet" href="assets/css/pagar.css" media="screen and (min-width: 769px)">
    <link rel="stylesheet" href="assets/css/pagarmobile.css" media="screen and (max-width: 768px)">
    <link rel="icon" href="assets/img/icons/favicon-32x32.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="form-container">
        <form method="post">
            <h2>Información de pago</h2>
            <div class="payment-buttons">
                <button type="button" class="paypal-button">
                    <i class="fab fa-paypal"></i>
                </button>
                <button type="button" class="google-button">
                    <i class="fab fa-google"></i>
                </button>
                <button type="button" class="apple-button">
                    <i class="fab fa-apple"></i>
                </button>
            </div>
            <label class="tarjeta">
                <input id="tarjeta" type="text" placeholder="Número de tarjeta:" maxlength="19" required>
                <img id="card-logo" class="card-logo" src="" alt="Card Logo" style="display: none;">
            </label>
            <div class="flex-container">
                <input id="cvv" type="text" placeholder="CVV:" maxlength="4" required>
                <input type="month" name="fecha" required>
            </div>
            <input type="text" placeholder="Nombre del titular:" required>
            <div class="checkbox-container">
                <input type="checkbox" name="terminos" id="terminos">
                <label for="terminos">Guardar esta información</label>
            </div>
            <h2>Información de pedido</h2>
            <!-- Añadir campos para el tipo de pedido -->
            <div class="order-type">
                <label>
                    <input type="radio" name="orderType" value="En el local" onclick="toggleFields()" checked>
                    En el local
                </label>
                <label>
                    <input type="radio" name="orderType" value="Para llevar" onclick="toggleFields()">
                    Para llevar
                </label>
            </div>

            <!-- Campos para pedidos para llevar -->
            <div id="branch-container" style="display: none;">
                <label for="branch">Sucursal:</label>
                <select name="branch" id="branch">
                    <option value="" disabled selected>Selecciona una sucursal</option>
                    <?php
                    // Consulta para obtener las sucursales
                    $query = "SELECT idSucursal, nombre FROM sucursal";
                    $result = $conn->query($query);

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $idSucursal = htmlspecialchars($row['idSucursal']);
                            $nombreSucursal = htmlspecialchars($row['nombre']);
                            echo "<option value=\"$idSucursal\">$nombreSucursal</option>";
                        }
                        $result->free();
                    } else {
                        echo "<option value=\"\">Error al cargar sucursales</option>";
                    }

                    // Cierra la conexión
                    $conn->close();
                    ?>
                </select>
            </div>
            <div id="pickupTime-container">
                <label for="pickupTime">Hora de recogida:</label>
                <select name="pickupTime" id="pickupTime" required>
                    <option value="" disabled selected>Selecciona una hora</option>
                </select>
            </div>


            <label for="orderNotes">Notas:</label>
            <textarea name="orderNotes" id="orderNotes" rows="4"></textarea>
            <div id="response-message"></div>
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $csrfTokenInput; ?>">
            <input type="submit" value="Finalizar pago">
        </form>
        <div class="back-arrow-container">
            <a href="carrito.php" class="back-arrow">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>

        <div class="cart-icon-container">
            <div class="cart-icon" id="cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="cart-content" id="cart-content" style="display: none;">
                <h3>Contenido del Pedido</h3>
                <div class="cart-items"></div>
                <div class="cart-summary">
                    <div>Subtotal: <span id="subtotal">$0.00</span></div>
                    <div>Total: <span id="total">$0.00</span></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleFields() {
    const orderType = document.querySelector('input[name="orderType"]:checked').value;
    const pickupTimeField = document.getElementById('pickupTime');

    document.getElementById('branch-container').style.display = 'block'; // Mostrar siempre el campo de sucursal
    const isPickup = orderType === 'Para llevar';

    document.getElementById('pickupTime-container').style.display = isPickup ? 'block' : 'none';

    // Si es "Para llevar", el campo pickupTime es requerido; si no, no lo es.
    if (isPickup) {
        pickupTimeField.setAttribute('required', 'required');
    } else {
        pickupTimeField.removeAttribute('required');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleFields();
    obtenerHorasDisponibles();
});
async function submitOrder(event) {
    event.preventDefault(); // Prevenir el envío por defecto del formulario

    const form = event.target;
    const formData = new FormData(form);
    const responseDiv = document.getElementById('response-message');
    const csrfToken = document.getElementById('csrf_token').value;

    formData.append('csrf_token', csrfToken);
    // Mostrar los valores para depuración
    for (const [key, value] of formData.entries()) {
        console.log(`Campo: ${key}, Valor: ${value}`);
    }

    try {
        const response = await fetch('/src/client/submitOrder.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            // Redirige a la página de confirmación con el ID del pedido
            window.location.href = `pagoConfirmado.php?order_id=${result.orderId}`;
        } else {
            responseDiv.innerHTML = `<p class="error">Error: ${result.message}</p>`;
        }
    } catch (error) {
        responseDiv.innerHTML = `<p class="error">Error en la solicitud: ${error.message}</p>`;
    }
}
        // Realizar la petición a la API para obtener las horas disponibles
        async function obtenerHorasDisponibles() {
    try {
        let response = await fetch('../src/client/checkDate.php');
        
        // Verifica si la respuesta fue exitosa
        if (!response.ok) {
            throw new Error('Error en la red: ' + response.status);
        }

        let data = await response.json();

        if (data.horasDisponibles && data.horasDisponibles.length > 0) {
            let select = document.getElementById('pickupTime');

            // Limpiar las opciones anteriores
            select.innerHTML = '<option value="" disabled selected>Selecciona una hora</option>';

            // Agregar las horas disponibles al select
            data.horasDisponibles.forEach(hora => {
                let option = document.createElement('option');
                option.value = hora;
                option.textContent = hora;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error al obtener las horas:', error);
    }
}

    </script>
    <script src="assets/js/card.js"></script>
</body>

</html>