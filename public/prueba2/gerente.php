<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Manager - Panel de Control</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Aquí va todo el CSS de tu página */
        body,
        html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #dee2e6;
        }

        .sidebar h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar li {
            margin-bottom: 10px;
        }

        .sidebar a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar a.active {
            background-color: #007bff;
            color: white;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        header h2 {
            margin: 0;
        }

        #notificationsBtn {
            background: none;
            border: none;
            cursor: pointer;
        }

        .dashboard-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .summary-card {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            width: 30%;
        }

        .summary-card h5 {
            margin: 0 0 10px 0;
            font-size: 1rem;
        }

        .summary-card p {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .chart-container {
            margin-bottom: 20px;
        }

        .tab-content>div {
            display: none;
        }

        .tab-content>div.active {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container">
        <nav class="sidebar">
            <ul>
                <li><a href="#" class="active" data-tab="pedidos">Pedidos</a></li>
                <li><a href="#" data-tab="historial">Historial</a></li>
                <li><a href="#" data-tab="inventario">Inventario</a></li>
                <li><a href="#" data-tab="personal">Personal</a></li>
                <li><a href="#" data-tab="finanzas">Finanzas</a></li>
                <li><a href="#" data-tab="analisis">Analisis</a></li>
            </ul>
        </nav>
        <main class="main-content">
            <header>
                <h2>Panel de Control</h2>
                <button id="notificationsBtn">
                    <!-- Icono SVG para notificaciones -->
                </button>
            </header>
            <div class="dashboard-summary">
                <div class="summary-card">
                    <h5>Ventas del Día</h5>
                    <p id="ventasDelDia">$0.00</p>

                </div>
                <div class="summary-card">
                    <h5>Pedidos Activos</h5>
                    <p id="pedidosActivos">0</p>
                </div>
                <div class="summary-card">
                    <h5>Inventario</h5>
                    <p id="totalArticulos">0 artículos</p>
                </div>
            </div>
            <div class="tab-content">
                <div id="pedidos" class="active">
                    <h3>Pedidos Activos</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Empleado</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="pedidosActivosmer"></tbody>
                    </table>
                </div>
                <div id="historial">
                    <h3>Historial de Pedidos</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="historialPedidos"></tbody>
                    </table>
                </div>
                <div id="inventario">
                    <h3>Inventario</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody id="inventarioItems"></tbody>
                    </table>
                </div>
                <div id="personal">
                    <h3>Personal</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                            </tr>
                        </thead>
                        <tbody id="personalItems"></tbody>
                    </table>

                </div>
                <div id="finanzas">
                    <h3>Finanzas</h3>
                    <p>Aquí se mostrará la información financiera.</p>
                </div>
                <div id="analisis">
                    <h3>Análisis de Ventas</h3>
                    <div class="row">
                        <div class="chart-container col-md-6 mb-4">
                            <canvas id="ventasSemanales"></canvas>
                        </div>
                        <div class="col-md-6 mb-4">
                            <canvas id="productosMasVendidos"></canvas>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <canvas id="tendenciaVentas"></canvas>
                            </div>
                            <div class="col-md-6 mb-4">
                                <canvas id="distribucionVentas"></canvas>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <canvas id="satisfaccionClientes"></canvas>
                            </div>
                            <div class="col-md-6 mb-4">
                                <canvas id="rendimientoEmpleados"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
        </main>
    </div>

    <script>
        function cargarDatos(url, elementId) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById(elementId);
                    tbody.innerHTML = data.map(item => `
                    <tr>
                        <td>${item.idPedido}</td>
                        <td>${item.fechaPedido}</td>
                        <td>${item.clienteNombre}</td>
                        <td>${item.empleadoNombre}</td>
                        <td>${item.total}</td>
                        <td>${item.estado}</td>
                    </tr>
                    `).join('');
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }

        // Función específica para cargar el inventario
        function cargarDatosInve(url, elementId) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById(elementId);
                    tbody.innerHTML = data.map(item => `
                       <tr>
                        <td>${item.id}</td>
                        <td>${item.item}</td>
                        <td>${item.quantity}</td>
                        <td>${item.price}</td>
                        </tr>
                    `).join('');
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }

        function cargarDatosHistorial(url, elementId) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById(elementId);
                    tbody.innerHTML = data.map(item => `
                        <tr>
                        <td>${item.id}</td>
                        <td>${item.date}</td>
                        <td>${item.customer}</td>
                        <td>${item.total}</td>
                        <td>${item.estado}</td>

                        </tr>
                    `).join('');
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }

        function cargarDatosPersonal(url, elementId) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById(elementId);
                    tbody.innerHTML = data.map(item => `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.firstName}</td>
                        <td>${item.lastName}</td>
                        <td>${item.email}</td>
                        <td>${item.phone}</td>
                    </tr>
                `).join('');
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }

        function cargarResumen() {
            fetch('/public/prueba2/obtener_resumen.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('ventasDelDia').textContent = `$${data.ventasDelDia.toFixed(2)}`;
                    document.getElementById('pedidosActivos').textContent = data.pedidosActivos;
                    document.getElementById('totalArticulos').textContent = `${data.totalArticulos} artículos`;
                })
                .catch(error => console.error('Error al cargar el resumen:', error));
        }

        document.addEventListener('DOMContentLoaded', function () {
            cargarResumen();
            crearGraficos(); // Llamar a la función para crear la gráfica
        });

        function manejarNavegacion() {
            const links = document.querySelectorAll('.sidebar a');
            const tabs = document.querySelectorAll('.tab-content > div');

            links.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const tabId = this.getAttribute('data-tab');

                    // Eliminar la clase 'active' de todos los enlaces y pestañas
                    links.forEach(l => l.classList.remove('active'));
                    tabs.forEach(t => t.classList.remove('active'));

                    // Agregar 'active' al enlace y pestaña seleccionada
                    this.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        }


     

        // Función para crear gráficos
        function crearGraficos() {
            // Gráfico de ventas semanales
            fetch('/public/prueba2/obtener_ventas_semanales.php')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.dia);
                    const ventas = data.map(item => item.ventas);

                    const ctxVentas = document.getElementById('ventasSemanales').getContext('2d');
                    new Chart(ctxVentas, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Ventas diarias',
                                data: ventas,
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Ventas Diarias de la Semana'
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error al obtener los datos:', error));

            // Gráfico de productos más vendidos
            fetch('/public/prueba2/obtener_productos_mas_vendidos.php')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.producto);
                    const cantidades = data.map(item => item.cantidad);

                    const ctxProductos = document.getElementById('productosMasVendidos').getContext('2d');
                    new Chart(ctxProductos, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Productos más vendidos',
                                data: cantidades,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.6)',
                                    'rgba(54, 162, 235, 0.6)',
                                    'rgba(255, 206, 86, 0.6)',
                                    'rgba(75, 192, 192, 0.6)',
                                    'rgba(153, 102, 255, 0.6)',
                                    'rgba(255, 159, 64, 0.6)',
                                ],
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Top 6 Productos Más Vendidos'
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error al obtener los datos:', error));

            // Gráfico de tendencia de ventas
            fetch('/public/prueba2/obtener_tendencia_ventas.php')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.mes);
                    const ventas = data.map(item => item.ventas);

                    const ctxTendencia = document.getElementById('tendenciaVentas').getContext('2d');
                    new Chart(ctxTendencia, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Ventas mensuales',
                                data: ventas,
                                fill: false,
                                borderColor: 'rgb(75, 192, 192)',
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Tendencia de Ventas Mensuales'
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error al obtener los datos:', error));

            // Gráfico de distribución de ventas por categoría
            fetch('/public/prueba2/obtener_distribucion_ventas.php')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.categoria);
                    const ventas = data.map(item => item.ventas);

                    const ctxDistribucion = document.getElementById('distribucionVentas').getContext('2d');
                    new Chart(ctxDistribucion, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: ventas,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)',
                                ],
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Distribución de Ventas por Categoría'
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error al obtener los datos:', error));


            // Gráfico de barras horizontales: Ventas por empleado
            fetch('/public/prueba2/obtener_rendimiento_empleados.php')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.empleado);
                    const ventas = data.map(item => item.ventas);

                    const ctxRendimiento = document.getElementById('rendimientoEmpleados').getContext('2d');
                    new Chart(ctxRendimiento, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Ventas',
                                data: ventas,
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Ventas por Empleado'
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error al obtener los datos:', error));

            // Inicializar la aplicación
            document.addEventListener('DOMContentLoaded', function () {
                // Cargar los datos dinámicamente para cada tabla
                cargarDatos('/public/prueba2/obtener_pedidos_activos.php', 'pedidosActivos');
                cargarDatosHistorial('/public/prueba2/obtener_historial_pedidos.php', 'historialPedidos');
                cargarDatosInve('/public/prueba2/obtener_inventario.php', 'inventarioItems');
                cargarDatosPersonal('/public/prueba2/obtener_personal.php', 'personalItems');
                manejarNavegacion(); // Asegúrate de que esta línea esté aquí
                crearGraficos();
            });
        }

        // Inicializar la aplicación
        document.addEventListener('DOMContentLoaded', function () {
            // Cargar los datos dinámicamente para cada tabla
            cargarDatos('/public/prueba2/obtener_pedidos_activos.php', 'pedidosActivosmer');
            cargarDatosHistorial('/public/prueba2/obtener_historial_pedidos.php', 'historialPedidos');
            cargarDatosInve('/public/prueba2/obtener_inventario.php', 'inventarioItems');
            cargarDatosPersonal('/public/prueba2/obtener_personal.php', 'personalItems');
            manejarNavegacion(); // Asegúrate de que esta línea esté aquí
            crearGraficos();
        });
    </script>
</body>

</html>