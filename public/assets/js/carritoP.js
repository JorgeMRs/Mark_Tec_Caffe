// const quantityBtns = document.querySelectorAll('.quantity-btn');
// const removeBtns = document.querySelectorAll('.remove-btn');
// const checkoutBtn = document.querySelector('.checkout-btn');

// console.log('quantityBtns:', quantityBtns);
// console.log('removeBtns:', removeBtns);
// console.log('checkoutBtn:', checkoutBtn);

// quantityBtns.forEach(btn => {
//     btn.addEventListener('click', updateQuantity);
// });

// removeBtns.forEach(btn => {
//     btn.addEventListener('click', removeItem);
// });

// checkoutBtn.addEventListener('click', proceedToCheckout);

// function updateQuantity(e) {
//     const currentQuantity = parseInt(e.target.parentNode.querySelector('span').textContent);

//     if (e.target.classList.contains('plus')) {
//         e.target.parentNode.querySelector('span').textContent = currentQuantity + 1;
//     } else if (e.target.classList.contains('minus') && currentQuantity > 1) {
//         e.target.parentNode.querySelector('span').textContent = currentQuantity - 1;
//     }

//     updateTotal();
// }

// function removeItem(e) {
//     const productElement = e.target.closest('.bg-background');
//     const productId = productElement.dataset.productId; // Asegúrate de tener el ID del producto en el DOM

//     fetch(`/src/db/remove_from_cart.php`, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify({ product_id: productId })
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             productElement.remove();
//             updateTotal();
//         } else {
//             console.error('Error removing product:', data.message);
//         }
//     })
//     .catch(error => console.error('Error:', error));
// }

// function updateTotal() {
//     // Implement the logic to recalculate the total
//     console.log('Total updated');
// }

// function proceedToCheckout() {
//     // Implement checkout logic
//     console.log('Proceeding to checkout');
// }


function addEventListeners() {
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    const removeBtns = document.querySelectorAll('.remove-btn');
    const checkoutBtn = document.querySelector('.checkout-btn');

    console.log('quantityBtns:', quantityBtns);
    console.log('removeBtns:', removeBtns);
    console.log('checkoutBtn:', checkoutBtn);

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
    const currentQuantity = parseInt(e.target.parentNode.querySelector('span').textContent);

    if (e.target.classList.contains('plus')) {
        e.target.parentNode.querySelector('span').textContent = currentQuantity + 1;
    } else if (e.target.classList.contains('minus') && currentQuantity > 1) {
        e.target.parentNode.querySelector('span').textContent = currentQuantity - 1;
    }

    updateTotal();
}

function removeItem(e) {
    const productElement = e.target.closest('.bg-background');
    const productId = productElement.dataset.productId; // Asegúrate de tener el ID del producto en el DOM
    //verificar que tengo el pruducto en el DOM
    console.log('productElement:', productElement);
    console.log('productId:', productId);




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
            updateTotal();
        } else {
            console.error('Error removing product:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateTotal() {
    // Implement the logic to recalculate the total
    console.log('Total updated');
}

function proceedToCheckout() {
    // Implement checkout logic
    console.log('Proceeding to checkout');
}

document.addEventListener('DOMContentLoaded', function() {
    addEventListeners(); // Añadir event listeners iniciales si hay elementos estáticos
});