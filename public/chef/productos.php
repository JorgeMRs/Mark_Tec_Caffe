<?php
session_start();

if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'Chef') {
    header('Location: /public/error/403.html');
    exit();
}

include '../../src/db/db_connect.php';

$conn = getDbConnection();
if (!$conn) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

// Manejo de la adición de categorías

// Obtener productos y categorías con búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modificar consulta para productos activos
$queryProductos = "SELECT p.idProducto, p.nombre, p.precio, p.stock, p.descripcion, p.imagen, c.nombre AS categoria, p.estadoActivacion
                   FROM producto p
                   LEFT JOIN categoria c ON p.idCategoria = c.idCategoria
                   WHERE p.nombre LIKE ?";

$stmtProductos = $conn->prepare($queryProductos);
$searchTerm = "%$search%";
$stmtProductos->bind_param('s', $searchTerm);
$stmtProductos->execute();
$resultProductos = $stmtProductos->get_result();

// Modificar consulta para categorías activas
$queryCategorias = "SELECT idCategoria, nombre, imagen, estadoActivacion FROM categoria";
$resultCategorias = $conn->query($queryCategorias);
if (!$resultCategorias) {
    die('Error al obtener categorías: ' . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Café Sabrosos</title>
    <link rel="stylesheet" href="productos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>

</style>

<body>
    <a href="chef.php" class="back-button" onclick="window.history.back();">Volver a la Página Principal</a>
    <div class="container">
        <div class="left-side">
            <!-- Formulario para agregar nuevas categorías -->
            <h3>Agregar Nueva Categoría</h3>
            <form id="addCategoryForm" enctype="multipart/form-data">
                <input type="text" name="nombreCategoria" placeholder="Nombre de la categoría" required>
                <input type="file" name="categoriaImagen" accept="image/*" required>
                <button type="submit">Agregar Categoría</button>
            </form>
            <div id="serverResponse"></div>
            <div id="updateCategoryForm" style="display: none;">
                <h3>Actualizar Categoría</h3>
                <form id="updateCategoryDetailsForm" enctype="multipart/form-data">
                    <input type="hidden" name="idCategoria" id="categoryId">
                    <label for="nombreCategoria">Nombre de la Categoría</label>
                    <input type="text" name="nombreCategoria" id="nombreCategoria" required>

                    <label for="categoriaImagen">Imagen</label>
                    <input type="file" name="categoriaImagen" id="categoriaImagen" accept="image/*">

                    <button type="submit">Actualizar Categoría</button>
                </form>
                <div id="updateCategoryResponse"></div>
            </div>
            <!-- Formulario para agregar nuevos productos -->
            <h3>Agregar Nuevo Producto</h3>
            <form id="addProductForm" enctype="multipart/form-data">
                <input type="text" name="nombreProducto" placeholder="Nombre del producto" required>
                <input type="text" name="descripcionProducto" placeholder="Descripción del producto" required>
                <input type="number" step="0.01" name="precioProducto" placeholder="Precio" required>
                <input type="number" name="stockProducto" placeholder="Stock" required>
                <input type="file" name="imagenProducto" accept="image/*" required>
                <select name="categoriaProducto" required>
                    <option value="">Seleccione una categoría</option>
                    <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                        <option value="<?php echo $categoria['idCategoria']; ?>"><?php echo $categoria['nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Agregar Producto</button>
                <div id="serverResponse"></div>
            </form>
            <?php
            // Rehacer la consulta para categorías antes del formulario de actualización de productos
            $resultCategorias->data_seek(0); // Restablece el puntero del resultado

            // Código para el formulario de actualización de productos
            ?>
            <!-- Imagen del producto seleccionado -->
            <img id="productImage" class="product-image" src="" alt="Imagen del producto" style="display: none;">
            <div id="updateImageForm" style="display: none;">
                <h3>Actualizar Imagen del Producto</h3>
                <form id="updateProductImageForm" enctype="multipart/form-data">
                    <input type="hidden" name="idProducto" id="productId">
                    <input type="file" name="nuevaImagen" accept="image/*" required>
                    <button type="submit">Actualizar Imagen</button>
                </form>
                <div id="updateImageResponse"></div>
            </div>

            <!-- Formulario para actualizar detalles del producto -->
            <div id="updateProductForm" style="display: none;">
                <h3>Actualizar Detalles del Producto</h3>
                <form id="updateProductDetailsForm">
                    <input type="hidden" name="idProducto" id="updateProductId">
                    <label for="nombreProducto">Nombre del Producto</label>
                    <input type="text" name="nombre" id="nombreProducto" required>

                    <label for="descripcionProducto">Descripción</label>
                    <textarea name="descripcion" id="descripcionProducto" required></textarea>

                    <label for="precioProducto">Precio (€)</label>
                    <input type="number" name="precio" id="precioProducto" step="0.01" required>

                    <label for="categoriaProducto">Categoría</label>
                    <select name="categoria" id="categoriaProducto">
                        <option value="">Seleccione una categoría</option>
                        <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($categoria['idCategoria']); ?>"><?php echo htmlspecialchars($categoria['nombre']); ?></option>
                        <?php endwhile; ?>
                    </select>

                    <button type="submit">Actualizar Producto</button>
                </form>
                <div id="updateProductResponse"></div>
            </div>
        </div>
        <div class="right-side">
            <div class="button-group">
                <button id="showCategoriesBtn">Mostrar Categorías</button>
                <button id="showProductsBtn">Mostrar Productos</button>
            </div>
            <!-- Formulario de búsqueda de productos -->
            <h3>Buscar Productos</h3>
            <form id="searchForm" onsubmit="return false;">
                <input type="text" name="search" id="searchInput" placeholder="Buscar productos" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Buscar</button>
            </form>

            <?php
            // Rehacer la consulta para categorías antes del formulario de actualización de productos
            $resultCategorias->data_seek(0); // Restablece el puntero del resultado

            // Código para el formulario de actualización de productos
            ?>
            <div id="categoriesTable">
                <h3>Lista de Categorías</h3>
                <table id="categoryTable">
                    <thead>
                        <tr>
                            <th>Nombre de Categoría</th>
                            <th>Imagen</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                            <tr data-id="<?php echo $categoria['idCategoria']; ?>" data-active="<?php echo $categoria['estadoActivacion']; ?>" data-image="<?php echo htmlspecialchars($categoria['imagen']); ?>" class="<?php echo $categoria['estadoActivacion'] == 0 ? 'desactivado' : ''; ?>">
                                <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                                <td>
                                    <?php if ($categoria['imagen']): ?>
                                        <img src="<?php echo htmlspecialchars($categoria['imagen']); ?>" alt="Imagen de la categoría" style="max-width: 100px;">
                                    <?php else: ?>
                                        Sin imagen
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($categoria['estadoActivacion'] == 0): ?>
                                        <!-- Botón para reactivar categoría -->
                                        <button class="reactivateCategoryButton" data-id="<?php echo $categoria['idCategoria']; ?>" style="border: none; background: none; cursor: pointer;">
                                            <i class="fas fa-redo" title="Reactivar categoría"></i>
                                        </button>
                                    <?php else: ?>
                                        <!-- Botón para eliminar categoría -->
                                        <button class="deleteCategoryButton" data-id="<?php echo $categoria['idCategoria']; ?>" style="border: none; background: none; cursor: pointer;">
                                            <i class="fas fa-trash-alt" title="Eliminar categoría"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>
            </div>

            <!-- Tabla de productos -->
            <div id="productsTable" class="hidden">
                <h3>Lista de Productos</h3>
                <table id="productTable">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Actualizar Stock</th>
                            <th>Eliminar</th> <!-- Nueva columna para el botón de eliminación -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($producto = $resultProductos->fetch_assoc()): ?>
                            <tr data-id="<?php echo $producto['idProducto']; ?>" data-active="<?php echo $producto['estadoActivacion']; ?>" data-image="<?php echo htmlspecialchars($producto['imagen']); ?>" class="<?php echo $producto['estadoActivacion'] == 0 ? 'desactivado' : ''; ?>">
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($producto['precio']); ?>€</td>
                                <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                                <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                                <td>
                                    <form class="updateStockForm" id="updateStockForm_<?php echo $producto['idProducto']; ?>">
                                        <input type="hidden" name="idProducto" value="<?php echo $producto['idProducto']; ?>">
                                        <input type="number" name="nuevoStock" value="<?php echo $producto['stock']; ?>" required>
                                        <button type="submit">Actualizar</button>
                                    </form>
                                </td>
                                <td>
                                    <?php if ($producto['estadoActivacion'] == 0): ?>
                                        <button class="reactivateProductButton" data-id="<?php echo $producto['idProducto']; ?>" style="border: none; background: none; cursor: pointer; display: block;">
                                            <i class="fas fa-redo" title="Reactivar producto"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($producto['estadoActivacion'] != 0): ?>
                                        <button class="deleteProductButton" data-id="<?php echo $producto['idProducto']; ?>" style="border: none; background: none; cursor: pointer;">
                                            <i class="fas fa-trash-alt" title="Eliminar producto"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <div id="confirmationModal" class="hidden">
        <div class="modal-overlay">
            <div class="modal-content">
                <h4 id="modalTitle">¿Qué quieres hacer con este producto?</h4>
                <div class="button-container">
                    <button id="deleteProductButton" class="modal-button delete-button">Eliminar</button>
                    <button id="deactivateProductButton" class="modal-button deactivate-button">Desactivar</button>
                </div>
                <button id="cancelButton" class="modal-button cancel-button">Cancelar</button>
                <div id="serverResponse"></div>
            </div>
        </div>
    </div>

    <!-- Modal de Éxito -->
    <div id="successModal" class="hidden">
        <div class="modal-overlay">
            <div class="modal-content">
                <h4 id="successMessage">Operación exitosa</h4>
                <button id="closeSuccessModal" class="modal-button success-button">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="passwordModal" class="modal-password modal-password-hidden">
        <div class="modal-password-content">
            <h2>Ingrese su contraseña</h2>
            <input type="password" id="passwordInput" placeholder="Contraseña" required />
            <div id="passwordError" class="password-error"></div>
            <button id="confirmPasswordButton" class="password-confirm-btn">Confirmar</button>
            <button id="cancelPasswordButton" class="password-cancel-btn">Cancelar</button>
        </div>
    </div>

    <style>
        /* Estilos para el modal de la contraseña */
        .modal-password {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            /* Fondo oscuro semitransparente */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-password-hidden {
            display: none;
        }

        .modal-password-show {
            display: flex;
        }

        .modal-password-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }

        .modal-password-content h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .modal-password-content input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .modal-password-content button {
            margin: 10px 5px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-password-content button:hover {
            background-color: #0056b3;
        }

        .modal-password-content #passwordError {
            color: red;
            display: none;
            margin-bottom: 10px;
        }
    </style>
    <style>
        #confirmationModal,
        #successModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .desactivado td {
            text-decoration: line-through;
            color: #888;
        }

        #confirmationModal.hidden,
        #successModal.hidden {
            display: none;
        }

        #confirmationModal.show,
        #successModal.show {
            display: flex;
        }

        .modal {
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

        .show {
            display: block;
        }

        .hidden {
            display: none;
        }

        /* Modal title */
        #modalTitle,
        #successMessage {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        /* Button container */
        .button-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
            /* Espacio entre botones */
        }

        /* Buttons */
        .modal-button {
            font-size: 1em;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .delete-button {
            background-color: #e74c3c;
            color: #fff;
        }

        .deactivate-button {
            background-color: #f39c12;
            color: #fff;
        }

        .cancel-button {
            background-color: #3498db;
            color: #fff;
            margin-top: 10px;
            display: block;
            width: 100%;
        }

        .success-button {
            background-color: #2ecc71;
            color: #fff;
            margin-top: 10px;
            display: block;
            width: 100%;
        }

        /* Button hover effects */
        .modal-button:hover {
            opacity: 0.9;
        }

        .modal-button:active {
            transform: scale(0.98);
        }
    </style>
    <script>

    </script>
    <script src="chef.js"></script>
</body>

</html>

<?php
$conn->close();
?>