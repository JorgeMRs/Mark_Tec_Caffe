document.addEventListener('DOMContentLoaded', function() {
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    const removeBtns = document.querySelectorAll('.remove-btn');
    const checkoutBtn = document.querySelector('.checkout-btn');

    quantityBtns.forEach(btn => {
        btn.addEventListener('click', updateQuantity);
    });

    removeBtns.forEach(btn => {
        btn.addEventListener('click', removeItem);
    });

    checkoutBtn.addEventListener('click', proceedToCheckout);

    function updateQuantity(e) {
        const currentQuantity = parseInt(e.target.parentNode.querySelector('span').textContent);
        if (e.target.classList.contains('plus')) {
            e.target.parentNode.querySelector('span').textContent = currentQuantity + 1;
        } else if (e.target.classList.contains('minus') && currentQuantity > 1) {
            e.target.parentNode.querySelector('span').textContent = currentQuantity - 1;
        }
        updateTotal();
    }

    function removeItem(e) {
        e.target.closest('.bg-background').remove();
        updateTotal();
    }

    function updateTotal() {
        // Implement the logic to recalculate the total
        console.log('Total updated');
    }

    function proceedToCheckout() {
        // Implement checkout logic
        console.log('Proceeding to checkout');
    }
});
