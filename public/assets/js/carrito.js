document.addEventListener('DOMContentLoaded', function () {
    // Primero, verifica la sesión del usuario
    fetch('/src/db/checkSession.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red.');
            }
            return response.json();
        })
        .then(sessionData => {
            const userId = sessionData.loggedIn ? sessionData.userId : null;

            if (userId) {
                fetch(`/src/cart/getCart.php?user_id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        const productsContainer = document.getElementById('productos');

                        data.forEach(product => {
                            const totalPrice = product.precio * product.cantidad;
                            const productHTML = `
                               <div id="carta" class="flex items-center gap-4 bg-background" data-product-id="${product.idProducto}">
                                    <div class="imagenP flex-1">
                                        <img id="imagen" src="${product.imagen}" alt="${product.nombre}" width="80" height="80" class="rounded-md">
                                    </div>
                                    <div id="nombredesc" class="flex-1">
                                        <h3 class="font-medium text-lg">${product.nombre}</h3>
                                        <p class="text-muted-foreground text-sm">${product.descripcion}</p>
                                    </div>
                                    <div id="cantidad" class="flex items-center gap-2">
                                        <button class="quantity-btn minus">-</button>
                                        <span id="cantidadnum" data-precio="${product.precio}">${product.cantidad}</span>
                                        <button class="quantity-btn plus">+</button>
                                    </div>
                                    <div id="precionu" class="font-medium">€${totalPrice.toFixed(2)}</div>
                                    <button class="remove-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            `;

                            productsContainer.innerHTML += productHTML;
                        });

                        // Actualizar el subtotal, el IVA y el total en el DOM usando la función optimizada
                        updateSubtotalAndTax();

                        // Añadir event listeners después de insertar los productos
                        addEventListeners();
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                // Si no hay usuario logueado, mostrar un mensaje adecuado
                document.getElementById('productos').innerHTML = "<p>Por favor, inicia sesión para ver tu carrito.</p>";
            }
        })
        .catch(error => console.error('Error fetching session data:', error));
});

// Función para recalcular el subtotal y el IVA
function updateSubtotalAndTax() {
    let subtotal = 0;
    document.querySelectorAll('#cantidadnum').forEach(span => {
        const quantity = parseInt(span.textContent);
        const price = parseFloat(span.getAttribute('data-precio'));
        subtotal += quantity * price;
    });
    
    // Actualizar el subtotal en el DOM
    document.getElementById('subtotal').textContent = `€${subtotal.toFixed(2)}`;

    // Calcular y actualizar el IVA y el total
    const ivaRate = 0.20;
    const tax = subtotal * ivaRate;
    document.getElementById('tax').textContent = `€${tax.toFixed(2)}`;
    document.getElementById('total').textContent = `€${(subtotal + tax).toFixed(2)}`;
}





document.addEventListener('DOMContentLoaded', function() {
    addEventListeners(); // Añadir event listeners iniciales si hay elementos estáticos
});

function addEventListeners() {
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    const removeBtns = document.querySelectorAll('.remove-btn');

    quantityBtns.forEach(btn => {
        btn.addEventListener('click', updateQuantity);
    });

    removeBtns.forEach(btn => {
        btn.addEventListener('click', removeItem);
    });

}

function updateQuantity(e) {
    const quantitySpan = e.target.parentNode.querySelector('span');
    const currentQuantity = parseInt(quantitySpan.textContent);
    const productId = e.target.closest('.bg-background').dataset.productId;
    const price = parseFloat(quantitySpan.getAttribute('data-precio'));

    let newQuantity = currentQuantity;

    if (e.target.classList.contains('plus')) {
        newQuantity += 1;
    } else if (e.target.classList.contains('minus') && currentQuantity > 1) {
        newQuantity -= 1;
    }

    // Actualizar la cantidad en el DOM
    quantitySpan.textContent = newQuantity;
    const totalPrice = price * newQuantity;
    const priceDiv = e.target.closest('.bg-background').querySelector('#precionu');
    priceDiv.textContent = `€${totalPrice.toFixed(2)}`;

    updateSubtotalAndTax();

    const formData = new FormData();
    formData.append('producto_id', productId);
    formData.append('cantidad', newQuantity);

    fetch('/src/cart/updateQuantity.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== 'success') {
            console.error('Error updating quantity:', data.message);
        }
        updateCartCounter();  // Actualizar el contador del carrito después de actualizar la cantidad
    })
    .catch(error => console.error('Error:', error));
}

function removeItem(e) {
    const productElement = e.target.closest('.bg-background');
    const productId = productElement.dataset.productId; 

    fetch(`/src/cart/removeFromCart.php`, {
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
            updateCartCounter();  // Actualizar el contador del carrito después de eliminar el producto
        } else {
            console.error('Error removing product:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}