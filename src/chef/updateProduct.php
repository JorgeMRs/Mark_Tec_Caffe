<?php
session_start();
include '../db/db_connect.php';

$response = array('success' => false, 'message' => '');

try {
    $conn = getDbConnection();
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos.');
    }

    // Manejo de la adición de productos
    if (isset($_POST['accion']) && $_POST['accion'] === 'agregarProducto') {
        $nombreProducto = $_POST['nombreProducto'];
        $precioProducto = $_POST['precioProducto'];
        $stockProducto = $_POST['stockProducto'];
        $descripcionProducto = $_POST['descripcionProducto'];
        $categoriaProducto = $_POST['categoriaProducto'];

        // Validación de datos
        if (empty($nombreProducto) || empty($precioProducto) || empty($stockProducto) || empty($descripcionProducto) || empty($categoriaProducto)) {
            throw new Exception('Todos los campos son obligatorios.');
        }

        // Manejo de la imagen
        if (!empty($_FILES['imagenProducto']['name'])) {
            $imagenNombre = basename($_FILES['imagenProducto']['name']);
            $categoriaNombre = '';

            // Obtén el nombre de la categoría
            $queryCategoriaNombre = "SELECT nombre FROM categoria WHERE idCategoria = ?";
            $stmtCategoriaNombre = $conn->prepare($queryCategoriaNombre);
            if (!$stmtCategoriaNombre) {
                throw new Exception('Error en la preparación de la consulta para obtener el nombre de la categoría.');
            }
            $stmtCategoriaNombre->bind_param('i', $categoriaProducto);
            $stmtCategoriaNombre->execute();
            $resultCategoriaNombre = $stmtCategoriaNombre->get_result();
            if ($categoria = $resultCategoriaNombre->fetch_assoc()) {
                $categoriaNombre = $categoria['nombre'];
            }
            $stmtCategoriaNombre->close();

            // Ruta para guardar la imagen
            $targetDir = '../../public/assets/img/productos/' . $categoriaNombre . '/';
            if (!file_exists($targetDir)) {
                if (!mkdir($targetDir, 0777, true)) {
                    throw new Exception('Error al crear el directorio para la imagen.');
                }
            }
            $targetFile = $targetDir . $imagenNombre;

            if (move_uploaded_file($_FILES['imagenProducto']['tmp_name'], $targetFile)) {
                $rutaRelativa = '/public/assets/img/productos/' . $categoriaNombre . '/' . $imagenNombre;
                $queryProducto = "INSERT INTO producto (nombre, precio, stock, descripcion, idCategoria, imagen) VALUES (?, ?, ?, ?, ?, ?)";
                $stmtProducto = $conn->prepare($queryProducto);
                if (!$stmtProducto) {
                    throw new Exception('Error en la preparación de la consulta para agregar el producto.');
                }
                $stmtProducto->bind_param('sdisss', $nombreProducto, $precioProducto, $stockProducto, $descripcionProducto, $categoriaProducto, $rutaRelativa);
                $stmtProducto->execute();
                $stmtProducto->close();
                $response['success'] = true;
                $response['message'] = 'Producto agregado exitosamente.';
            } else {
                $response['message'] = 'Error al subir la imagen.';
            }
        }
    }

    // Manejo de actualización de stock
    if (isset($_POST['accion']) && $_POST['accion'] === 'actualizarStock') {
        $idProducto = $_POST['idProducto'];
        $nuevoStock = $_POST['nuevoStock'];

        // Validación de datos
        if (empty($idProducto) || empty($nuevoStock)) {
            throw new Exception('ID del producto y stock son obligatorios.');
        }

        $queryStock = "UPDATE producto SET stock = ? WHERE idProducto = ?";
        $stmtStock = $conn->prepare($queryStock);
        if (!$stmtStock) {
            throw new Exception('Error en la preparación de la consulta para actualizar el stock.');
        }
        $stmtStock->bind_param('ii', $nuevoStock, $idProducto);
        $stmtStock->execute();
        $stmtStock->close();
        $response['success'] = true;
        $response['message'] = 'Stock actualizado exitosamente.';
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'actualizarImagen') {
        $idProducto = $_POST['idProducto'];

        // Validación de datos
        if (empty($idProducto) || empty($_FILES['nuevaImagen']['name'])) {
            throw new Exception('ID del producto y nueva imagen son obligatorios.');
        }

        // Obtener el nombre de la categoría del producto
        $queryCategoria = "SELECT c.nombre AS categoriaNombre, p.imagen AS imagenActual
                           FROM producto p
                           JOIN categoria c ON p.idCategoria = c.idCategoria
                           WHERE p.idProducto = ?";
        $stmtCategoria = $conn->prepare($queryCategoria);
        if (!$stmtCategoria) {
            throw new Exception('Error en la preparación de la consulta para obtener la categoría del producto.');
        }
        $stmtCategoria->bind_param('i', $idProducto);
        $stmtCategoria->execute();
        $resultCategoria = $stmtCategoria->get_result();
        $categoria = $resultCategoria->fetch_assoc();
        $stmtCategoria->close();

        if (!$categoria) {
            throw new Exception('Producto no encontrado.');
        }

        // Usar el nombre de la categoría en la ruta de la imagen
        $categoriaNombre = $categoria['categoriaNombre'];
        $imagenNombre = basename($_FILES['nuevaImagen']['name']);
        $targetDir = '../../public/assets/img/productos/' . $categoriaNombre . '/';

        // Crear el directorio si no existe
        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0777, true)) {
                throw new Exception('Error al crear el directorio para la imagen.');
            }
        }
        $targetFile = $targetDir . $imagenNombre;

        // Eliminar la imagen anterior del servidor (si existe)
        if (!empty($categoria['imagenActual'])) {
            $imagenAnterior = '../../' . $categoria['imagenActual']; // Ruta completa de la imagen anterior
            if (file_exists($imagenAnterior)) {
                unlink($imagenAnterior); // Eliminar la imagen
            }
        }
        
        // Subir la nueva imagen
        if (move_uploaded_file($_FILES['nuevaImagen']['tmp_name'], $targetFile)) {
            // Guardar la ruta relativa de la imagen en la base de datos
            $rutaRelativa = '/public/assets/img/productos/' . $categoriaNombre . '/' . $imagenNombre;
            $queryActualizarImagen = "UPDATE producto SET imagen = ? WHERE idProducto = ?";
            $stmtActualizarImagen = $conn->prepare($queryActualizarImagen);
            if (!$stmtActualizarImagen) {
                throw new Exception('Error en la preparación de la consulta para actualizar la imagen.');
            }
            $stmtActualizarImagen->bind_param('si', $rutaRelativa, $idProducto);
            $stmtActualizarImagen->execute();
            $stmtActualizarImagen->close();

            $response['success'] = true;
            $response['message'] = 'Imagen actualizada exitosamente.';
        } else {
            $response['message'] = 'Error al subir la nueva imagen.';
        }
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'actualizarProducto') {
        $idProducto = $_POST['idProducto'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $categoria = $_POST['categoria'];

        // Validación de datos
        if (empty($idProducto) || empty($nombre) || empty($descripcion) || empty($precio) || empty($categoria)) {
            $response['message'] = 'Todos los campos son obligatorios.';
            echo json_encode($response);
            exit();
        }

        $updateQuery = "UPDATE producto SET nombre = ?, descripcion = ?, precio = ?, idCategoria = ? WHERE idProducto = ?";
        $stmt = $conn->prepare($updateQuery);
        if (!$stmt) {
            $response['message'] = 'Error en la preparación de la consulta para actualizar el producto.';
            echo json_encode($response);
            exit();
        }

        $stmt->bind_param('ssdii', $nombre, $descripcion, $precio, $categoria, $idProducto);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Detalles del producto actualizados exitosamente.';
        } else {
            $response['message'] = 'Error al actualizar los detalles del producto en la base de datos.';
        }

        $stmt->close();
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    $conn->close();
    echo json_encode($response);
}
