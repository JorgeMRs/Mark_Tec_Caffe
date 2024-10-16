<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Manager - Panel de Control</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <!-- Otros enlaces y estilos -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Aquí va todo el CSS de tu página */
        body,

        html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
        }

        /* Estilos para el modal */
        .modal {
            /* Oculto por defecto */
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Fin de estilos para el modal */

        .acciones {
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        .contenedor-graficos {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .grafico {
            flex: 1 1 45%;
            /* Ajusta el tamaño de las gráficas para que ocupen el 45% del ancho del contenedor */
            margin: 10px;
            min-width: 300px;
            /* Ajusta el ancho mínimo según sea necesario */
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
            display: block
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
                                <th>Acciones</th> 
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
                                <th>Acciones</th>
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
                                <th>Categoría</th>
                                <th>Acciones</th>
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
                                <th>Correo</th>
                                <!-- <th>Contraseña</th> -->
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>CI</th>
                                <th>Puesto</th>
                                <th>Sucursal</th>
                                <th>Fecha de Ingreso</th>
                                <th>Salario</th>
                                <th>Teléfono</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Acciones</th> <!-- Nueva columna para los botones de edición -->
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
                    <div class="contenedor-graficos">
                        <div class="grafico">
                            <canvas id="ventasSemanales"></canvas>
                        </div>
                        <div class="grafico">
                            <canvas id="productosMasVendidos"></canvas>
                        </div>
                        <div class="grafico">
                            <canvas id="tendenciaVentas"></canvas>
                        </div>
                        <div class="grafico">
                            <canvas id="distribucionVentas"></canvas>
                        </div>
                    </div>
                </div>

                <?php include 'modals.html'; ?>

        </main>
    </div>

    <script>
        // Función genérica para abrir modales y cargar datos
        function mostrarFormulario(modalId, url, formFields) {
            openModal(modalId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('Datos recibidos:', data);
                    for (const field in formFields) {
                        if (formFields.hasOwnProperty(field)) {
                            // Ajustar los nombres de los campos para que coincidan con los datos devueltos
                            if (field === 'idHistorial') {
                                document.getElementById(formFields[field]).value = data['idPedido'];
                            } else if (field === 'fecha') {
                                document.getElementById(formFields[field]).value = data['fechaPedido'];
                            } else {
                                document.getElementById(formFields[field]).value = data[field];
                            }
                        }
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }

          function mostrarFormulario(modalId, url, formFields) {
            openModal(modalId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('Datos recibidos:', data);
                    for (const field in formFields) {
                        if (formFields.hasOwnProperty(field)) {
                            // Ajustar los nombres de los campos para que coincidan con los datos devueltos
                            if (field === 'idHistorial') {
                                document.getElementById(formFields[field]).value = data['idPedido'];
                            } else if (field === 'fecha') {
                                document.getElementById(formFields[field]).value = data['fechaPedido'];
                            } else {
                                document.getElementById(formFields[field]).value = data[field];
                            }
                        }
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }

                function mostrarFormularioEmpleado(modalId, url, formFields) {
            openModal(modalId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('Datos recibidos:', data);
                    for (const field in formFields) {
                        if (formFields.hasOwnProperty(field) && field !== 'contrasena') {
                            document.getElementById(formFields[field]).value = data[field] || '';
                        }
                    }
                    // Asegurarse de que los campos de contraseña estén vacíos
                    document.getElementById('personalContrasena').value = '';
                    document.getElementById('personalConfirmarContrasena').value = '';
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }


        // Función para abrir modal
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'block';
        }

        // Función para cerrar modales
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'none';
        }

        // Asignar eventos a los botones de cerrar
        document.querySelectorAll('.close').forEach(button => {
            button.onclick = function () {
                const modal = button.closest('.modal');
                modal.style.display = 'none';
            }
        });

        // Cerrar el modal al hacer clic fuera de él
        window.onclick = function (event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Función para manejar el envío de formularios
        function handleFormSubmit(formId, successCallback) {
            const form = document.getElementById(formId);
            form.onsubmit = function (e) {
                e.preventDefault();
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Operación realizada con éxito');
                            successCallback();
                            closeModal(form.closest('.modal').id);
                        } else {
                            alert('Error: ' + data.error);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        // Función genérica para cargar datos de una URL y mostrarlos en una tabla
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
                    <td>
                        <select class="acciones" data-id="${item.idPedido}">
                            <option value="">Seleccionar</option>
                            <option value="modificar">Modificar</option>
                            <option value="eliminar">Eliminar</option>
                        </select>
                    </td>
                </tr>
                `).join('');

                    // Agregar event listeners para los select de acciones
                    document.querySelectorAll('.acciones').forEach(select => {
                        select.addEventListener('change', function () {
                            const id = select.getAttribute('data-id');
                            const action = select.value;
                            if (action === 'modificar') {
                                mostrarFormulario('editModal', `/public/prueba2/obtener_pedido_por_id.php?id=${id}`, {
                                    idPedido: 'editId',
                                    fechaPedido: 'editFechaPedido',
                                    idCliente: 'editClienteId',
                                    idEmpleado: 'editEmpleadoId',
                                    total: 'editTotal',
                                    estado: 'editEstado'
                                });
                            } else if (action === 'eliminar') {
                                eliminarPedido(id);
                            }
                            // Reset the select value to default
                            select.value = '';
                        });
                    });
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }

        // Función específica para cargar el historial de pedidos
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
                    <td>
                        <select class="acciones" data-id="${item.id}">
                            <option value="">Seleccionar</option>
                            <option value="modificar">Modificar</option>
                            <option value="eliminar">Eliminar</option>
                        </select>
                    </td>
                </tr>
            `).join('');
                    // Agregar event listeners para los select de acciones
                    document.querySelectorAll('.acciones').forEach(select => {
                        select.addEventListener('change', function () {
                            const id = select.getAttribute('data-id');
                            const action = select.value;
                            if (action === 'modificar') {
                                mostrarFormulario('historialModal', `/public/prueba2/obtener_historial_por_id.php?id=${id}`, {
                                    idHistorial: 'historialId',
                                    fecha: 'historialFecha',
                                    clienteNombre: 'historialClienteNombre',
                                    total: 'historialTotal',
                                    estado: 'historialEstado'
                                });
                            } else if (action === 'eliminar') {
                                eliminarPedido(id);
                            }
                            // Reset the select value to default
                            select.value = '';
                        });
                    });
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
                    <td>${item.category}</td>
                    <td>
                        <select class="acciones" data-id="${item.id}">
                            <option value="">Seleccionar</option>
                            <option value="modificar">Modificar</option>
                            <option value="eliminar">Eliminar</option>
                        </select>
                    </td>
                </tr>
            `).join('');

                    // Agregar event listeners para los select de acciones
                    document.querySelectorAll('.acciones').forEach(select => {
                        select.addEventListener('change', function () {
                            const id = select.getAttribute('data-id');
                            const action = select.value;
                            if (action === 'modificar') {
                                mostrarFormulario('inventarioModal', `/public/prueba2/obtener_producto_por_id.php?id=${id}`, {
                                    idProducto: 'inventarioId',
                                    nombreProducto: 'inventarioNombre',
                                    cantidad: 'inventarioCantidad',
                                    precio: 'inventarioPrecio',
                                    idCategoria: 'inventarioCategoria'
                                });
                            } else if (action === 'eliminar') {
                                eliminarPedido(id);
                            }
                            // Reset the select value to default
                            select.value = '';
                        });
                    });
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }

        // Función específica para cargar el personal
           function cargarDatosPersonal(url, elementId) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById(elementId);
                tbody.innerHTML = data.map(item => `
                    <tr>
                        <td>${item.idEmpleado}</td>
                        <td>${item.correo}</td>
                        <td>${item.nombre}</td>
                        <td>${item.apellido}</td>
                        <td>${item.ci}</td>
                        <td>${item.idPuesto}</td>
                        <td>${item.idSucursal}</td>
                        <td>${item.fechaIngreso}</td>
                        <td>${item.salario}</td>
                        <td>${item.tel}</td>
                        <td>${item.fechaNacimiento}</td>
                        <td>
                            <select class="acciones" data-id="${item.idEmpleado}">
                                <option value="">Seleccionar</option>
                                <option value="modificar">Modificar</option>
                                <option value="eliminar">Eliminar</option>
                            </select>
                        </td>
                    </tr>
                `).join('');
    
                // Agregar event listeners para los select de acciones
                document.querySelectorAll('.acciones').forEach(select => {
                    select.addEventListener('change', function () {
                        const id = select.getAttribute('data-id');
                        const action = select.value;
                        if (action === 'modificar') {
                            mostrarFormularioEmpleado('personalModal', `/public/prueba2/obtener_empleado_por_id.php?id=${id}`, {
                                idEmpleado: 'personalId',
                                correo: 'personalCorreo',
                                // No llenar el campo de contraseña
                                contrasena: '',
                                nombre: 'personalNombre',
                                apellido: 'personalApellido',
                                ci: 'personalCi',
                                idPuesto: 'personalPuesto',
                                idSucursal: 'personalSucursal',
                                fechaIngreso: 'personalFechaIngreso',
                                salario: 'personalSalario',
                                tel: 'personalTel',
                                fechaNacimiento: 'personalFechaNacimiento'
                            });
                        } else if (action === 'eliminar') {
                            eliminarEmpleado(id);
                        }
                        // Reset the select value to default
                        select.value = '';
                    });
                });
            })
            .catch(error => console.error('Error al cargar datos:', error));
    }
        // Función para cargar el resumen de ventas
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

        // Ejecutar la función al cargar la página
        document.addEventListener('DOMContentLoaded', function () {
            cargarResumen();
            crearGraficos(); // Llamar a la función para crear la gráfica
            cargarDatos('/public/prueba2/obtener_pedidos_activos.php', 'pedidosActivosmer');
            cargarDatosHistorial('/public/prueba2/obtener_historial_pedidos.php', 'historialPedidos');
            cargarDatosInve('/public/prueba2/obtener_inventario.php', 'inventarioItems');
            cargarDatosPersonal('/public/prueba2/obtener_personal.php', 'personalItems');
            manejarNavegacion();

            // Manejar el envío de formularios
            handleFormSubmit('editForm', function () {
                cargarDatos('/public/prueba2/obtener_pedidos_activos.php', 'pedidosActivosmer');
            });

            handleFormSubmit('historialForm', function () {
                cargarDatosHistorial('/public/prueba2/obtener_historial_pedidos.php', 'historialPedidos');
            });
        });

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
        }

        // Función para eliminar un pedido
        function eliminarPedido(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este pedido?')) {
                fetch(`/public/prueba2/eliminar_pedido.php?id=${id}`, {
                    method: 'DELETE'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Pedido eliminado con éxito');
                            cargarDatos('/public/prueba2/obtener_pedidos_activos.php', 'pedidosActivosmer');
                        } else {
                            alert('Error al eliminar el pedido');
                        }
                    })
                    .catch(error => console.error('Error al eliminar el pedido:', error));
            }
        }

        // Función para formatear fechas
        function formatDateToDatetimeLocal(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        // Función para manejar la navegación
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
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/public/prueba2/obtener_categorias.php')
                .then(response => response.json())
                .then(data => {
                    const categoriaSelect = document.getElementById('inventarioCategoria');
                    data.forEach(categoria => {
                        const option = document.createElement('option');
                        option.value = categoria.idCategoria;
                        option.textContent = categoria.nombre;
                        categoriaSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error al cargar categorías:', error));
        });
   
        function handleFormSubmit(event, modalId) {
        event.preventDefault(); // Evitar el envío del formulario por defecto
    
        const formData = new FormData(event.target);
        fetch(event.target.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Operación realizada correctamente.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    closeModal(modalId);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al realizar la operación: ' + data.error,
                });
            }
        })
        .catch(error => {
            console.error('Error al enviar el formulario:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al enviar el formulario.',
            });
        });
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

document.getElementById('personalForm').addEventListener('submit', function(event) {
    handleFormSubmit(event, 'personalModal');
});

document.getElementById('editForm').addEventListener('submit', function(event) {
    handleFormSubmit(event, 'editModal');
});

document.getElementById('historialForm').addEventListener('submit', function(event) {
    handleFormSubmit(event, 'historialModal');
});

document.getElementById('inventarioForm').addEventListener('submit', function(event) {
    handleFormSubmit(event, 'inventarioModal');
});


   </script>
</body>

</html>