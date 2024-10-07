<?php
include '../../src/db/db_connect.php';
require '../../vendor/autoload.php';
require '../../src/auth/verifyToken.php';


$response = checkToken();

$employeeId = $response['idEmpleado'];
$role = $response['rol'];

// Obtener el ID del pedido de la URL
$idPedido = isset($_GET['idPedido']) ? intval($_GET['idPedido']) : 0;

if ($idPedido <= 0) {
    die('ID de pedido no válido.');
}

$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

// Consulta para obtener la información del pedido
$queryPedido = "SELECT p.idPedido, p.numeroPedidoSucursal, p.estado, p.total, p.fechaPedido, p.notas, c.nombre, c.apellido
                FROM pedido p
                LEFT JOIN cliente c ON p.idCliente = c.idCliente
                WHERE p.idPedido = ?";
$stmt = $conn->prepare($queryPedido);
$stmt->bind_param('i', $idPedido);
$stmt->execute();
$resultPedido = $stmt->get_result();
$pedido = $resultPedido->fetch_assoc();

if (!$pedido) {
    die('No se encontró el pedido.');
}

$stmt->close();

// Consulta para obtener los detalles del pedido (productos)
$queryDetalles = "SELECT pd.idProducto, pr.nombre, pd.cantidad, pd.precio
                  FROM pedidodetalle pd
                  LEFT JOIN producto pr ON pd.idProducto = pr.idProducto
                  WHERE pd.idPedido = ?";
$stmtDetalles = $conn->prepare($queryDetalles);
$stmtDetalles->bind_param('i', $idPedido);
$stmtDetalles->execute();
$resultDetalles = $stmtDetalles->get_result();
// Generar el contenido HTML para la nota con los productos del pedido
$productosHtml = '';
if ($resultDetalles->num_rows > 0) {
    while ($detalle = $resultDetalles->fetch_assoc()) {
        $subtotal = $detalle['cantidad'] * $detalle['precio'];
        $productosHtml .= "<li>{$detalle['nombre']} - {$detalle['cantidad']} x {$detalle['precio']}€ = {$subtotal}€</li>";
    }
} else {
    $productosHtml = '<li>No hay productos en este pedido.</li>';
}

// Añadir notas debajo de la lista de productos
$notasHtml = '<h4>Notas</h4><p>' . htmlspecialchars($pedido['notas']) . '</p>';

// Consulta para obtener el siguiente pedido en estado "En preparación"
$querySiguiente = "SELECT idPedido FROM pedido WHERE estado = 'En preparación' AND idPedido > ? ORDER BY idPedido ASC LIMIT 1";
$stmtSiguiente = $conn->prepare($querySiguiente);
$stmtSiguiente->bind_param('i', $idPedido);
$stmtSiguiente->execute();
$resultSiguiente = $stmtSiguiente->get_result();
$siguientePedido = $resultSiguiente->fetch_assoc();

// Consulta para obtener el pedido anterior en estado "En preparación"
$queryAnterior = "SELECT idPedido FROM pedido WHERE estado = 'En preparación' AND idPedido < ? ORDER BY idPedido DESC LIMIT 1";
$stmtAnterior = $conn->prepare($queryAnterior);
$stmtAnterior->bind_param('i', $idPedido);
$stmtAnterior->execute();
$resultAnterior = $stmtAnterior->get_result();
$anteriorPedido = $resultAnterior->fetch_assoc();

$stmtSiguiente->close();
$stmtAnterior->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido - Café Sabrosos</title>
    <link rel="stylesheet" href="detallesPedidos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    .nav-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: #f2f2f2;
        border: none;
        font-size: 24px;
        cursor: pointer;
        padding: 10px;
    }

    .left-arrow {
        left: 10px;
    }

    .right-arrow {
        right: 10px;
    }

    .nav-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .nav-button i {
        font-size: 24px;
        color: #333;
    }
</style>

<body>
    <!-- Botones de navegación -->
    <?php if ($anteriorPedido): ?>
        <a href="detallesPedidos.php?idPedido=<?php echo $anteriorPedido['idPedido']; ?>" id="leftArrow" class="nav-button left-arrow">
            <i class="fas fa-arrow-left"></i>
        </a>
    <?php endif; ?>

    <?php if ($siguientePedido): ?>
        <a href="detallesPedidos.php?idPedido=<?php echo $siguientePedido['idPedido']; ?>" id="rightArrow" class="nav-button right-arrow">
            <i class="fas fa-arrow-right"></i>
        </a>
    <?php endif; ?>
    <div class="paper pink">
        <div class="tape-section"></div>
        <p> Pedido #<?php echo htmlspecialchars($pedido['idPedido']); ?></p>
        <div class="tape-section"></div>
    </div>

    <div class="container">
        <h2>Información del Pedido</h2>
        <!-- Información del pedido -->
        <p><strong>ID Pedido:</strong> <?php echo htmlspecialchars($pedido['idPedido']); ?></p>
        <p><strong>Número de Pedido en la Sucursal:</strong> <?php echo htmlspecialchars($pedido['numeroPedidoSucursal']); ?></p>
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['nombre'] . ' ' . $pedido['apellido']); ?></p>
        <p><strong>Estado:</strong> <span id="estadoActual"><?php echo htmlspecialchars($pedido['estado']); ?></span></p>
        <p><strong>Total:</strong> <?php echo htmlspecialchars($pedido['total']); ?>€</p>
        <p><strong>Fecha del Pedido:</strong> <?php echo htmlspecialchars($pedido['fechaPedido']); ?></p>

        <!-- Formulario para cambiar el estado del pedido -->
        <form id="estadoForm">
            <input type="hidden" name="idPedido" value="<?php echo htmlspecialchars($pedido['idPedido']); ?>">
            <label for="estado">Cambiar Estado:</label>
            <select name="estado" id="estado">
                <?php
                $estadoActual = $pedido['estado'];
                $estados = [
                    'Pendiente' => ['En Preparación'],
                    'En Preparación' => ['Listo para Recoger', 'Cancelado'],
                    'Listo para Recoger' => ['Completado', 'Cancelado'],
                    'Completado' => [],
                    'Cancelado' => []
                ];

                foreach ($estados[$estadoActual] as $estado) {
                    echo "<option value=\"$estado\">$estado</option>";
                }
                ?>
            </select>
            <button type="submit" class="button">Actualizar Estado</button>
        </form>

        <div id="respuesta"></div>

        <!-- Modal de confirmación -->
        <div id="cancelacionModal" style="display:none;">
            <div class="modal-content">
                <h2>Confirmación de Cancelación</h2>
                <p>¿Está seguro de que desea cancelar este pedido?</p>
                <textarea id="notasCancelacion" placeholder="Notas sobre la cancelación (opcional)" rows="4"></textarea>
                <div class="modal-buttons">
                    <button id="confirmarCancelacion">Sí, cancelar</button>
                    <button id="cancelarCancelacion">No, volver</button>
                </div>
            </div>
        </div>
        <!-- Modal de Completado -->
        <div id="completadoModal" class="modal completado-modal" style="display:none;">
            <div class="modal-content completado-modal-content">
                <h2 class="completado-modal-title">Pedido Completado</h2>
                <p class="completado-modal-message">El pedido ha sido completado con éxito.</p>
                <div class="completado-modal-buttons">
                    <button id="cerrarModal" class="completado-modal-close-button">Cerrar</button>
                </div>
            </div>
        </div>
        <style>
            .completado-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
                /* Asegúrate de que el modal esté por encima de otros elementos */
            }

            .completado-modal-content {
                background-color: white;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
            }

            .completado-modal-title {
                margin-bottom: 10px;
            }

            .completado-modal-buttons {
                margin-top: 20px;
            }

            .completado-modal-close-button {
                background-color: #4CAF50;
                /* Cambia esto a tu color preferido */
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 5px;
                cursor: pointer;
            }

            .completado-modal-close-button:hover {
                background-color: #45a049;
                /* Cambia esto a tu color preferido */
            }
        </style>
        <style>
            #cancelacionModal {
                position: fixed;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            }

            .modal-content {
                background: #fff;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
                width: 90%;
                max-width: 500px;
            }

            .modal-content h2 {
                margin-top: 0;
                color: #333;
            }

            .modal-content p {
                margin: 10px 0;
                color: #555;
            }

            #notasCancelacion {
                width: calc(100% - 20px);
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                margin-bottom: 20px;
                box-sizing: border-box;
            }

            .modal-buttons {
                display: flex;
                justify-content: center;
                gap: 10px;
            }

            .modal-buttons button {
                background-color: #007bff;
                border: none;
                color: white;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
                border-radius: 5px;
                transition: background-color 0.3s;
            }

            .modal-buttons button:hover {
                background-color: #0056b3;
            }

            #cancelarCancelacion {
                background-color: #6c757d;
            }

            #cancelarCancelacion:hover {
                background-color: #5a6268;
            }
        </style>
        <script>
            document.getElementById('estadoForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Evita que el formulario se envíe de manera tradicional

                const formData = new FormData(this);
                const estado = formData.get('estado');

                if (estado === 'Cancelado') {
                    // Muestra el modal de confirmación
                    document.getElementById('cancelacionModal').style.display = 'flex';
                    return;
                }

                // Si el estado no es "Cancelado", envía la solicitud
                fetch('/src/chef/actualizarEstado.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        const respuestaDiv = document.getElementById('respuesta');
                        console.log(respuestaDiv); // Esto debería mostrar el elemento o `null`
                        respuestaDiv.innerHTML = ''; // Limpia el contenido del div
                        respuestaDiv.style.color = '';

                        if (data.status === 'success') {
                            respuestaDiv.innerHTML = data.message; // Mensaje de éxito
                            respuestaDiv.style.color = 'green';

                            // Actualiza el <span> con el nuevo estado
                            document.getElementById('estadoActual').textContent = estado;

                            // Si el estado es "Completado", muestra el modal y redirige
                            if (estado === 'Completado') {
                                document.getElementById('completadoModal').style.display = 'flex';
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 8000);
                            } else if (data.redirect) {
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 8000);
                            }

                            // Actualiza el <select> con los nuevos estados permitidos
                            updateEstadosSelect(data.estadosPermitidos);
                        } else {
                            respuestaDiv.innerHTML = data.message; // Mensaje de error
                            respuestaDiv.style.color = 'red';
                        }
                    })
                    .catch(error => {
                        console.error('Error en el fetch:', error);
                        const respuestaDiv = document.getElementById('respuesta');
                        respuestaDiv.innerHTML = 'Error al actualizar el estado.';
                        respuestaDiv.style.color = 'red';
                    });

            });

            document.getElementById('confirmarCancelacion').addEventListener('click', function() {
                const formData = new FormData(document.getElementById('estadoForm'));
                formData.append('notasCancelacion', document.getElementById('notasCancelacion').value);

                fetch('/src/chef/actualizarEstado.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json()) // Asegúrate de que el servidor devuelve JSON
                    .then(data => {
                        const respuestaDiv = document.getElementById('respuesta');
                        respuestaDiv.innerHTML = ''; // Limpia el contenido del div
                        respuestaDiv.style.color = '';

                        if (data.status === 'success') {
                            respuestaDiv.innerHTML = data.message; // Mensaje de éxito
                            respuestaDiv.style.color = 'green';
                            if (data.redirect) {
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 2000);
                            }
                        } else {
                            respuestaDiv.innerHTML = data.message; // Mensaje de error
                            respuestaDiv.style.color = 'red';
                        }
                        document.getElementById('cancelacionModal').style.display = 'none'; // Oculta el modal
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const respuestaDiv = document.getElementById('respuesta');
                        respuestaDiv.innerHTML = 'Error al actualizar el estado.';
                        respuestaDiv.style.color = 'red';
                        document.getElementById('cancelacionModal').style.display = 'none'; // Oculta el modal
                    });
            });
            // Función para actualizar el <select> con los nuevos estados permitidos
            function updateEstadosSelect(estadosPermitidos) {
                const select = document.getElementById('estado');
                select.innerHTML = ''; // Limpiar las opciones actuales

                if (Array.isArray(estadosPermitidos)) {
                    estadosPermitidos.forEach(estado => {
                        const option = document.createElement('option');
                        option.value = estado;
                        option.textContent = estado;
                        select.appendChild(option);
                    });
                } else {
                    console.error('Error: estadosPermitidos no es un array.');
                }
            }
            document.querySelector('.completado-modal-close-button').addEventListener('click', function() {
                document.getElementById('completadoModal').style.display = 'none';
                window.location.href = 'cocina.php';
            });
        </script>
        <a href="cocina.php" class="button" onclick="window.history.back()">Volver a Pedidos Activos</a>

        <?php $conn->close(); ?>
    </div>

    <div class="quote-container">
        <i class="pin"></i>
        <blockquote class="note yellow">
            <h4>Productos en el Pedido</h4>
            <ol id="stuff">
                <?php echo $productosHtml; ?>
            </ol>

            <?php if (!empty($pedido['notas'])): ?>
                <h4>Notas</h4>
                <p><?php echo htmlspecialchars($pedido['notas']); ?></p>
            <?php endif; ?>
        </blockquote>
    </div>
    <script>
        // Función para manejar las teclas izquierda y derecha
        document.addEventListener('keydown', function(event) {
            if (event.key === 'ArrowLeft') {
                // Si la tecla izquierda es presionada, ir al enlace de la flecha izquierda
                const leftArrow = document.getElementById('leftArrow');
                if (leftArrow) {
                    window.location.href = leftArrow.href;
                }
            } else if (event.key === 'ArrowRight') {
                // Si la tecla derecha es presionada, ir al enlace de la flecha derecha
                const rightArrow = document.getElementById('rightArrow');
                if (rightArrow) {
                    window.location.href = rightArrow.href;
                }
            }
        });
    </script>

</body>

</html>