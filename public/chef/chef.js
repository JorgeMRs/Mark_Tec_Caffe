document.addEventListener('DOMContentLoaded', () => {
    // Manejo del formulario de agregar categoría
    document.getElementById('addCategoryForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('accion', 'agregarCategoria');

        try {
            const response = await fetch('/src/chef/addCategory.php', {
                method: 'POST',
                body: formData
            });

            // Verifica si la respuesta es válida
            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.statusText);
            }

            const data = await response.json();

            // Muestra el mensaje del servidor
            const messageDiv = document.getElementById('serverResponse');
            if (data.success) {
                messageDiv.textContent = 'Categoría agregada exitosamente.';
                messageDiv.style.color = 'green';

                // Recarga la página después de agregar la categoría
                setTimeout(() => {
                    location.reload();
                }, 2000); // Espera 2 segundos para mostrar el mensaje antes de recargar
            } else {
                messageDiv.textContent = data.message || 'Error al agregar la categoría.';
                messageDiv.style.color = 'red';
            }

            // Vacía los campos del formulario
            document.getElementById('addCategoryForm').reset();
        } catch (error) {
            // Maneja errores de red u otros errores
            const messageDiv = document.getElementById('serverResponse');
            messageDiv.textContent = 'Error en la solicitud: ' + error.message;
            messageDiv.style.color = 'red';
        }
    });

    // Manejo del formulario de agregar producto
    document.getElementById('addProductForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('accion', 'agregarProducto');

        try {
            const response = await fetch('/src/chef/updateProduct.php', {
                method: 'POST',
                body: formData
            });

            // Verifica si la respuesta es válida
            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.statusText);
            }

            const data = await response.json();

            // Muestra el mensaje del servidor
            const messageDiv = document.getElementById('serverResponse');
            if (data.success) {
                messageDiv.textContent = 'Producto agregado exitosamente.';
                messageDiv.style.color = 'green';

                // Recarga la página después de agregar el producto
                setTimeout(() => {
                    location.reload();
                }, 5000); // Espera 1 segundo para mostrar el mensaje antes de recargar
            } else {
                messageDiv.textContent = data.message || 'Error al agregar el producto.';
                messageDiv.style.color = 'red';
            }

            // Vacía los campos del formulario
            document.getElementById('addProductForm').reset();
            document.querySelector('input[type="file"]').value = ''; // Limpia el campo de archivo
        } catch (error) {
            // Maneja errores de red u otros errores
            const messageDiv = document.getElementById('serverResponse');
            messageDiv.textContent = 'Error en la solicitud: ' + error.message;
            messageDiv.style.color = 'red';
        }
    });

    // Manejo del formulario de actualización de stock
    document.querySelectorAll('.updateStockForm').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('accion', 'actualizarStock');

            try {
                const response = await fetch('/src/chef/updateProduct.php', {
                    method: 'POST',
                    body: formData
                });

                // Verifica si la respuesta es válida
                if (!response.ok) {
                    throw new Error('Error en la solicitud: ' + response.statusText);
                }

                const data = await response.json();

                // Muestra el mensaje del servidor
                const messageDiv = document.getElementById('serverResponse');
                if (data.success) {
                    messageDiv.textContent = 'Stock actualizado exitosamente.';
                    messageDiv.style.color = 'green';

                    // Actualiza el valor en el campo del formulario
                    const stockInput = e.target.querySelector('input[name="nuevoStock"]');
                    stockInput.value = formData.get('nuevoStock');
                } else {
                    messageDiv.textContent = data.message || 'Error al actualizar el stock.';
                    messageDiv.style.color = 'red';
                }
            } catch (error) {
                // Maneja errores de red u otros errores
                const messageDiv = document.getElementById('serverResponse');
                messageDiv.textContent = 'Error en la solicitud: ' + error.message;
                messageDiv.style.color = 'red';
            }
        });
    });

    document.querySelectorAll('.deleteProductButton').forEach(button => {
        button.addEventListener('click', async () => {
            const productId = button.getAttribute('data-id');

            if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
                const formData = new FormData();
                formData.append('accion', 'eliminarProducto');
                formData.append('idProducto', productId);

                try {
                    const response = await fetch('/src/chef/deleteProduct.php', {
                        method: 'POST',
                        body: formData
                    });

                    // Verifica si la respuesta es válida
                    if (!response.ok) {
                        throw new Error('Error en la solicitud: ' + response.statusText);
                    }

                    const data = await response.json();

                    // Muestra el mensaje del servidor
                    const messageDiv = document.getElementById('serverResponse');
                    if (data.success) {
                        messageDiv.textContent = 'Producto eliminado exitosamente.';
                        messageDiv.style.color = 'green';

                        // Elimina la fila de la tabla
                        button.closest('tr').remove();
                    } else {
                        messageDiv.textContent = data.message || 'Error al eliminar el producto.';
                        messageDiv.style.color = 'red';
                    }
                } catch (error) {
                    // Maneja errores de red u otros errores
                    const messageDiv = document.getElementById('serverResponse');
                    messageDiv.textContent = 'Error en la solicitud: ' + error.message;
                    messageDiv.style.color = 'red';
                }
            }
        });
    });

    // Manejo de la selección de producto y desplazar suavemente
    document.querySelectorAll('#productTable tbody tr').forEach(row => {
        row.addEventListener('click', (e) => {
            if (!e.target.closest('.deleteProductButton') && !e.target.closest('.updateStockForm')) {
                // Quitar la selección previa y agregar la clase 'selected' a la fila actual
                document.querySelectorAll('#productTable tbody tr').forEach(r => r.classList.remove('selected'));
                row.classList.add('selected');

                // Obtener los datos del producto desde los atributos de la fila
                const idProducto = row.getAttribute('data-id');
                const imgSrc = row.getAttribute('data-image');
                const nombreProducto = row.children[0].textContent;
                const descripcionProducto = row.children[1].textContent;
                const precioProducto = row.children[2].textContent.replace('€', '');
                const categoriaNombre = row.children[4].textContent;

                // Obtener el elemento del select
                const categoriaSelect = document.getElementById('categoriaProducto');
                const categoriaOption = Array.from(categoriaSelect.options).find(option => option.textContent === categoriaNombre);

                // Verifica que la opción existe antes de acceder a su valor
                const categoriaId = categoriaOption ? categoriaOption.value : '';

                // Actualizar y mostrar la imagen del producto
                const imgElement = document.getElementById('productImage');
                imgElement.src = imgSrc;
                imgElement.style.display = 'block';

                // Mostrar formulario de actualización de imagen
                const updateImageForm = document.getElementById('updateImageForm');
                const productIdInput = document.getElementById('productId');
                productIdInput.value = idProducto;
                updateImageForm.style.display = 'block';

                // Mostrar formulario de actualización de detalles del producto
                const updateProductForm = document.getElementById('updateProductForm');
                const updateProductIdInput = document.getElementById('updateProductId');
                const nombreProductoInput = document.getElementById('nombreProducto');
                const descripcionProductoInput = document.getElementById('descripcionProducto');
                const precioProductoInput = document.getElementById('precioProducto');
                const categoriaProductoSelect = document.getElementById('categoriaProducto');

                // Rellenar el formulario con los detalles del producto
                updateProductIdInput.value = idProducto;
                nombreProductoInput.value = nombreProducto;
                descripcionProductoInput.value = descripcionProducto;
                precioProductoInput.value = parseFloat(precioProducto).toFixed(2);
                categoriaProductoSelect.value = categoriaId; // Establecer la categoría seleccionada

                updateProductForm.style.display = 'block';

                // Desplazarse suavemente a la imagen del producto
                const imgRect = imgElement.getBoundingClientRect();
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                window.scrollTo({
                    top: imgRect.top + scrollTop - 300,
                    behavior: 'smooth'
                });
            }
        });
    });

    document.querySelectorAll('#categoryTable tbody tr').forEach(row => {
        row.addEventListener('click', (e) => {
            // Verifica si se ha hecho clic en un botón dentro de la fila
            if (!e.target.closest('.updateCategoryButton')) {
                // Quitar la clase 'selected' de cualquier fila previamente seleccionada
                document.querySelectorAll('#categoryTable tbody tr').forEach(r => r.classList.remove('selected'));
    
                // Agregar la clase 'selected' a la fila clicada
                row.classList.add('selected');
    
                // Obtener los datos de la categoría desde los atributos de la fila
                const idCategoria = row.getAttribute('data-id');
                const nombreCategoria = row.children[0].textContent;
                const imgSrc = row.getAttribute('data-image');
    
                // Mostrar formulario de actualización de categoría
                const updateCategoryForm = document.getElementById('updateCategoryForm');
                const categoryIdInput = document.getElementById('categoryId');
                const nombreCategoriaInput = document.getElementById('nombreCategoria');
                const categoriaImagenInput = document.getElementById('categoriaImagen');
                const imgElement = document.createElement('img');
    
                // Rellenar el formulario con los detalles de la categoría
                categoryIdInput.value = idCategoria;
                nombreCategoriaInput.value = nombreCategoria;
                categoriaImagenInput.value = ''; // Limpiar el campo de archivo
    
                // Mostrar la imagen actual si existe
                imgElement.src = imgSrc;
                imgElement.alt = 'Imagen de la categoría';
                imgElement.style.maxWidth = '200px'; // Ajusta el tamaño según sea necesario
    
                const imageContainer = updateCategoryForm.querySelector('img');
                if (imageContainer) {
                    imageContainer.remove();
                }
                updateCategoryForm.appendChild(imgElement);
    
                updateCategoryForm.style.display = 'block';
            }
        });
    });

    // Función para filtrar productos
    document.getElementById('searchInput').addEventListener('input', () => {
        filterProducts();
    });

    function filterProducts() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#productTable tbody tr');

        rows.forEach(row => {
            const productName = row.querySelector('td:first-child').textContent.toLowerCase();
            if (productName.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Manejo del formulario de actualización de imagen
    document.getElementById('updateProductImageForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('accion', 'actualizarImagen');

        try {
            const response = await fetch('/src/chef/updateProduct.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.statusText);
            }

            const data = await response.json();
            const messageDiv = document.getElementById('updateImageResponse');
            const imgElement = document.getElementById('productImage');

            if (data.success) {
                // Actualiza la imagen en la tabla
                const productId = formData.get('idProducto');
                const newImage = URL.createObjectURL(formData.get('nuevaImagen'));

                document.querySelector(`#productTable tbody tr[data-id="${productId}"]`).setAttribute('data-image', newImage);
                imgElement.src = newImage;

                // Muestra el mensaje de éxito
                messageDiv.textContent = 'Imagen actualizada exitosamente.';
                messageDiv.style.color = 'green';

                // Muestra el formulario y la imagen
                document.getElementById('updateImageForm').style.display = 'block';
                imgElement.style.display = 'block';
            } else {
                messageDiv.textContent = data.message || 'Error al actualizar la imagen.';
                messageDiv.style.color = 'red';
            }

            // Limpia el mensaje después de un tiempo
            setTimeout(() => {
                messageDiv.textContent = '';
            }, 5000); // Limpia el mensaje después de 3 segundos
        } catch (error) {
            const messageDiv = document.getElementById('updateImageResponse');
            messageDiv.textContent = 'Error en la solicitud: ' + error.message;
            messageDiv.style.color = 'red';
        }
    });

    document.getElementById('updateProductDetailsForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('accion', 'actualizarProducto');

        try {
            const response = await fetch('/src/chef/updateProduct.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.statusText);
            }

            const data = await response.json();
            const messageDiv = document.getElementById('updateProductResponse');

            if (data.success) {
                messageDiv.textContent = 'Detalles del producto actualizados exitosamente.';
                messageDiv.style.color = 'green';
            } else {
                messageDiv.textContent = data.message || 'Error al actualizar los detalles del producto.';
                messageDiv.style.color = 'red';
            }

            // Limpia el mensaje después de un tiempo
            setTimeout(() => {
                messageDiv.textContent = '';
            }, 5000); // Limpia el mensaje después de 3 segundos
        } catch (error) {
            const messageDiv = document.getElementById('updateProductResponse');
            messageDiv.textContent = 'Error en la solicitud: ' + error.message;
            messageDiv.style.color = 'red';
        }
    });

    document.getElementById('updateCategoryDetailsForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('accion', 'actualizarCategoria');
    
        try {
            const response = await fetch('/src/chef/updateCategory.php', {
                method: 'POST',
                body: formData
            });
    
            // Verifica si la respuesta es válida
            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.statusText);
            }
    
            const data = await response.json();
    
            // Muestra el mensaje del servidor
            const messageDiv = document.getElementById('updateCategoryResponse');
            if (data.success) {
                messageDiv.textContent = 'Categoría actualizada exitosamente.';
                messageDiv.style.color = 'green';
    
                // Actualizar la imagen en la tabla solo si hay una nueva imagen
                const updatedCategoryRow = document.querySelector(`#categoryTable tbody tr[data-id="${data.idCategoria}"]`);
                if (updatedCategoryRow) {
                    if (data.imagen) {
                        const imgElement = updatedCategoryRow.querySelector('img');
                        if (imgElement) {
                            imgElement.src = data.imagen; // Actualiza la imagen con la nueva ruta
                        } else {
                            const newImgElement = document.createElement('img');
                            newImgElement.src = data.imagen;
                            newImgElement.alt = 'Imagen de la categoría';
                            newImgElement.style.maxWidth = '100px'; // Ajusta el tamaño según sea necesario
                            updatedCategoryRow.children[1].appendChild(newImgElement); // Añade la imagen a la celda de la imagen
                        }
                        updatedCategoryRow.setAttribute('data-image', data.imagen); // Actualiza el atributo data-image
                    }
                }
    
                // Actualizar la imagen en el formulario solo si hay una nueva imagen
                const formImgElement = document.getElementById('updateCategoryForm').querySelector('img');
                if (data.imagen) {
                    if (formImgElement) {
                        formImgElement.src = data.imagen; // Actualiza la imagen con la nueva ruta
                    } else {
                        const newFormImgElement = document.createElement('img');
                        newFormImgElement.src = data.imagen;
                        newFormImgElement.alt = 'Imagen de la categoría';
                        newFormImgElement.style.maxWidth = '200px'; // Ajusta el tamaño según sea necesario
                        document.getElementById('updateCategoryForm').appendChild(newFormImgElement);
                    }
                } else if (formImgElement) {
                    formImgElement.src = ''; // Limpia la imagen si no hay nueva imagen
                    formImgElement.alt = 'Imagen de la categoría'; // Asegúrate de mantener el texto alternativo
                }
    
                // Vacía los campos del formulario
                document.querySelector('input[type="file"]').value = '';
            } else {
                messageDiv.textContent = data.message || 'Error al actualizar la categoría.';
                messageDiv.style.color = 'red';
            }
        } catch (error) {
            // Maneja errores de red u otros errores
            const messageDiv = document.getElementById('updateCategoryResponse');
            messageDiv.textContent = 'Error en la solicitud: ' + error.message;
            messageDiv.style.color = 'red';
        }
    });

    document.querySelectorAll('.deleteCategoryButton').forEach(button => {
        button.addEventListener('click', () => {
            const idCategoria = button.getAttribute('data-id');
            if (confirm('¿Estás seguro de que quieres eliminar esta categoría y todos los productos asociados?')) {
                fetch('/src/chef/deleteCategory.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        'accion': 'eliminarCategoria',
                        'idCategoria': idCategoria
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Categoría eliminada exitosamente.');
                        location.reload(); // Recargar la página para actualizar la tabla
                    } else {
                        alert('Error al eliminar la categoría: ' + data.message);
                    }
                });
            }
        });
    });

});