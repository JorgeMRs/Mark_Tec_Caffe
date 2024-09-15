document.addEventListener('DOMContentLoaded', function() {
    const quantityElement = document.querySelector('.quantity');
    const maxQuantity = 10;  // Límite máximo de cantidad
    const productIdInput = document.getElementById('product-id');
    const productId = productIdInput ? productIdInput.value : null;

    function updateQuantity() {
        return parseInt(quantityElement.textContent);
    }

    document.querySelector('.quantity-control .btn-outline:first-of-type').addEventListener('click', function() {
        let quantity = updateQuantity();
        if (quantity > 1) {
            quantity -= 1;
            quantityElement.textContent = quantity;
        }
    });

    document.querySelector('.quantity-control .btn-outline:last-of-type').addEventListener('click', function() {
        let quantity = updateQuantity();
        if (quantity < maxQuantity) {
            quantity += 1;
            quantityElement.textContent = quantity;
        }
    });

    document.querySelector('.action-buttons .btn-lg:first-of-type').addEventListener('click', async function(e) {
        e.preventDefault();

        const quantity = updateQuantity();
        console.log('Product ID:', productId);
        console.log('Quantity:', quantity);

        try {
            const session = await checkSession();
            if (session.loggedIn) {
                await addToCart(productId, quantity);
            } else {
                addToLocalCart(productId, quantity);
            }
        } catch (error) {
            console.error('Error al procesar el pedido:', error);
        }
    });

    document.querySelector('.action-buttons .btn-lg.btn-outline').addEventListener('click', async function(e) {
        e.preventDefault();

        const quantity = updateQuantity();
        console.log('Product ID:', productId);
        console.log('Quantity:', quantity);

        try {
            const session = await checkSession();
            if (session.loggedIn) {
                await buyNow(productId, quantity);
            } else {
                addToLocalCart(productId, quantity);
                window.location.href = `/public/carrito.php`;
            }
        } catch (error) {
            console.error('Error al procesar la compra:', error);
        }
    });

    updateCartCounter();
});

async function checkSession() {
    try {
        const response = await fetch('/src/db/checkSession.php');
        if (!response.ok) throw new Error('Error en la respuesta del servidor');
        return await response.json();
    } catch (error) {
        console.error('Error al verificar la sesión:', error);
        return { loggedIn: false, userId: null };
    }
}

async function performFetch(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: data.toString()
        });
        if (!response.ok) throw new Error('Error en la respuesta del servidor');
        return await response.json();
    } catch (error) {
        console.error('Error en la solicitud de red:', error);
        throw error;
    }
}

async function addToCart(productId, quantity) {
    const url = '/src/cart/addCart.php';
    const data = new URLSearchParams({
        producto_id: productId,
        cantidad: quantity
    });

    try {
        const result = await performFetch(url, data);
        if (result.status === 'success') {
            updateCartCounter();
            resetQuantity();
            alert('Producto agregado al carrito.');
            document.dispatchEvent(new CustomEvent('cartUpdated'));
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Error en la red. Por favor, inténtelo de nuevo.');
    }
}

function addToLocalCart(productId, quantity) {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || {};
    carrito[productId] = (carrito[productId] || 0) + quantity;
    localStorage.setItem('carrito', JSON.stringify(carrito));

    const expirationTime = Date.now() + 3600000; // 1 hora
    localStorage.setItem('cart_expiration', expirationTime);

    updateCartCounter();
    resetQuantity();
    alert('Producto agregado al carrito local.');
    document.dispatchEvent(new CustomEvent('cartUpdated'));
}

function resetQuantity() {
    const quantityElement = document.querySelector('.quantity');
    quantityElement.textContent = '1';
}

async function buyNow(productId, quantity) {
    const url = '/src/cart/addCart.php';
    const data = new URLSearchParams({
        producto_id: productId,
        cantidad: quantity
    });

    try {
        const result = await performFetch(url, data);
        if (result.status === 'success') {
            window.location.href = `/public/carrito.php`;
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Error en la red. Por favor, inténtelo de nuevo.');
    }
}

async function updateCartCounter() {
    const cartCounterElement = document.getElementById('cart-counter');

    try {
        const response = await fetch('/src/cart/getCartCounter.php');
        if (!response.ok) throw new Error('Error en la respuesta del servidor');
        const data = await response.json();

        if (data.status === 'success') {
            cartCounterElement.textContent = data.totalQuantity;
        } else {
            handleLocalStorageCart(cartCounterElement);
        }
    } catch (error) {
        handleLocalStorageCart(cartCounterElement);
    }
}

function handleLocalStorageCart(cartCounterElement) {
    if (isCartExpired()) {
        clearExpiredCart();
    }
    const carrito = JSON.parse(localStorage.getItem('carrito')) || {};
    const totalQuantity = Object.values(carrito).reduce((acc, quantity) => acc + quantity, 0);
    cartCounterElement.textContent = totalQuantity;
}

function getCartExpiration() {
    return parseInt(localStorage.getItem('cart_expiration'));
}

function isCartExpired() {
    const expirationTime = getCartExpiration();
    return expirationTime && Date.now() > expirationTime;
}

function clearExpiredCart() {
    localStorage.removeItem('carrito');
    localStorage.removeItem('cart_expiration');
}
function setCategoryInLocalStorage(category, idCategoria) {
    const categoryData = {
        category,
        idCategoria
    };
    localStorage.setItem('selectedCategory', JSON.stringify(categoryData));
}