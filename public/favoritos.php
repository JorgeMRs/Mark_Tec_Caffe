<?php
include '../vendor/autoload.php';
include '../src/auth/verifyToken.php';

$response = checkToken();

?>
<!DOCTYPE html>
<?php

$pageTitle = 'Café Sabrosos - Productos Favoritos';

$customCSS = [
    '/public/assets/css/nav.css',
    '/public/assets/css/footer.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'
];

include 'templates/head.php' ?>

<body>
    <header>
        <?php
        include 'templates/nav.php'
        ?>
    </header>
    <div class="container">
        <p id="empty-favorites-message" style="display: none; text-align: center;">No tienes actualmente productos en favoritos.</p>
        <div class="grid" id="favorites">
        </div>
    </div>

    <style>
        /* Tu CSS aquí (igual que antes) */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            min-height: 20vh;
            margin-top: 80px;
            margin-bottom: 180px;
            padding: 2rem;
        }

        header {
            position: relative;
            z-index: 100000;
            text-align: center;
            margin-bottom: 2rem;
            font-family: 'Poppins', sans-serif;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            margin-top: 0;
        }

        .header p {
            color: #6c757d;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .card-header {
            position: relative;
        }

        .image-container {
            position: relative;
            padding-top: 100%;
            /* Aspect ratio 1:1 */
        }

        .image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .remove-button {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            /* Adjust size of the icon */
        }

        .card-content {
            padding: 1rem;
            flex-grow: 1;
        }

        .card-title {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .price {
            color: #6c757d;
        }

        .card-footer {
            padding: 1rem;
        }

        .view-details-button {
            width: 100%;
            padding: 0.5rem;
            background-color: #1b0d0b;
            color: #daa520;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
        }

        i.fa-trash-can:hover {
            background-color: #d5d5d5;
        }

        i.fa-trash-can {
            background-color: white;
            padding: 8px;
            border-radius: 18px;
            transition: 0.5s;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const favoritesContainer = document.getElementById('favorites');
            const emptyFavoritesMessage = document.getElementById('empty-favorites-message');

            // Obtener el array de IDs de favoritos del localStorage
            const favorites = JSON.parse(localStorage.getItem('favorites')) || [];

            // Verificar si hay productos favoritos
            if (favorites.length === 0) {
                emptyFavoritesMessage.style.display = 'block'; // Mostrar mensaje si no hay favoritos
            } else {
                emptyFavoritesMessage.style.display = 'none'; // Ocultar mensaje si hay favoritos
                fetchProducts(favorites);
            }

            function fetchProducts(favoriteIds) {
                fetch(`/src/client/favorites.php?ids=${favoriteIds.join(',')}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.length === 0) {
                            emptyFavoritesMessage.style.display = 'block'; // Mostrar mensaje si no hay productos
                        } else {
                            data.forEach(product => {
                                const productHTML = `
                                    <div class="card" data-product-id="${product.idProducto}">
                                        <div class="card-header">
                                            <div class="image-container">
                                                <img src="${product.imagen}" alt="${product.nombre}" class="image" />
                                                <button class="remove-button" data-product-id="${product.idProducto}">
                                                    <span class="sr-only">Remove from favorites</span><i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <h2 class="card-title">${product.nombre}</h2>
                                            <p class="price">$${parseFloat(product.precio).toFixed(2)}</p>
                                        </div>
                                        <div class="card-footer">
                                           <a href="productos.php?id=${product.idProducto}"> <button class="view-details-button"><i class="fas fa-eye"></i> Ver Detalles</button></a>
                                        </div>
                                    </div>
                                `;
                                favoritesContainer.innerHTML += productHTML;
                            });
                            addRemoveEventListeners(); // Llamar a la función para agregar listeners
                        }
                    })
                    .catch(error => console.error('Error fetching products:', error));
            }

            function addRemoveEventListeners() {
                const removeButtons = document.querySelectorAll('.remove-button');

                removeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = button.dataset.productId;
                        removeFromFavorites(productId);
                        button.closest('.card').remove(); // Eliminar el producto de la vista
                    });
                });
            }

            function removeFromFavorites(productId) {
                let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
                favorites = favorites.filter(id => id !== productId); // Eliminar el ID del producto
                localStorage.setItem('favorites', JSON.stringify(favorites)); // Actualizar el localStorage
                if (favorites.length === 0) {
                    emptyFavoritesMessage.style.display = 'block'; // Mostrar mensaje si no hay favoritos
                }
            }
        });
    </script>
    <?php
    include 'templates/footer.php';
    ?>
    <script src="/public/assets/js/updateCartCounter.js"></script>
</body>

</html>