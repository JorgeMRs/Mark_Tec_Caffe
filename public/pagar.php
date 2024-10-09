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
<?php 

$pageTitle = 'Café Sabrosos - Finalizar Pago';

$customCSS = [
    '/public/assets/css/pagar.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'
];


include 'templates/head.php' ?>

<body>
    <div class="form-container">
        <form method="post">
            <h2>>Payment Information</h2>
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
                <input id="tarjeta" type="text" placeholder="Card number:" maxlength="16" required>
                <img id="card-logo" class="card-logo" src="" alt="Card Logo" style="display: none;">
            </label>
            <div class="flex-container">
                <input id="cvv" type="text" placeholder="CVV:" maxlength="4" required>
                <input type="month" name="fecha" required>
            </div>
            <input type="text" placeholder="Owner's name:" required>
            <div class="checkbox-container">
                <input type="checkbox" name="terminos" id="terminos">
                <label for="terminos">Save this information</label>
            </div>
            <h2>Order Information</h2>
            <!-- Añadir campos para el tipo de pedido -->
            <div class="order-type">
                <label>
                    <input type="radio" name="orderType" value="En el local" onclick="toggleFields()" checked>
                    At the premises
                </label>
                <label>
                    <input type="radio" name="orderType" value="Para llevar" onclick="toggleFields()">
                    To go
                </label>
            </div>

            <!-- Campos para pedidos para llevar -->
            <div id="branch-container" style="display: none;">
                <label for="branch">Branch:</label>
                <select name="branch" id="branch">
                    <option value="" disabled selected>Select a branch</option>
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
                        echo "<option value=\"\">Error loading branches</option>";
                    }

                    // Cierra la conexión
                    $conn->close();
                    ?>
                </select>
            </div>
            <div id="pickupTime-container" style="display: none;">
                <label for="pickupTime">Pickup time:</label>
                <select name="pickupTime" id="pickupTime" required>
                    <option value="" disabled selected>Select a time</option>
                    <?php
                    $start = new DateTime('08:00:00'); // Hora de inicio en formato HH:MM:SS
                    $end = new DateTime('20:00:00'); // Hora de fin en formato HH:MM:SS
                    $interval = new DateInterval('PT30M'); // Intervalo de 30 minutos

                    while ($start <= $end) {
                        $time24 = $start->format('H:i:s'); // Formato de hora en 24 horas
                        $time12 = $start->format('g:i A'); // Formato de hora en 12 horas
                        echo "<option value=\"$time24\">$time12</option>";
                        $start->add($interval);
                    }
                    ?>
                </select>
            </div>

            <label for="orderNotes">Grades:</label>
            <textarea name="orderNotes" id="orderNotes" rows="4"></textarea>
            <div id="response-message"></div>
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $csrfTokenInput; ?>">
            <input type="submit" value="Finish payment">
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
                <h3>Order Content</h3>
                <div class="cart-items"></div>
                <div class="cart-summary">
                    <div>Subtotal: <span id="subtotal">$0.00</span></div>
                    <div>Total: <span id="total">$0.00</span></div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/card.js"></script>
    <script src="assets/js/pagar.js"></script>
</body>

</html>