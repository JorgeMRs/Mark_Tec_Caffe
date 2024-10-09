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

document.addEventListener('DOMContentLoaded', function() {
    // Asegúrate de que el dropdown esté cerrado al cargar la página
    if (dropdown.classList.contains('active')) {
        dropdown.classList.remove('active');
    }
});

const toggleButton = document.querySelector('.toggle-button');
const navLinks = document.querySelector('.nav-links');
const dropdown = document.querySelector('.dropdown');
const dropdownMenu = document.querySelector('#mobile-category-dropdown');
const productosLink = document.querySelector('.dropdown-link');

// Detectar si estás en un dispositivo móvil
function isMobileDevice() {
    return window.innerWidth <= 768;
}

const currentPage = window.location.pathname;

// Evento para mostrar/ocultar el menú de navegación
toggleButton.addEventListener('click', function() {
    navLinks.classList.toggle('active');
    
    if (!navLinks.classList.contains('active')) {
        // Si el menú se cierra, también cierra el dropdown
        if (dropdown.classList.contains('active')) {
            dropdown.classList.remove('active');
        }
    }
});

// Prevenir redirección y abrir el dropdown en tienda.php para móviles
productosLink.addEventListener('click', function(e) {
    if (currentPage === '/public/tienda.php' && isMobileDevice()) {
        e.preventDefault();  // Prevenir redirección
        dropdown.classList.toggle('active');  
        dropdownMenu.classList.toggle('open');  
    }
});
