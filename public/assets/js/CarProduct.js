

function loadProducts(idCarrito) {
    fetch(`/src/db/DetallCarProduct.php?idCarrito=${idCarrito}`)
        .then((response) => response.json())
        .then((data) => {
            let productosHtml = "";
            data.forEach((producto) => {
                const precio = parseFloat(producto.precio);
                productosHtml += `

                <div class="product"> <img src="${producto.imagen} alt="Product Image">
                    <div class="product-details">
                        <h3>${producto.nombre}</h3>
                        <p>$${producto.descripcion}</p>
                        <p class="price">${producto.precio}</p>
                    </div>
                     <div class="quantity"> <input type="number" value="1" min="1"> pcs </div>
                </div>
                
               
                `;
            });
            document.getElementById('productos').innerHTML = productosHtml;
        })
        .catch((error) => {
            console.error('Error al cargar los productos:', error);
        });
}

// Ejecutar la función cuando se cargue la página y la URL contenga ?idCarrito=1
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const idCarrito = urlParams.get('idCarrito');
    if (idCarrito) {
        loadProducts(idCarrito);
    }
});