<?php
session_start();

// Verificar si la sesión está activa y si el rol del usuario está definido
if (!isset($_SESSION['role'])) {
    // Mostrar un mensaje de error si no hay rol definido
    $mensaje = "Error: No se ha iniciado sesión correctamente.";
} else {
    // Asignar el rol de 'gerente' al usuario para esta sesión de ejemplo
    $_SESSION['role'] = 'admin'; // o 'admin'
    $mensaje = "Bienvenido, " . $_SESSION['role'];
}
?>
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

        /* Estilos para el fondo sombreado */
        .modal-overlay {
            display: none;
            /* Oculto por defecto */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            /* Fondo sombreado */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Estilos para el contenido del modal */
        .modal {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            padding: 20px;
            margin: auto
        }

        .modal-header {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .modal-title {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .btn-cancel {
            background-color: #f0f0f0;
            color: #333;
        }

        .btn-save {
            background-color: #4CAF50;
            color: white;
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



        /* Estilos para el fondo sombreado del modal de personal */
        .personal-modal-overlay {
            display: none;
            /* Oculto por defecto */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            /* Fondo sombreado */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Estilos para el contenido del modal de personal */
        .personal-modal {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 90vw;
            /* Ancho máximo relativo al viewport */
            max-height: 90vh;
            /* Altura máxima relativa al viewport */
            overflow-y: auto;
            /* Desplazamiento vertical si el contenido es demasiado grande */
            padding: 20px;
            margin: auto;
        }

        .personal-modal .modal-header {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .personal-modal .modal-title {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }

        .personal-modal .form-group {
            margin-bottom: 15px;
        }

        .personal-modal label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .personal-modal input,
        .personal-modal select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .personal-modal .actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .personal-modal .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .personal-modal .btn-cancel {
            background-color: #f0f0f0;
            color: #333;
        }

        .personal-modal .btn-save {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <nav class="sidebar">
            <ul>
                <li><a href="#" class="active" data-tab="pedidos">Pedidos</a></li>
                <li><a href="#" data-tab="retroalimentacion">Retroalimentacion</a></li>
                <li><a href="#" data-tab="inventario">Inventario</a></li>
                <li><a href="#" data-tab="personal">Personal</a></li>
                <li><a href="#" data-tab="reservas">Reservas</a></li>
                <li><a href="#" data-tab="categoria">Categoria</a></li>
                <li><a href="#" data-tab="analisis">Analisis</a></li>
            </ul>
        </nav>
        <main class="main-content">
            <h1><?php echo $mensaje; ?></h1>

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
                                <th>Método de Pago</th>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="pedidosActivosmer"></tbody>
                    </table>
                </div>
                <div id="retroalimentacion">
                    <h3>Retroalimentación</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ID Cliente</th>
                                <th>Nivel de Satisfacción</th>
                                <th>Comentario</th>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="retroalimentacionItems"></tbody>
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
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
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
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>CI</th>
                                <th>Puesto</th>
                                <th>Sucursal</th>
                                <th>Fecha de Ingreso</th>
                                <th>Salario</th>
                                <th>Teléfono</th>
                                <th>Fecha de Nacimiento</th>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="personalItems"></tbody>
                    </table>
                </div>
                <div id="reservas">
                    <h3>Reservas</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>ID Cliente</th>
                                <th>ID Mesa</th>
                                <th>Estado</th>
                                <th>ID Empleado</th>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="reservalItems"></tbody>
                    </table>
                </div>
                <div id="categoria">
                    <h3>Categoria</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="categoriaItems"></tbody>
                    </table>
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
        const userRole = '<?php echo $_SESSION['role']; ?>';


        // function mostrarFormulario(modalId, url, formFields) {
        //     openModal(modalId);
        //     fetch(url)
        //         .then(response => response.json())
        //         .then(data => {
        //             console.log('Datos recibidos:', data);
        //             for (const field in formFields) {
        //                 if (formFields.hasOwnProperty(field)) {
        //                     const element = document.getElementById(formFields[field]);
        //                     if (field === 'idHistorial') {
        //                         element.value = data['idPedido'];
        //                     } else if (field === 'fecha') {
        //                         element.value = data['fechaPedido'];
        //                     } else if (field === 'estado') {
        //                         // Asegurarse de que el valor del select coincida con el valor devuelto
        //                         element.value = data[field];
        //                     } else {
        //                         element.value = data[field];
        //                     }
        //                 }
        //             }
        //         })
        //         .catch(error => console.error('Error al cargar datos:', error));
        // }

        function mostrarFormularioinve(modalId, url, campos) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    for (const campo in campos) {
                        document.getElementById(campos[campo]).value = data[campo];
                    }
                    document.getElementById(modalId).style.display = 'block';
                })
                .catch(error => console.error('Error al cargar el formulario:', error));
        }


        function mostrarFormularioPedido(modalId, url, formFields) {
            openModal(modalId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    for (const field in formFields) {
                        if (formFields.hasOwnProperty(field)) {
                            const element = document.getElementById(formFields[field]);
                            if (element) {
                                element.value = data[field];
                            }
                        }
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }


        function mostrarFormularioInventario(modalId, url, formFields) {
            openModal(modalId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    for (const field in formFields) {
                        if (formFields.hasOwnProperty(field)) {
                            const element = document.getElementById(formFields[field]);
                            if (element) {
                                element.value = data[field];
                            }
                        }
                    }
                    // Cargar las categorías y seleccionar la categoría actual del producto
                    cargarCategorias(data.idCategoria);
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


        function mostrarFormularioRetroalimentacion(modalId, url, formFields) {
            openModal(modalId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    for (const field in formFields) {
                        if (formFields.hasOwnProperty(field)) {
                            const element = document.getElementById(formFields[field]);
                            if (element) {
                                element.value = data[field];
                            }
                        }
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }


        function mostrarFormularioReserva(modalId, url, formFields) {
            openModal(modalId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    for (const field in formFields) {
                        if (formFields.hasOwnProperty(field)) {
                            const element = document.getElementById(formFields[field]);
                            if (element) {
                                element.value = data[field];
                            }
                        }
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }


        function mostrarFormularioCategoria(modalId, url, campos) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error al cargar el formulario:', data.error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error,
                        });
                        return;
                    }
                    for (const campo in campos) {
                        document.getElementById(campos[campo]).value = data[campo];
                    }
                    document.getElementById(modalId).style.display = 'block';
                })
                .catch(error => console.error('Error al cargar el formulario:', error));
        }

        // Función para abrir modal
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'block';
        }

        // Función para cerrar modales
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            } else {
                console.error('Modal no encontrado:', modalId);
            }
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






        // Cargar datos al cargar la página pedidos activos     



        function cargarDatos(url, elementId) {
            console.log(userRole)
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById(elementId);
                    tbody.innerHTML = data.map(item => `
                        <tr data-id="${item.idPedido}">
                            <td data-campo="idPedido">${item.idPedido}</td>
                            <td data-campo="fechaPedido">${item.fechaPedido}</td>
                            <td data-campo="clienteNombre">${item.clienteNombre}</td>
                            <td data-campo="empleadoNombre">${item.empleadoNombre}</td>
                            <td data-campo="total">${item.total}</td>
                            <td data-campo="estado">${item.estado}</td>
                            <td data-campo="metodoPago">${item.metodoPago}</td>
                            ${userRole === 'admin' ? `
                            <td>
                                <select class="acciones" data-id="${item.idPedido}">
                                    <option value="">Seleccionar</option>
                                    <option value="modificar">Modificar</option>
                                    <option value="eliminar">Eliminar</option>
                                </select>
                            </td>
                            ` : ''}
                        </tr>
                    `).join('');
                    if (userRole === 'admin') {
                        // Agregar event listeners para los select de acciones
                        document.querySelectorAll('.acciones').forEach(select => {
                            select.addEventListener('change', function () {
                                const id = select.getAttribute('data-id');
                                const action = select.value;
                                if (action === 'modificar') {
                                    mostrarFormularioPedido('editModal', `/public/prueba2/obtener_pedido_por_id.php?id=${id}`, {
                                        idPedido: 'editId',
                                        fechaPedido: 'editFechaPedido',
                                        idCliente: 'editClienteId',
                                        clienteNombre: 'editClienteNombre',
                                        clienteApellido: 'editClienteApellido',
                                        idEmpleado: 'editEmpleadoId',
                                        total: 'editTotal',
                                        estado: 'editEstado',
                                        metodoPago: 'editMetodoPago'
                                    });
                                } else if (action === 'eliminar') {
                                    eliminarPedido(id);
                                }
                                // Reset the select value to default
                                select.value = '';
                            });
                        });
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }


        function cargarDatosRetroalimentacion(url, elementId) {
            console.log(userRole)
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById(elementId);
                    tbody.innerHTML = data.map(item => `
                    <tr data-id="${item.id}">
                        <td data-campo="idRetroalimentacion">${item.id}</td>
                        <td data-campo="clienteNombre">${item.clienteNombre} ${item.clienteApellido}</td>
                        <td data-campo="nivelSatisfaccion">${item.nivelSatisfaccion}</td>
                        <td data-campo="comentario">${item.comentario}</td>
                        ${userRole === 'admin' ? `
                        <td>
                            <select class="acciones" data-id="${item.id}">
                                <option value="">Seleccionar</option>
                                <option value="modificar">Modificar</option>
                                <option value="eliminar">Eliminar</option>
                            </select>
                        </td>
                        ` : ''}
                    </tr>
                `).join('');
                    if (userRole === 'admin') {
                        // Agregar event listeners para los select de acciones
                        document.querySelectorAll('.acciones').forEach(select => {
                            select.addEventListener('change', function () {
                                const id = select.getAttribute('data-id');
                                const action = select.value;
                                if (action === 'modificar') {
                                    mostrarFormularioRetroalimentacion('editRetroalimentacionModal', `/public/prueba2/obtener_retroalimentacion_por_id.php?id=${id}`, {
                                        idRetroalimentacion: 'retroalimentacionId',
                                        idCliente: 'retroalimentacionClienteId',
                                        clienteNombre: 'retroalimentacionClienteNombre',
                                        clienteApellido: 'retroalimentacionClienteApellido',
                                        nivelSatisfaccion: 'retroalimentacionNivelSatisfaccion',
                                        comentario: 'retroalimentacionComentario'
                                    });
                                } else if (action === 'eliminar') {
                                    eliminarRetroalimentacion(id);
                                }
                                // Reset the select value to default
                                select.value = '';
                            });
                        });
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }
        // Función específica para cargar el inventario



        // Función específica para cargar el personal
        function mostrarFormularioPersonal(modalId, url, formFields) {
            openModal(modalId);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    for (const field in formFields) {
                        if (formFields.hasOwnProperty(field)) {
                            const element = document.getElementById(formFields[field]);
                            if (element) {
                                element.value = data[field];
                            }
                        }
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }


        // Función genérica para cargar datos de una URL y mostrarlos en una tabla

        function cargarDatosPersonal(url, elementId) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById(elementId);
                    tbody.innerHTML = data.map(item => `
                    <tr data-id="${item.idEmpleado}">
                        <td data-campo="idEmpleado">${item.idEmpleado}</td>
                        <td data-campo="correo">${item.correo}</td>
                        <td data-campo="nombre">${item.nombre}</td>
                        <td data-campo="apellido">${item.apellido}</td>
                        <td data-campo="ci">${item.ci}</td>
                        <td data-campo="idPuesto">${item.idPuesto}</td>
                        <td data-campo="idSucursal">${item.idSucursal}</td>
                        <td data-campo="fechaIngreso">${item.fechaIngreso}</td>
                        <td data-campo="salario">${item.salario}</td>
                        <td data-campo="tel">${item.tel}</td>
                        <td data-campo="fechaNacimiento">${item.fechaNacimiento}</td>
                        ${userRole === 'admin' ? `
                        <td>
                            <select class="acciones" data-id="${item.idEmpleado}">
                                <option value="">Seleccionar</option>
                                <option value="modificar">Modificar</option>
                                <option value="eliminar">Eliminar</option>
                            </select>
                        </td>
                        ` : ''}
                    </tr>
                `).join('');

                    if (userRole === 'admin') {
                        // Agregar event listeners para los select de acciones
                        document.querySelectorAll('.acciones').forEach(select => {
                            select.addEventListener('change', function () {
                                const id = select.getAttribute('data-id');
                                const action = select.value;
                                if (action === 'modificar') {
                                    mostrarFormularioPersonal('personalModal', `/public/prueba2/obtener_empleado_por_id.php?id=${id}`, {
                                        idEmpleado: 'personalId',
                                        correo: 'personalCorreo',
                                        nombre: 'personalNombre',
                                        apellido: 'personalApellido',
                                        ci: 'personalCI',
                                        idPuesto: 'personalPuesto',
                                        idSucursal: 'personalSucursal',
                                        fechaIngreso: 'personalFechaIngreso',
                                        salario: 'personalSalario',
                                        tel: 'personalTelefono',
                                        fechaNacimiento: 'personalFechaNacimiento'
                                    });
                                } else if (action === 'eliminar') {
                                    eliminarEmpleado(id);
                                }
                                // Reset the select value to default
                                select.value = '';
                            });
                        });
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }


        function cargarDatosInve(url, elementId) {
            console.log(userRole);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById(elementId);
                    tbody.innerHTML = data.map(item => `
                <tr data-id="${item.id}">
                    <td data-campo="idProducto">${item.id}</td>
                    <td data-campo="nombreProducto">${item.item}</td>
                    <td data-campo="cantidad">${item.quantity}</td>
                    <td data-campo="precio">${item.price}</td>
                    <td data-campo="idCategoria">${item.category}</td>
                    ${userRole === 'admin' ? `
                    <td>
                        <select class="acciones" data-id="${item.id}">
                            <option value="">Seleccionar</option>
                            <option value="modificar">Modificar</option>
                            <option value="eliminar">Eliminar</option>
                        </select>
                    </td>
                    ` : ''}
                </tr>
            `).join('');

                    if (userRole === 'admin') {
                        // Agregar event listeners para los select de acciones
                        document.querySelectorAll('.acciones').forEach(select => {
                            select.addEventListener('change', function () {
                                const id = select.getAttribute('data-id');
                                const action = select.value;
                                if (action === 'modificar') {
                                    mostrarFormularioInventario('inventarioModal', `/public/prueba2/obtener_producto_por_id.php?id=${id}`, {
                                        idProducto: 'inventarioId',
                                        nombreProducto: 'inventarioNombre',
                                        cantidad: 'inventarioCantidad',
                                        precio: 'inventarioPrecio',
                                        idCategoria: 'inventarioCategoria'
                                    });
                                } else if (action === 'eliminar') {
                                    eliminarInventario(id);
                                }
                                // Reset the select value to default
                                select.value = '';
                            });
                        });
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }


        function cargarCategorias(selectedCategoryId = null) {
            fetch('/public/prueba2/obtener_categorias.php')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('inventarioCategoria');
                    select.innerHTML = data.map(categoria => `
                    <option value="${categoria.idCategoria}" ${categoria.idCategoria == selectedCategoryId ? 'selected' : ''}>
                        ${categoria.nombre}
                    </option>
                `).join('');
                })
                .catch(error => console.error('Error al cargar categorías:', error));
        }

        function obtenerNombreCategoria(idCategoria) {
            return fetch(`/public/prueba2/obtener_nombre_categoria.php?idCategoria=${idCategoria}`)
                .then(response => response.json())
                .then(data => data.nombre)
                .catch(error => {
                    console.error('Error al obtener el nombre de la categoría:', error);
                    return null;
                });
        }



        function cargarDatosReservas(url, elementId) {
            console.log(userRole);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById(elementId);
                    tbody.innerHTML = data.map(item => `
                    <tr data-id="${item.id}">
                        <td data-campo="id">${item.id}</td>
                        <td data-campo="fecha">${item.fecha}</td>
                        <td data-campo="cliente">${item.cliente}</td>
                        <td data-campo="mesa">${item.mesa}</td>
                        <td data-campo="estado">${item.estado}</td>
                        <td data-campo="empleado">${item.idEmpleado}</td>
                        ${userRole === 'admin' ? `
                        <td>
                            <select class="acciones" data-id="${item.id}">
                                <option value="">Seleccionar</option>
                                <option value="modificar">Modificar</option>
                                <option value="eliminar">Eliminar</option>
                            </select>
                        </td>
                        ` : ''}
                    </tr>
                `).join('');

                    if (userRole === 'admin') {
                        // Agregar event listeners para los select de acciones
                        document.querySelectorAll('.acciones').forEach(select => {
                            select.addEventListener('change', function () {
                                const id = select.getAttribute('data-id');
                                const action = select.value;
                                if (action === 'modificar') {
                                    mostrarFormularioReserva('reservaModal', `/public/prueba2/obtener_reserva_por_id.php?id=${id}`, {
                                        id: 'reservaId',
                                        fecha: 'editFechaReserva',
                                        cliente: 'editClienteReservaId',
                                        mesa: 'editMesaId',
                                        estado: 'editEstadoReserva',
                                        empleado: 'editEmpleadoReservaId'
                                    });
                                } else if (action === 'eliminar') {
                                    eliminarReserva(id);
                                }
                                // Reset the select value to default
                                select.value = '';
                            });
                        });
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }




        function cargarDatosCategorias(url, elementId) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById(elementId);
                    tbody.innerHTML = data.map(item => `
                    <tr data-id="${item.idCategoria}">
                        <td data-campo="idCategoria">${item.idCategoria}</td>
                        <td data-campo="nombre">${item.nombre}</td>
                        ${userRole === 'admin' ? `
                        <td>
                            <select class="acciones" data-id="${item.idCategoria}">
                                <option value="">Seleccionar</option>
                                <option value="modificar">Modificar</option>
                                <option value="eliminar">Eliminar</option>
                            </select>
                        </td>
                        ` : ''}
                    </tr>
                `).join('');

                    if (userRole === 'admin') {
                        // Agregar event listeners para los select de acciones
                        document.querySelectorAll('.acciones').forEach(select => {
                            select.addEventListener('change', function () {
                                const id = select.getAttribute('data-id');
                                const action = select.value;
                                if (action === 'modificar') {
                                    mostrarFormularioCategoria('categoriaModal', `/public/prueba2/obtener_categoria_por_id.php?id=${id}`, {
                                        idCategoria: 'categoriaId',
                                        nombre: 'editNombreCategoria'
                                    });
                                } else if (action === 'eliminar') {
                                    eliminarCategoria(id);
                                }
                                // Reset the select value to default
                                select.value = '';
                            });
                        });
                    }
                })
                .catch(error => console.error('Error al cargar datos:', error));
        }



        function eliminarCategoria(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta categoría?')) {
                fetch(`/public/prueba2/eliminar_categoria.php?id=${id}`, {
                    method: 'DELETE'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Categoría eliminada correctamente');
                            cargarDatosCategorias('/public/prueba2/obtener_categorias.php', 'categoriaItems');
                        } else {
                            alert('Error al eliminar la categoría');
                        }
                    })
                    .catch(error => console.error('Error al eliminar la categoría:', error));
            }
        }







        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }




        function eliminarReserva(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
                fetch(`/public/prueba2/eliminar_reserva.php?id=${id}`, {
                    method: 'DELETE'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Reserva eliminada correctamente');
                            cargarDatosReservas('/public/prueba2/obtener_reservas.php', 'reservalItems');
                        } else {
                            alert('Error al eliminar la reserva');
                        }
                    })
                    .catch(error => console.error('Error al eliminar la reserva:', error));
            }
        }

        function eliminarEmpleado(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este empleado?')) {
                fetch(`/public/prueba2/eliminar_empleado.php?id=${id}`, {
                    method: 'DELETE'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Empleado eliminado correctamente');
                            cargarDatosPersonal('/public/prueba2/obtener_empleados.php', 'tablaPersonal');
                        } else {
                            alert('Error al eliminar el empleado');
                        }
                    })
                    .catch(error => console.error('Error al eliminar el empleado:', error));
            }
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
            cargarDatosRetroalimentacion('/public/prueba2/obtener_retroalimentacion.php', 'retroalimentacionItems');
            cargarDatosInve('/public/prueba2/obtener_inventario.php', 'inventarioItems');
            cargarDatosPersonal('/public/prueba2/obtener_personal.php', 'personalItems');
            cargarDatosReservas('/public/prueba2/obtener_reserva.php', 'reservalItems');
            cargarDatosCategorias('/public/prueba2/obtener_categorias.php', 'categoriaItems');

            manejarNavegacion();

            // Manejar el envío de formularios
            handleFormSubmitPedidos('editForm', function () {
                cargarDatos('/public/prueba2/obtener_pedidos_activos.php', 'pedidosActivosmer');
            });

            // handleFormSubmit('historialForm', function () {
            //     cargarDatosRetroalimentacion('/public/prueba2/obtener_historial_pedidos.php', 'retroalimentacionItems');
            // });
        });

        // Función para crear gráficos
        function crearGraficos() {
            // Gráfico de ventas semanales
            fetch('/public/panel/obtener_ventas_semanales.php')
                .then(response => response.json())
                .then(data => {
                    // Definir las semanas del mes
                    const semanasMes = ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'];

                    // Mapeo de las ventas para las 4 semanas del mes
                    const ventasPorSemana = semanasMes.map((semana, index) => {
                        const venta = data.find(item => item.semanaMes == index + 1); // Semana 1 corresponde al index 0
                        return venta ? venta.ventas : 0;
                    });

                    // Configuración del gráfico
                    const ctxVentas = document.getElementById('ventasSemanales').getContext('2d');
                    new Chart(ctxVentas, {
                        type: 'bar',
                        data: {
                            labels: semanasMes,
                            datasets: [{
                                label: 'Ventas por Semana del Mes',
                                data: ventasPorSemana,
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
                                    text: 'Ventas Semanales del Mes Actual'
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



        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        document.getElementById('personalForm').addEventListener('submit', function (event) {
            handleFormSubmitPersonal(event, 'personalModal');
        });
        document.getElementById('reservaForm').addEventListener('submit', function (event) {
            handleFormSubmitReserva(event, 'reservaModal');
        });

        document.getElementById('editForm').addEventListener('submit', function (event) {
            handleFormSubmitPedidos(event, 'editModal');
        });

        // document.getElementById('historialForm').addEventListener('submit', function (event) {
        //     handleFormSubmit(event, 'historialModal');
        // });

        document.getElementById('inventarioForm').addEventListener('submit', function (event) {
            handleFormSubmitInventario(event, 'inventarioModal');
        });

        document.getElementById('categoriaForm').addEventListener('submit', function (event) {
            handleFormSubmitCategoria(event, 'categoriaModal');
        });



        document.getElementById('weekSelect').addEventListener('change', function () {
            const week = this.value;
            actualizarGraficoVentasSemanales(week);
        });

        function actualizarGraficoVentasSemanales(week) {
            fetch(`/public/prueba2/obtener_ventas_semanales.php?week=${week}`)
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
        }

        // Inicializar la gráfica con la última semana
        document.addEventListener('DOMContentLoaded', function () {
            actualizarGraficoVentasSemanales(0);
        });





        function handleFormSubmitPedidos(event, modalId) {
            event.preventDefault(); // Evitar el envío del formulario por defecto

            const formData = new FormData(event.target);
            const id = formData.get('idPedido'); // Obtener el ID del pedido

            fetch(event.target.action, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta del servidor:', data); // Mensaje de depuración
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            closeModal(modalId);
                            const camposModificados = data.camposModificados;
                            for (const campo in camposModificados) {
                                if (camposModificados.hasOwnProperty(campo)) {
                                    actualizarCeldaPedidos(id, campo, camposModificados[campo]);
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al realizar la operación Pedidos: ' + (data.error || 'Error desconocido'),
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al enviar el formulario:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al enviar el formulario.',
                    });
                });
        }

        function handleFormSubmitRetroalimentacion(event, modalId) {
            event.preventDefault(); // Evitar el envío del formulario por defecto

            const formData = new FormData(event.target);
            const id = formData.get('idRetroalimentacion'); // Obtener el ID de la retroalimentación

            fetch(event.target.action, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta del servidor:', data); // Mensaje de depuración
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            closeModal(modalId);
                            const camposModificados = data.camposModificados;
                            for (const campo in camposModificados) {
                                if (camposModificados.hasOwnProperty(campo)) {
                                    actualizarCeldaRetroalimentacion(id, campo, camposModificados[campo]);
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al realizar la operación Retroalimentación: ' + (data.error || 'Error desconocido'),
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al enviar el formulario:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al enviar el formulario.',
                    });
                });
        }






        function handleFormSubmitInventario(event, modalId) {
            event.preventDefault(); // Evitar el envío del formulario por defecto

            const formData = new FormData(event.target);
            const id = formData.get('idProducto'); // Obtener el ID del producto

            fetch(event.target.action, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta del servidor:', data); // Mensaje de depuración
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            closeModal(modalId);
                            const camposModificados = data.camposModificados;
                            for (const campo in camposModificados) {
                                if (camposModificados.hasOwnProperty(campo)) {
                                    actualizarCeldaInventario(id, campo, camposModificados[campo]);
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al realizar la operación Inventario: ' + (data.error || 'Error desconocido'),
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al enviar el formulario:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al enviar el formulario.',
                    });
                });
        }

        function handleFormSubmitPersonal(event, modalId) {
            event.preventDefault(); // Evitar el envío del formulario por defecto

            const formData = new FormData(event.target);
            const id = formData.get('idEmpleado'); // Obtener el ID del personal

            fetch(event.target.action, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta del servidor:', data); // Mensaje de depuración
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            closeModal(modalId);
                            const camposModificados = data.camposModificados;
                            for (const campo in camposModificados) {
                                if (camposModificados.hasOwnProperty(campo)) {
                                    actualizarCeldaPersonal(id, campo, camposModificados[campo]);
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al realizar la operación Personal: ' + (data.error || 'Error desconocido'),
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al enviar el formulario:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al enviar el formulario.',
                    });
                });
        }


        function handleFormSubmitReserva(event, modalId) {
            event.preventDefault(); // Evitar el envío del formulario por defecto

            const formData = new FormData(event.target);
            const id = formData.get('idReserva'); // Obtener el ID de la reserva

            fetch(event.target.action, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta del servidor:', data); // Mensaje de depuración
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            closeModal(modalId);
                            const camposModificados = data.camposModificados;
                            for (const campo in camposModificados) {
                                if (camposModificados.hasOwnProperty(campo)) {
                                    actualizarCeldaReservas(id, campo, camposModificados[campo]);
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al realizar la operación Reserva: ' + (data.error || 'Error desconocido'),
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al enviar el formulario:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al enviar el formulario.',
                    });
                });
        }

        function handleFormSubmitCategoria(event, modalId) {
            event.preventDefault(); // Evitar el envío del formulario por defecto

            const formData = new FormData(event.target);
            fetch(event.target.action, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta del servidor:', data); // Mensaje de depuración
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            closeModal(modalId);
                            actualizarCeldaCategoria(data.id, data.campoModificado, data.valorModificado);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al realizar la operación Categoría: ' + (data.error || 'Error desconocido'),
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al enviar el formulario:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al enviar el formulario.',
                    });
                });
        }


      


        function actualizarCeldaPedidos(id, campoModificado, valorModificado) {
            console.log('Actualizando celda en pedidos:', id, campoModificado, valorModificado); // Mensaje de depuración
            const fila = document.querySelector(`#pedidosActivosmer tr[data-id="${id}"]`);
            if (fila) {
                const celda = fila.querySelector(`td[data-campo="${campoModificado}"]`);
                if (celda) {
                    celda.textContent = valorModificado;
                } else {
                    console.error('Celda no encontrada en pedidos:', campoModificado); // Mensaje de depuración
                }
            } else {
                console.error('Fila no encontrada en pedidos:', id); // Mensaje de depuración
            }
        }

        function actualizarCeldaRetroalimentacion(id, campoModificado, valorModificado) {
            console.log('Actualizando celda en retroalimentación:', id, campoModificado, valorModificado); // Mensaje de depuración
            const fila = document.querySelector(`#retroalimentacionItems tr[data-id="${id}"]`);
            if (fila) {
                const celda = fila.querySelector(`td[data-campo="${campoModificado}"]`);
                if (celda) {
                    celda.textContent = valorModificado;
                } else {
                    console.error('Celda no encontrada en retroalimentación:', campoModificado); // Mensaje de depuración
                }
            } else {
                console.error('Fila no encontrada en retroalimentación:', id); // Mensaje de depuración
            }
        }
        function actualizarCeldaReservas(id, campoModificado, valorModificado) {
            console.log('Actualizando celda en reservas:', id, campoModificado, valorModificado); // Mensaje de depuración
            const fila = document.querySelector(`#reservalItems tr[data-id="${id}"]`);
            if (fila) {
                const celda = fila.querySelector(`td[data-campo="${campoModificado}"]`);
                if (celda) {
                    if (campoModificado === 'fecha') {
                        celda.textContent = new Date(valorModificado).toLocaleString(); // Formatear la fecha
                    } else {
                        celda.textContent = valorModificado;
                    }
                } else {
                    console.error('Celda no encontrada en reservas:', campoModificado); // Mensaje de depuración
                }
            } else {
                console.error('Fila no encontrada en reservas:', id); // Mensaje de depuración
            }
        }

        function actualizarCeldaInventario(id, campoModificado, valorModificado) {
            console.log('Actualizando celda en inventario:', id, campoModificado, valorModificado); // Mensaje de depuración
            const fila = document.querySelector(`#inventarioItems tr[data-id="${id}"]`);
            if (fila) {
                const celda = fila.querySelector(`td[data-campo="${campoModificado}"]`);
                if (celda) {
                    if (campoModificado === 'idCategoria') {
                        obtenerNombreCategoria(valorModificado).then(nombreCategoria => {
                            if (nombreCategoria) {
                                celda.textContent = nombreCategoria;
                            }
                        });
                    } else {
                        celda.textContent = valorModificado;
                    }
                } else {
                    console.error('Celda no encontrada en inventario:', campoModificado); // Mensaje de depuración
                }
            } else {
                console.error('Fila no encontrada en inventario:', id); // Mensaje de depuración
            }
        }



        function actualizarCeldaPersonal(id, campoModificado, valorModificado) {
            console.log('Actualizando celda en personal:', id, campoModificado, valorModificado); // Mensaje de depuración
            const fila = document.querySelector(`#personalItems tr[data-id="${id}"]`);
            if (fila) {
                const celda = fila.querySelector(`td[data-campo="${campoModificado}"]`);
                if (celda) {
                    celda.textContent = valorModificado;
                } else {
                    console.error('Celda no encontrada en personal:', campoModificado); // Mensaje de depuración
                }
            } else {
                console.error('Fila no encontrada en personal:', id); // Mensaje de depuración
            }
        }


        function actualizarCeldaCategoria(id, campoModificado, valorModificado) {
            console.log('Actualizando celda en categoría:', id, campoModificado, valorModificado); // Mensaje de depuración
            const fila = document.querySelector(`#categoriaItems tr[data-id="${id}"]`);
            if (fila) {
                const celda = fila.querySelector(`td[data-campo="${campoModificado}"]`);
                if (celda) {
                    celda.textContent = valorModificado;
                } else {
                    console.error('Celda no encontrada en categoría:', campoModificado); // Mensaje de depuración
                }
            } else {
                console.error('Fila no encontrada en categoría:', id); // Mensaje de depuración
            }
        }

    </script>
</body>

</html>