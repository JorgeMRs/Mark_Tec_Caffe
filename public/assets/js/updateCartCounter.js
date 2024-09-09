// updateCartCounter.js

document.addEventListener('DOMContentLoaded', function() {
    updateCartCounter();
});

function updateCartCounter() {
    const cartCounterElement = document.getElementById('cart-counter');

    fetch('/src/cart/getCartCounter.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                cartCounterElement.textContent = data.totalQuantity;
            } else {
                handleLocalStorageCart(cartCounterElement);
            }
        })
        .catch(() => {
            handleLocalStorageCart(cartCounterElement);
        });
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
