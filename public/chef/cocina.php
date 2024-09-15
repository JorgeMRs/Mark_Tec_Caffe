<?php
session_start();

// Verificar si el usuario ha iniciado sesión y si su rol es 'Chef'
if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'Chef') {
    header('Location: /public/error/403.html');
    exit();
}

include '../../src/db/db_connect.php';

// Obtener el ID del empleado (chef) de la sesión
$employeeId = $_SESSION['employee_id'];

$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

// Consulta para obtener la sucursal del chef
$querySucursal = "SELECT idSucursal FROM empleado WHERE idEmpleado = ?";
$stmtSucursal = $conn->prepare($querySucursal);
$stmtSucursal->bind_param('i', $employeeId);
$stmtSucursal->execute();
$resultSucursal = $stmtSucursal->get_result();
$empleado = $resultSucursal->fetch_assoc();

$idSucursal = $empleado['idSucursal']; // Sucursal del chef

// Consulta para obtener los pedidos activos de la sucursal del chef
$query = "SELECT p.idPedido, p.estado, p.total, p.fechaPedido, p.notas, c.nombre, c.apellido
          FROM pedido p
          LEFT JOIN cliente c ON p.idCliente = c.idCliente
          WHERE p.idSucursal = ? AND p.estado IN ('Pendiente', 'En Preparación', 'Listo para Recoger')
          ORDER BY p.fechaPedido ASC";

$stmtPedidos = $conn->prepare($query);
$stmtPedidos->bind_param('i', $idSucursal);
$stmtPedidos->execute();
$result = $stmtPedidos->get_result();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Sabrosos - Pedidos</title>
    <link rel="stylesheet" href="/public/assets/css/mozo/pedidos.css">
</head>
<style>
    /* Estilos generales */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        margin-top: 20px;
        color: #f1f1f1;
    }

    .container {
        width: 90%;
        margin: 0 auto;
        max-width: 1200px;
    }

    .header {
        background-color: #1B0D0B;
        color: white;
        padding: 10px 0;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background-color: #fff;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #DAA520;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 14px;
    }

    td {
        font-size: 14px;
        color: #555;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    select {
        padding: 6px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    button {
        background-color: #DAA520;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-size: 14px;
    }

    button:hover {
        background-color: #1B0D0B;
    }
    a.button {
    display: inline-block;
    background-color: #007BFF;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

a.button:hover {
    background-color: #0056b3;
    color: #fff;
}

    /* Estilo para mensaje de "No hay pedidos" */
    p {
        text-align: center;
        font-size: 16px;
        color: #888;
    }

    /* Responsive design */
    @media screen and (max-width: 768px) {

        table,
        thead,
        tbody,
        th,
        td,
        tr {
            display: block;
        }

        th {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        tr {
            margin-bottom: 15px;
        }

        td {
            position: relative;
            padding-left: 50%;
            text-align: right;
        }

        td:before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            width: 45%;
            padding-right: 10px;
            font-weight: bold;
            text-align: left;
        }

        td:last-child {
            border-bottom: 1px solid #ddd;
        }
    }
</style>

<body>
    
    <div class="header">
        <h1>Pedidos Activos</h1>
    </div>

    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Notas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pedido = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['idPedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['nombre'] . ' ' . $pedido['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['total']); ?>€</td>
                            <td><?php echo htmlspecialchars($pedido['fechaPedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['notas']); ?></td>
                            <td>
                                <form method="post" action="actualizar_pedido.php">
                                    <a href="detallesPedidos.php?idPedido=<?php echo htmlspecialchars($pedido['idPedido']); ?>" class="button">Ver Detalles</a>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay pedidos activos en este momento.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>
</body>

</html>