function toggleFields() {
    const orderType = document.querySelector('input[name="orderType"]:checked').value;
    const pickupTimeField = document.getElementById('pickupTime');

    document.getElementById('branch-container').style.display = 'block'; // Mostrar siempre el campo de sucursal
    const isPickup = orderType === 'Para llevar';

    document.getElementById('pickupTime-container').style.display = isPickup ? 'block' : 'none';

    // Si es "Para llevar", el campo pickupTime es requerido; si no, no lo es.
    if (isPickup) {
        pickupTimeField.setAttribute('required', 'required');
    } else {
        pickupTimeField.removeAttribute('required');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleFields();
});
async function submitOrder(event) {
    event.preventDefault(); // Prevenir el envío por defecto del formulario

    const form = event.target;
    const formData = new FormData(form);
    const responseDiv = document.getElementById('response-message');
    const csrfToken = document.getElementById('csrf_token').value;

    formData.append('csrf_token', csrfToken);
    // Mostrar los valores para depuración
    for (const [key, value] of formData.entries()) {
        console.log(`Campo: ${key}, Valor: ${value}`);
    }

    try {
        const response = await fetch('/src/client/submitOrder.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            // Redirige a la página de confirmación con el ID del pedido
            window.location.href = `pagoConfirmado.php?order_id=${result.orderId}`;
        } else {
            responseDiv.innerHTML = `<p class="error">Error: ${result.message}</p>`;
        }
    } catch (error) {
        responseDiv.innerHTML = `<p class="error">Error en la solicitud: ${error.message}</p>`;
    }
}

document.querySelector('form').addEventListener('submit', submitOrder);

document.getElementById('cart-icon').addEventListener('click', function() {
    const cartContent = document.getElementById('cart-content');
    const cartIconContainer = document.querySelector('.cart-icon-container');

    // Mostrar el contenido del carrito
    if (!cartContent.classList.contains('show')) {
        fetch('/src/cart/getCart.php') // Cambia a la ruta de tu API
            .then(response => response.json())
            .then(data => {
                const cartItemsContainer = document.querySelector('.cart-items');
                cartItemsContainer.innerHTML = ''; // Limpiar contenido previo

                let subtotal = 0;

                if (data.length === 0) {
                    cartItemsContainer.innerHTML = '<p>Tu carrito está vacío.</p>';
                    document.getElementById('subtotal').innerText = '$0.00';
                    document.getElementById('total').innerText = '$0.00';
                } else {
                    data.forEach(item => {
                        const price = parseFloat(item.precio);
                        const quantity = parseInt(item.cantidad);
                        subtotal += price * quantity;

                        const itemHTML = `
                    <div class="cart-item">
                        <img src="${item.imagen}" alt="${item.nombre}" style="width: 50px;">
                        <div>${item.nombre} - $${isNaN(price) ? 'N/A' : price.toFixed(2)} (Cantidad: ${quantity})</div>
                    </div>
                `;
                        cartItemsContainer.innerHTML += itemHTML;
                    });

                    const tax = subtotal * 0.2; // 20% de impuesto
                    const total = subtotal + tax;

                    document.getElementById('subtotal').innerText = `$${subtotal.toFixed(2)}`;
                    document.getElementById('total').innerText = `$${total.toFixed(2)}`;
                }

                // Mostrar el contenedor con animación
                cartContent.classList.add('show');
                cartContent.style.display = 'block';
                cartIconContainer.style.right = '300px'; // Desplaza el contenedor a la derecha
            })
            .catch(error => console.error('Error al cargar el carrito:', error));
    } else {
        // Ocultar el contenido
        cartContent.classList.remove('show');
        cartIconContainer.style.right = '32%'; // Regresa el contenedor a la posición original
        setTimeout(() => {
            cartContent.style.display = 'none'; // Oculta después de la animación
        }, 500); // Debe coincidir con la duración de la transición
    }
});

// Cerrar el carrito
document.getElementById('close-cart').addEventListener('click', function() {
    const cartContent = document.getElementById('cart-content');
    const cartIconContainer = document.querySelector('.cart-icon-container');

    cartContent.classList.remove('show');
    cartIconContainer.style.right = '32%'; // Regresa el contenedor a la posición original
    setTimeout(() => {
        cartContent.style.display = 'none';
    }, 500);
});