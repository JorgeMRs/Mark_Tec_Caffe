document.addEventListener("DOMContentLoaded", function () {
    const cartIcon = document.getElementById("cart-icon");
    const cartPreview = document.getElementById("cart-preview");
    const cartItems = document.getElementById("cart-items");
    let cartData = null;
    let isDataLoaded = false;

    async function fetchCartItems(userId) {
        try {
            const response = await fetch(`/src/cart/getCart.php?user_id=${userId}`);
            const data = await response.json();
            cartData = data; 
            isDataLoaded = true; 
            renderCartItems(); 
        } catch (error) {
            console.error("Error al obtener los productos del carrito:", error);
            cartItems.innerHTML = "<p>Error al cargar el carrito.</p>";
        }
    }

    function renderCartItems() {
        cartItems.innerHTML = "";

        if (cartData && cartData.length > 0) {
            let subtotal = 0;

            cartData.forEach(item => {
                const cartItem = document.createElement("li");
                cartItem.classList.add("cart-item");

                const itemTotal = item.cantidad * item.precio;
                subtotal += itemTotal;

                cartItem.innerHTML = `
                    <img src="${item.imagen}" alt="${item.nombre}">
                    <div class="cart-item-details">
                        <h4>${item.nombre}</h4>
                        <p>Cantidad: ${item.cantidad}</p>
                        <p>Precio: €${item.precio}</p>
                    </div>
                `;
                cartItems.appendChild(cartItem);
            });
            const subtotalElement = document.createElement("li");
            subtotalElement.classList.add("cart-subtotal");
            subtotalElement.innerHTML = `
                <span class="subtotal-text">Subtotal del carrito:</span>
                <span class="subtotal-price">€${subtotal.toFixed(2)}</span>
            `;
            cartItems.appendChild(subtotalElement);
        } else {
            cartItems.innerHTML = "<p>Tu carrito está vacío.</p>";
        }
    }

    function handleCartUpdate() {
        fetch('/src/db/checkSession.php')
            .then(response => response.json())
            .then(data => {
                const userId = data.loggedIn ? data.userId : null;
                if (userId) {
                    fetchCartItems(userId); 
                } else {
                    console.log('Usuario no conectado. No se puede actualizar el carrito.');
                    
                }
            })
            .catch(error => {
                console.error('Error al verificar la sesión:', error);
            });
    }
    

    document.addEventListener('cartUpdated', handleCartUpdate);

    // Verificar si el usuario está conectado
    fetch('/src/db/checkSession.php')
        .then(response => response.json())
        .then(data => {
            const userId = data.loggedIn ? data.userId : null;

            cartIcon.addEventListener("mouseenter", debounce(function () {
                if (userId) {
                    if (!isDataLoaded) {
                        fetchCartItems(userId);
                    } else {
                        renderCartItems();
                    }
                    cartPreview.classList.add("visible");
                    cartPreview.classList.remove("hide");
                } else {
                    cartItems.innerHTML = "<p>Por favor, inicia sesión para ver tu carrito.</p>";
                }
            }, 100));

            cartIcon.addEventListener("mouseleave", function () {
                cartPreview.classList.add("hide"); 
                setTimeout(() => {
                    cartPreview.classList.remove("visible"); 
                }, 300); 
            });

            cartPreview.addEventListener("mouseleave", function () {
                cartPreview.classList.add("hide"); 
                setTimeout(() => {
                    cartPreview.classList.remove("visible"); 
                }, 300); 
            });
        });

    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
});

function toggleLanguageMenu() {
    const menu = document.getElementById('language-menu');
    menu.classList.toggle('show');
}
function changeLanguage(lang) {
    const url = new URL(window.location.href);
    url.searchParams.set('lang', lang);
    window.location.href = url.toString();
}
document.addEventListener('DOMContentLoaded', () => {
const language = '<?= htmlspecialchars($language) ?>'; // Obtener el idioma actual de PHP

document.querySelectorAll('[data-lang]').forEach(element => {
    if (element.getAttribute('data-lang') === language) {
        element.style.display = 'block';
    } else {
        element.style.display = 'none';
    }
});
});
