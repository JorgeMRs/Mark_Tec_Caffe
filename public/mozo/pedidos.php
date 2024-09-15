<?php
session_start();

if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'Mozo') {
    header('Location: /public/error/403.html');
    exit();
}

include '../../src/db/db_connect.php';

$limit = 10; // Número de pedidos por página
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

// Obtener la sucursal del empleado
$employee_id = $_SESSION['employee_id'];
$querySucursal = "SELECT idSucursal FROM empleado WHERE idEmpleado = ?";
$stmtSucursal = $conn->prepare($querySucursal);
$stmtSucursal->bind_param('i', $employee_id);

if (!$stmtSucursal->execute()) {
    die('Error en la consulta de sucursal: ' . $stmtSucursal->error);
}

$sucursalResult = $stmtSucursal->get_result()->fetch_assoc();
$sucursal_id = $sucursalResult['idSucursal'];

$stmtSucursal->close();

// Definir qué estados consideramos como 'activos'
$activos = ['Pendiente', 'En Preparación', 'Listo para Recoger'];
$placeholders = implode(',', array_fill(0, count($activos), '?'));

$query = "SELECT p.idPedido, p.numeroPedidoSucursal AS numeroPedido, p.tipoPedido, p.total, p.horaRecogida, s.nombre AS sucursal, m.numero AS numeroMesa, p.estado
          FROM pedido p
          LEFT JOIN sucursal s ON p.idSucursal = s.idSucursal
          LEFT JOIN mesa m ON p.idMesa = m.idMesa
          WHERE p.estado IN ($placeholders) AND p.idSucursal = ?
          ORDER BY p.numeroPedidoSucursal ASC
          LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);
$params = array_merge($activos, [$sucursal_id, $limit, $offset]);
$stmt->bind_param(str_repeat('s', count($activos)) . 'iii', ...$params);


if (!$stmt->execute()) {
    die('Error en la consulta: ' . $stmt->error);
}

$result = $stmt->get_result();
$pedidos = $result->fetch_all(MYSQLI_ASSOC);

// Consulta para obtener el número total de pedidos activos
$queryCount = "SELECT COUNT(*) AS total
               FROM pedido p
               WHERE p.estado IN ($placeholders) AND p.idSucursal = ?";

$stmtCount = $conn->prepare($queryCount);
$stmtCount->bind_param(str_repeat('s', count($activos)) . 'i', ...array_merge($activos, [$sucursal_id]));

if (!$stmtCount->execute()) {
    die('Error en la consulta de conteo: ' . $stmtCount->error);
}

$countResult = $stmtCount->get_result()->fetch_assoc();
$totalPedidos = $countResult['total'];
$totalPages = ceil($totalPedidos / $limit);

$stmt->close();
$stmtCount->close();
$conn->close();

function getEstadoColor($estado)
{
    switch ($estado) {
        case 'Pendiente':
            return '#FFF3CD';
        case 'En Preparación':
            return '#61c0bf';
        case 'Listo para Recoger':
            return '#D5EDDB';
        case 'Completado':
            return '#F9D7DB';
        case 'Cancelado':
            return '#777';
        default:
            return '#fff';
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Sabrosos - Pedidos Activos</title>
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/public/assets/img/icons/favicon-64x64.png">
    <link rel="icon" type="image/x-icon" href="/public/assets/img/icons/favicon.ico">
    <link rel="stylesheet" href="/public/assets/css/mozo/mozoPedidos.css">
</head>

<body>
    <style>
        /* Estilos para el modal */
        #detalleModal {
            display: none;
            /* Por defecto el modal está oculto */
            position: fixed;
            /* Fijo para que esté en la misma posición en toda la pantalla */
            z-index: 1000;
            /* Asegúrate de que el modal esté encima de otros elementos */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            /* Añade scroll si el contenido es largo */
            background-color: rgba(0, 0, 0, 0.5);
            /* Fondo negro con opacidad */
        }

        /* Estilo del contenido del modal */
        .modal-content {
            background-color: #fff;
            /* Fondo blanco */
            margin: 15% auto;
            /* Margen automático para centrar el modal */
            padding: 20px;
            /* Espaciado interior */
            border: 1px solid #888;
            /* Borde gris claro */
            width: 80%;
            /* Ancho del modal */
            max-width: 600px;
            /* Máximo ancho */
            border-radius: 8px;
            /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Sombra para dar profundidad */
        }

        /* Estilo del botón de cerrar */
        .close {
            color: #aaa;
            /* Color del texto del botón de cerrar */
            float: right;
            /* Alinear a la derecha */
            font-size: 28px;
            /* Tamaño de fuente */
            font-weight: bold;
            /* Negrita */
        }

        .close:hover,
        .close:focus {
            color: black;
            /* Color al pasar el ratón o enfocar */
            text-decoration: none;
            /* Quitar subrayado */
            cursor: pointer;
            /* Cambiar cursor a puntero */
        }

        /* Estilo de la lista dentro del modal */
        .modal-content ul {
            list-style-type: none;
            /* Quitar viñetas */
            padding: 0;
            /* Quitar padding */
            margin: 0;
            /* Quitar margin */
        }

        .modal-content li {
            padding: 8px 0;
            /* Espaciado entre elementos de la lista */
            border-bottom: 1px solid #ddd;
            /* Línea divisoria */
        }

        .modal-content li span {
            font-weight: bold;
            /* Negrita para las etiquetas */
            margin-right: 10px;
            /* Espacio entre etiqueta y valor */
        }

        .modal-content li:last-child {
            border-bottom: none;
            /* Quitar línea divisoria en el último elemento */
        }

        /* Botón de cancelar pedido */
        .cancel-order-button {
            display: block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #e74c3c;
            /* Rojo de advertencia */
            color: #fff;
            /* Texto blanco */
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }

        .cancel-order-button:hover {
            background-color: #c0392b;
            /* Rojo oscuro */
        }

        /* Estilo para el botón de ver detalles */
        .view-details-button {
            background-color: #1B0C0A;
            /* Color de fondo del botón */
            color: white;
            /* Color del texto */
            border: none;
            /* Sin borde */
            border-radius: 4px;
            /* Bordes redondeados */
            padding: 10px 20px;
            /* Espaciado interno del botón */
            font-size: 16px;
            /* Tamaño del texto */
            cursor: pointer;
            /* Cambia el cursor al pasar sobre el botón */
            transition: background-color 0.3s ease;
            /* Transición suave para el cambio de color */
        }

        /* Efecto al pasar el ratón sobre el botón */
        .view-details-button:hover {
            background-color: #74623c;
            /* Color de fondo cuando el ratón pasa sobre el botón */
        }
        #productosDetalles ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

#productosDetalles li {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    background-color: #f9f9f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

#productosDetalles li span {
    font-weight: normal;
    color: #333;
}

/* Estilo del nombre del producto */
#productosDetalles li .product-name {
    font-size: 18px;
    font-weight: bold;
}

/* Estilo del precio y cantidad del producto */
#productosDetalles li .product-price,
#productosDetalles li .product-quantity {
    font-size: 16px;
}
#confirmCancelModal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.close-confirm {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-confirm:hover,
.close-confirm:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

textarea {
    width: 100%;
    height: 100px;
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

.confirm-cancel-button,
.cancel-cancel-button {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.confirm-cancel-button {
    background-color: #e74c3c;
    color: #fff;
}

.confirm-cancel-button:hover {
    background-color: #c0392b;
}

.cancel-cancel-button {
    background-color: #95a5a6;
    color: #fff;
}

.cancel-cancel-button:hover {
    background-color: #7f8c8d;
}
    </style>

<div id="detalleModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Detalles del Pedido</h2>
        <ul id="pedidoDetalles"></ul>
        <h3>Productos</h3>
        <div id="productosDetalles"></div>
        <button id="cancelOrderButton" class="cancel-order-button" style="display: none;">Cancelar Pedido</button>
    </div>
</div>

<div id="confirmCancelModal" class="modal">
    <div class="modal-content">
        <span class="close-confirm">&times;</span>
        <h2>Confirmar Cancelación</h2>
        <p>¿Estás seguro de que deseas cancelar este pedido?</p>
        <textarea id="cancelNotes" placeholder="Escribe una nota de cancelación (opcional)"></textarea>
        <button id="confirmCancelButton" class="confirm-cancel-button">Confirmar Cancelación</button>
        <button id="cancelCancelButton" class="cancel-cancel-button">Cancelar</button>
    </div>
</div>

    <div class="container">
        <div class="header-buttons">
            <a href="mozo.php" class="back-button" onclick="window.history.back();">Volver a la Página Principal</a>
            <a href="crearPedido.php" class="new-order-button">Crear Nuevo Pedido</a>
        </div>
        <h1>Pedidos Activos</h1>
        <table>
            <thead>
                <tr>
                    <th>Numero Pedido</th>
                    <th>Estado</th>
                    <th>Tipo de Pedido</th>
                    <th>Total</th>
                    <th>Hora de Recogida</th>
                    <th>Sucursal</th>
                    <th>Número de Mesa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['numeroPedido']); ?></td> <!-- Mostrar el numeroPedidoSucursal -->
                        <td style="background-color: <?php echo getEstadoColor($pedido['estado']); ?>;">
                            <?php echo htmlspecialchars($pedido['estado']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($pedido['tipoPedido']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($pedido['total'], 2, ',', '.')); ?> €</td>
                        <td><?php echo htmlspecialchars($pedido['horaRecogida']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['sucursal']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['numeroMesa']) ? htmlspecialchars($pedido['numeroMesa']) : 'N/A'; ?></td> <!-- Mostrar número de mesa o 'N/A' -->
                        <td>
                            <button class="view-details-button" data-id="<?php echo htmlspecialchars($pedido['idPedido']); ?>">Ver Detalles</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">« Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Siguiente »</a>
            <?php endif; ?>
        </div>
    </div>
</body>
<script src="/public/assets/js/mozo/mozo.js"></script>
</html>