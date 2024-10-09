document.addEventListener('DOMContentLoaded', function () {
    const languageSelector = document.getElementById('language-selector');
    let selectedLanguage = localStorage.getItem('selectedLanguage') || languageSelector.value; // Usa el idioma guardado o el por defecto

    // Establecer el idioma seleccionado en el selector
    languageSelector.value = selectedLanguage;

    // Listener para cambios en el selector de idioma
    languageSelector.addEventListener('change', function () {
        selectedLanguage = this.value; // Actualiza el idioma seleccionado
        localStorage.setItem('selectedLanguage', selectedLanguage); // Guarda el idioma en localStorage
        fetchCartProducts(); // Vuelve a obtener los productos con el nuevo idioma
    });

    function fetchCartProducts() {
        fetch('/src/db/checkSession.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network error.');
                }
                return response.json();
            })
            .then(sessionData => {
                const userId = sessionData.loggedIn ? sessionData.userId : null;

                if (userId) {
                    fetch(`/src/cart/getCart.php?user_id=${userId}&lang=${selectedLanguage}`) // Pasa el idioma como parámetro
                        .then(response => response.json())
                        .then(data => {
                            const checkoutButton = document.getElementById('checkout-button');
                            const productsContainer = document.querySelector('.products tbody');
                            const emptyCartMessage = document.getElementById('empty-cart-message');

                            checkoutButton.disabled = true;
                            productsContainer.innerHTML = '';

                            if (data.length === 0) {
                                emptyCartMessage.style.display = 'block';
                            } else {
                                emptyCartMessage.style.display = 'none';

                                data.forEach(product => {
                                    const price = parseFloat(product.precio);
                                    if (isNaN(price)) {
                                        console.error(`Invalid price for product ${product.nombre}: ${product.precio}`);
                                        return;
                                    }

                                    const productHTML = `
                                        <tr class="product" data-product-id="${product.idProducto}">
                                            <td>
                                                <img class="product-image" src="${product.imagen}" alt="${product.nombre}">
                                            </td>
                                            <td>
                                                <div class="details">
                                                    <h2 style="text-transform: uppercase;">${product.nombre}</h2>
                                                    <p>${product.descripcion}</p>
                                                </div>
                                            </td>
                                            <td>€${price.toFixed(2)}</td>
                                            <td>
                                                <div class="controls">
                                                    <button class="btn icon decrease">-</button>
                                                    <div class="quantity">${product.cantidad}</div>
                                                    <button class="btn icon increase">+</button>
                                                </div>
                                            </td>
                                            <td>€${(price * product.cantidad).toFixed(2)}</td>
                                            <td class="btn-wrapper">
                                                <button class="btn ghost favorite">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                                <button class="btn ghost remove">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    `;

                                    productsContainer.innerHTML += productHTML;
                                });

                                if (data.length > 0) {
                                    checkoutButton.disabled = false;
                                }

                                updateSubtotalAndTax();
                                addEventListeners();
                                initializeFavorites();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    document.querySelector('.products tbody').innerHTML = "<tr><td colspan='5'>Por favor, inicia sesión para ver tu carrito.</td></tr>";
                }
            })
            .catch(error => console.error('Error fetching session data:', error));
    }

    // Llama a la función para obtener los productos al cargar
    fetchCartProducts();

    const checkoutButton = document.getElementById('checkout-button');
    checkoutButton.addEventListener('click', function () {
        if (!checkoutButton.disabled) {
            // Redirigir a pagar.php
            window.location.href = 'pagar.php';
        }
    });
});

function updateSubtotalAndTax() {
    let subtotal = 0;
    document.querySelectorAll('.product').forEach(product => {
        const quantity = parseInt(product.querySelector('.quantity').textContent);
        const price = parseFloat(product.querySelector('td:nth-child(3)').textContent.replace('€', ''));
        subtotal += quantity * price;
    });

    const taxRate = 0.20;
    const tax = subtotal * taxRate;
    const total = subtotal + tax;

    document.getElementById('subtotal').textContent = `€${subtotal.toFixed(2)}`;
    document.getElementById('tax').textContent = `€${tax.toFixed(2)}`;
    document.getElementById('total').textContent = `€${total.toFixed(2)}`;
}

function addEventListeners() {
    const decreaseBtns = document.querySelectorAll('.decrease');
    const increaseBtns = document.querySelectorAll('.increase');
    const removeBtns = document.querySelectorAll('.remove');

    decreaseBtns.forEach(btn => {
        btn.addEventListener('click', updateQuantity);
    });

    increaseBtns.forEach(btn => {
        btn.addEventListener('click', updateQuantity);
    });

    removeBtns.forEach(btn => {
        btn.addEventListener('click', removeItem);
    });
}

function updateQuantity(e) {
    const productElement = e.target.closest('.product');
    const quantitySpan = productElement.querySelector('.quantity');
    let currentQuantity = parseInt(quantitySpan.textContent);
    const productId = productElement.dataset.productId;
    const price = parseFloat(productElement.querySelector('td:nth-child(3)').textContent.replace('€', ''));

    if (e.target.classList.contains('increase')) {
        currentQuantity += 1;
    } else if (e.target.classList.contains('decrease') && currentQuantity > 1) {
        currentQuantity -= 1;
    }

    quantitySpan.textContent = currentQuantity;

    // Actualizar el total del producto
    const totalCell = productElement.querySelector('td:nth-child(5)');
    totalCell.textContent = `€${(price * currentQuantity).toFixed(2)}`;

    updateSubtotalAndTax();

    const formData = new FormData();
    formData.append('producto_id', productId);
    formData.append('cantidad', currentQuantity);

    fetch('/src/cart/updateQuantity.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== 'success') {
            console.error('Error updating quantity:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function removeItem(e) {
    const productElement = e.target.closest('.product');
    const productId = productElement.dataset.productId;
    const checkoutButton = document.getElementById('checkout-button');

    fetch('/src/cart/removeFromCart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            productElement.remove();
            updateSubtotalAndTax();

            // Verificar si el carrito está vacío después de eliminar un producto
            if (document.querySelectorAll('.product').length === 0) {
                document.getElementById('empty-cart-message').style.display = 'block';
                checkoutButton.disabled = true;  // Deshabilitar el botón si no hay productos
            } else {
                checkoutButton.disabled = false; // Habilitar si aún quedan productos
            }
        } else {
            console.error('Error removing product:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function initializeFavorites() {
    const favoriteButtons = document.querySelectorAll('.btn.favorite');

    // Obtener el array de favoritos del localStorage o crear uno nuevo
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];

    favoriteButtons.forEach(button => {
        const productId = button.closest('.product').dataset.productId;

        // Verificar si el producto está en el array de favoritos
        if (favorites.includes(productId)) {
            button.classList.add('selected'); // Si está en favoritos, marcar como seleccionado
        }

        // Agregar event listener para el click
        button.addEventListener('click', function () {
            if (button.classList.contains('selected')) {
                // Si ya está seleccionado (dorado), cambiar a rojo y eliminar del array
                button.classList.remove('selected');
                favorites = favorites.filter(favId => favId !== productId); // Eliminar del array
            } else {
                // Si no está seleccionado, cambiar a dorado y agregar al array
                button.classList.add('selected');
                favorites.push(productId); // Agregar al array
            }

            // Actualizar el localStorage con el nuevo array de favoritos
            localStorage.setItem('favorites', JSON.stringify(favorites));
        });
    });
}
