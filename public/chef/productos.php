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
$queryProductos = "SELECT p.idProducto, p.nombre, p.precio, p.stock, p.descripcion, p.imagen, c.nombre AS categoria
                   FROM producto p
                   LEFT JOIN categoria c ON p.idCategoria = c.idCategoria
                   WHERE p.nombre LIKE ?";


$stmtProductos = $conn->prepare($queryProductos);
$searchTerm = "%$search%";
$stmtProductos->bind_param('s', $searchTerm);
$stmtProductos->execute();
$resultProductos = $stmtProductos->get_result();

$queryCategorias = "SELECT idCategoria, nombre, imagen FROM categoria";
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
    a.back-button {
        position: absolute;
    }

    .hidden {
        display: none;
    }

    .button-group {
        margin-bottom: 20px;
    }

    .button-group button {
        margin-right: 10px;
    }
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
                            <tr data-id="<?php echo $categoria['idCategoria']; ?>" data-image="<?php echo htmlspecialchars($categoria['imagen']); ?>">
                                <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                                <td>
                                    <?php if ($categoria['imagen']): ?>
                                        <img src="<?php echo htmlspecialchars($categoria['imagen']); ?>" alt="Imagen de la categoría" style="max-width: 100px;">
                                    <?php else: ?>
                                        Sin imagen
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="deleteCategoryButton" data-id="<?php echo $categoria['idCategoria']; ?>" style="border: none; background: none; cursor: pointer;">
                                        <i class="fas fa-trash-alt" title="Eliminar categoría"></i>
                                    </button>
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
                            <tr data-id="<?php echo $producto['idProducto']; ?>" data-image="<?php echo htmlspecialchars($producto['imagen']); ?>">
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
                                    <button class="deleteProductButton" data-id="<?php echo $producto['idProducto']; ?>" style="border: none; background: none; cursor: pointer;">
                                        <i class="fas fa-trash-alt" title="Eliminar producto"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('showCategoriesBtn').addEventListener('click', function() {
            document.getElementById('categoriesTable').classList.remove('hidden');
            document.getElementById('productsTable').classList.add('hidden');

            // Ocultar el formulario de actualización de productos y la imagen
            const updateImageForm = document.getElementById('updateImageForm');
            const updateProductForm = document.getElementById('updateProductForm');
            const productImage = document.getElementById('productImage');

            updateImageForm.style.display = 'none';
            updateProductForm.style.display = 'none';
            productImage.style.display = 'none';
        });

        document.getElementById('showProductsBtn').addEventListener('click', function() {
            document.getElementById('productsTable').classList.remove('hidden');
            document.getElementById('categoriesTable').classList.add('hidden');
            // Ocultar el formulario de actualización de productos y la imagen
            const updateImageForm = document.getElementById('updateImageForm');
            const updateProductForm = document.getElementById('updateProductForm');
            const productImage = document.getElementById('productImage');

            updateImageForm.style.display = 'none';
            updateProductForm.style.display = 'none';
            productImage.style.display = 'none';

            // Ocultar el formulario de actualización de categoría si está visible
            const updateCategoryForm = document.getElementById('updateCategoryForm');
            updateCategoryForm.style.display = 'none';
        });
    </script>
    <script src="chef.js"></script>
</body>

</html>

<?php
$conn->close();
?>