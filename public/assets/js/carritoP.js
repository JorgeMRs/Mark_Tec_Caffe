document.addEventListener('DOMContentLoaded', function() {
    addEventListeners(); // Añadir event listeners iniciales si hay elementos estáticos
});

function addEventListeners() {
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    const removeBtns = document.querySelectorAll('.remove-btn');
    const checkoutBtn = document.querySelector('.checkout-btn');

    quantityBtns.forEach(btn => {
        btn.addEventListener('click', updateQuantity);
    });

    removeBtns.forEach(btn => {
        btn.addEventListener('click', removeItem);
    });

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', proceedToCheckout);
    }
}

function updateQuantity(e) {
    const quantitySpan = e.target.parentNode.querySelector('span');
    const currentQuantity = parseInt(quantitySpan.textContent);
    const price = parseFloat(quantitySpan.getAttribute('data-precio'));

    if (e.target.classList.contains('plus')) {
        quantitySpan.textContent = currentQuantity + 1;
    } else if (e.target.classList.contains('minus') && currentQuantity > 1) {
        quantitySpan.textContent = currentQuantity - 1;
    }

    const newQuantity = parseInt(quantitySpan.textContent);
    const totalPrice = price * newQuantity;
    const priceDiv = e.target.closest('.bg-background').querySelector('#precionu');
    priceDiv.textContent = `$${totalPrice.toFixed(2)}`;

    updateSubtotalAndTax();
}

function removeItem(e) {
    const productElement = e.target.closest('.bg-background');
    const productId = productElement.dataset.productId; // Asegúrate de tener el ID del producto en el DOM

    fetch(`/src/db/remove_from_cart.php`, {
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
        } else {
            console.error('Error removing product:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateSubtotalAndTax() {
    let subtotal = 0;
    const ivaRate = 0.21; // Tasa de IVA (21%)

    document.querySelectorAll('#cantidadnum').forEach(span => {
        const quantity = parseInt(span.textContent);
        const price = parseFloat(span.getAttribute('data-precio'));
        subtotal += quantity * price;
    });

    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;

    // Calcular y actualizar el IVA y el total
    const tax = subtotal * ivaRate;
    document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('total').textContent = `$${(subtotal + tax).toFixed(2)}`;
}

function proceedToCheckout() {
    // Implement checkout logic
    console.log('Proceeding to checkout');
}