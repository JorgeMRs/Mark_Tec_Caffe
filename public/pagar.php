<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no está autenticado
    header('Location: login.php');
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
    <title>Cafe Sabrosos</title>
    <link rel="stylesheet" href="assets/css/pagar.css" media="screen and (min-width: 769px)">
    <link rel="stylesheet" href="assets/css/pagarmobile.css" media="screen and (max-width: 768px)">
    <link rel="icon" href="assets/img/icons/favicon-32x32.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="body-local">
    <header class="header-local">

    </header>
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
                <input id="tarjeta" type="text" placeholder="Número de tarjeta:" maxlength="16" required>
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
                    // Incluye el archivo de conexión a la base de datos
                    include '../src/db/db_connect.php'; // Ajusta la ruta según tu estructura de directorios
                    $conn = getDbConnection();
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
            <div id="pickupTime-container" style="display: none;">
                <label for="pickupTime">Hora de recogida:</label>
                <select name="pickupTime" id="pickupTime" required>
                    <option value="" disabled selected>Selecciona una hora</option>
                    <?php
                    $start = new DateTime('08:00');
                    $end = new DateTime('20:00');
                    $interval = new DateInterval('PT30M'); // Intervalo de 30 minutos

                    while ($start <= $end) {
                        $time24 = $start->format('H:i'); // Formato de hora en 24 horas
                        $time12 = $start->format('g:i A'); // Formato de hora en 12 horas
                        echo "<option value=\"$time24\">$time12</option>";
                        $start->add($interval);
                    }
                    ?>
                </select>
            </div>

            <label for="orderNotes">Notas:</label>
            <textarea name="orderNotes" id="orderNotes" rows="4"></textarea>
            <div id="response-message"></div>
            <input type="submit" value="Finalizar pago">
        </form>
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
        });

        async function submitOrder(event) {
            event.preventDefault(); // Prevenir el envío por defecto del formulario

            const form = event.target;
            const formData = new FormData(form);
            const responseDiv = document.getElementById('response-message');

            try {
                const response = await fetch('/src/cart/submitOrder.php', {
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

        document.querySelector('form').addEventListener('submit', submitOrder);
    </script>
    <script src="assets/js/card.js"></script>
</body>

</html>