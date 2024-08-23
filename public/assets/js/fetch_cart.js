document.addEventListener('DOMContentLoaded', () => {
    updateCartCounter();
    loadCartItems();
    updateTotalPrice();
});

function updateTotalPrice() {
    fetch('../../backend/cart/fetchdb_cart.php')
        .then(response => response.json())
        .then(data => {
            let totalPrice = 0;
            data.forEach(item => {
                totalPrice += item.precio * item.quantity;
            });
            document.getElementById('total-price').textContent = `$${totalPrice.toFixed(2)}`;
        })
        .catch(error => console.error('Error:', error));
}

function loadCartItems() {
    fetch('../../backend/cart/fetchdb_cart.php')
        .then(response => response.json())
        .then(data => {
            const cartItemsTbody = document.getElementById('cart-items-tbody');
            cartItemsTbody.innerHTML = '';

            if (data.length > 0) {
                data.forEach(item => {
                    const productTotal = (item.precio * item.quantity).toFixed(2);
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                        <td class="flex-container">
                            <div class="flex-img-container">
                                <img src="${item.imagen}" alt="${item.nombre}" width="100">
                            </div>
                            <div class="flex-name-container">
                                ${item.nombre}
                            </div>
                        </td>
                        <td>$${item.precio}</td>
                        <td>
                            <div class="quantity-controls">
                                <button class='quantity-btn' onclick='updateQuantity(${item.idproducto}, "decrease")'>-</button>
                                <div class='quantity-display'>${item.quantity}</div>
                                <button class='quantity-btn' onclick='updateQuantity(${item.idproducto}, "increase")'>+</button>
                            </div>
                        </td>
                        <td class='total-column'>
                            <div class='total-and-remove'>
                                <span class='product-total'>$${productTotal}</span>
                                <button class='remove-btn' onclick='removeFromCart(${item.idproducto})'>x</button>
                            </div>
                        </td>
                    `;

                    cartItemsTbody.appendChild(tr);
                });
            } else {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td colspan="4">Tu carrito está vacío.</td>';
                cartItemsTbody.appendChild(tr);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateQuantity(productId, action) {
    fetch('../../backend/cart/update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ product_id: productId, action: action })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            updateCartCounter();
            loadCartItems();
            updateTotalPrice();
        } else {
            console.error('Error updating quantity');
        }
    })
    .catch(error => console.error('Error:', error));
}

function removeFromCart(productId) {
    fetch('../../backend/cart/remove_from_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            updateTotalPrice();
            updateCartCounter();
            loadCartItems();
        } else {
            console.error('Error removing item');
        }
    })
    .catch(error => console.error('Error:', error));
}

function verifyOrder() {
    // Check if the user is logged in
    fetch('../../check_login.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                // User is logged in, redirect to checkout page
                window.location.href = '../../frontend/pages/pagar.html';
            } else {
                // User is not logged in, redirect to login page
                window.location.href = '../pages/iniciarsesion.html?order_attempt=1';
            }
        })
        .catch(error => console.error('Error:', error));
}
