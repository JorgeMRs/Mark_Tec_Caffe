document.addEventListener("DOMContentLoaded", function() {
    function updateCartCounter() {
        let cartCounterElements = document.querySelectorAll('#cart-counter, #cart-counter2');
        fetch('../../backend/cart/obtener_cantidad_carrito.php')
            .then(response => {
                if (response.ok) {
                    return response.text();
                }
                throw new Error('Network response was not ok.');
            })
            .then(data => {
                cartCounterElements.forEach(cartCounter => {
                    cartCounter.textContent = data;
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Add updateCartCounter to the window object to make it globally accessible
    window.updateCartCounter = updateCartCounter;

    updateCartCounter();

    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            updateCartCounter();
        }
    });
});