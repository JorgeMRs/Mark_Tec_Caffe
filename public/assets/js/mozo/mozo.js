
document.addEventListener('DOMContentLoaded', function() {
const productosSeleccionadosContainer = document.getElementById('productosSeleccionadosContainer');

function actualizarProductosSeleccionados() {
const productosSeleccionados = document.querySelectorAll('#productosContainer .product-row input[type="checkbox"]:checked');
console.log(productosSeleccionados); 

productosSeleccionadosContainer.innerHTML = '';

if (productosSeleccionados.length === 0) {
    productosSeleccionadosContainer.textContent = 'No hay productos seleccionados.';
    return;
}

let total = 0;

productosSeleccionados.forEach(function(productoCheckbox) {
    const productId = productoCheckbox.value;
    const productoRow = productoCheckbox.parentElement;
    const nombreProducto = productoRow.getAttribute('data-producto');
    const precio = parseFloat(productoRow.getAttribute('data-precio'));
    const cantidadSpan = document.getElementById(`cantidad-${productId}`);
    const cantidad = cantidadSpan ? parseInt(cantidadSpan.textContent, 10) : 0;

    if (cantidad > 0) {
        const productoInfo = document.createElement('div');
        productoInfo.textContent = `${nombreProducto} - Cantidad: ${cantidad} - Precio: ${precio.toFixed(2)} €`;
        productosSeleccionadosContainer.appendChild(productoInfo);

        total += precio * cantidad;
    }
});

const totalDiv = document.createElement('div');
totalDiv.textContent = `Total: ${total.toFixed(2)} €`;
productosSeleccionadosContainer.appendChild(totalDiv);
}

function actualizarCantidad(idProducto) {
const cantidadInput = document.getElementById(`cantidad-${idProducto}`);
let cantidad = parseInt(cantidadInput ? cantidadInput.textContent : 0, 10);
if (isNaN(cantidad)) cantidad = 0;
const checkbox = document.querySelector(`.product-row input[type="checkbox"][value="${idProducto}"]`);

if (checkbox) {
    if (cantidad <= 0) {
        checkbox.checked = false;
    }

    actualizarProductosSeleccionados();
}
}

function seleccionarProducto(idProducto) {
const cantidadInput = document.getElementById(`cantidad-${idProducto}`);
let cantidad = parseInt(cantidadInput ? cantidadInput.textContent : 0, 10);
if (isNaN(cantidad)) cantidad = 0;

if (cantidad === 0) {
    cantidadInput.textContent = 1;
    actualizarCantidad(idProducto);
}
}

function incrementarCantidad(idProducto) {
const cantidadInput = document.getElementById(`cantidad-${idProducto}`);
let cantidad = parseInt(cantidadInput ? cantidadInput.textContent : 0, 10);
if (isNaN(cantidad)) cantidad = 0;
if (cantidadInput && document.querySelector(`.product-row input[type="checkbox"][value="${idProducto}"]`).checked) {
    cantidadInput.textContent = cantidad + 1;
    actualizarCantidad(idProducto);
}
}

function decrementarCantidad(idProducto) {
const cantidadInput = document.getElementById(`cantidad-${idProducto}`);
let cantidad = parseInt(cantidadInput ? cantidadInput.textContent : 0, 10);
if (isNaN(cantidad)) cantidad = 0;
if (cantidad > 0 && cantidadInput && document.querySelector(`.product-row input[type="checkbox"][value="${idProducto}"]`).checked) {
    cantidadInput.textContent = cantidad - 1;
    actualizarCantidad(idProducto);
}
}

const cantidadInputs = document.querySelectorAll('.product-row .product-quantity button');
cantidadInputs.forEach(function(button) {
button.addEventListener('click', function() {
    const productId = this.dataset.productId;
    if (this.classList.contains('increment')) {
        incrementarCantidad(productId);
    } else if (this.classList.contains('decrement')) {
        decrementarCantidad(productId);
    }
});
});

document.querySelectorAll('.product-row input[type="checkbox"]').forEach(function(checkbox) {
checkbox.addEventListener('change', function() {
    const productId = this.value;
    if (this.checked) {
        seleccionarProducto(productId);
    } else {
        // Cuando se desmarca el checkbox, reinicia la cantidad a 0
        const cantidadInput = document.getElementById(`cantidad-${productId}`);
        cantidadInput.textContent = 0;
        actualizarCantidad(productId);
    }
});
});

document.getElementById('crearPedidoForm').addEventListener('submit', function(event) {
event.preventDefault();

const formData = new FormData();
const productosSeleccionados = document.querySelectorAll('.product-row input[type="checkbox"]:checked');
let cantidadValida = false;

productosSeleccionados.forEach(function(productoCheckbox) {
    const productId = productoCheckbox.value;
    const cantidadInput = document.getElementById(`cantidad-${productId}`);
    const cantidad = cantidadInput ? cantidadInput.textContent : 0;

    if (cantidad && cantidad > 0) {
        formData.append(`productos[${productId}]`, productId);
        formData.append(`cantidad[${productId}]`, cantidad);
        cantidadValida = true;
    }
});

if (productosSeleccionados.length === 0 || !cantidadValida) {
    alert('Por favor, selecciona al menos un producto con cantidad válida.');
    return;
}

const tipoPedido = document.getElementById('tipoPedido').value;
formData.append('tipoPedido', tipoPedido);

if (tipoPedido === 'Para Llevar') {
    const horaRecogida = document.getElementById('horaRecogida').value;
    formData.append('horaRecogida', horaRecogida);
} else {
    const numeroMesa = document.getElementById('numeroMesa').value;
    formData.append('numeroMesa', numeroMesa);
}

fetch('/src/db/mozoPedido.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pedido creado con éxito.');
            window.location.href = 'pedidos.php';
        } else {
            alert(`Error: ${data.message}`);
        }
    })
    .catch(error => {
        alert('Ha ocurrido un error. Inténtalo de nuevo.');
    });
});

actualizarProductosSeleccionados();

});

function toggleHoraRecogida() {
    const tipoPedido = document.getElementById('tipoPedido').value;
    const horaRecogidaContainer = document.getElementById('horaRecogidaContainer');
    const numeroMesaSelect = document.getElementById('numeroMesa');

    if (tipoPedido === 'Para Llevar') {
        horaRecogidaContainer.style.display = 'block';
        numeroMesaSelect.style.display = 'none';
    } else {
        horaRecogidaContainer.style.display = 'none';
        numeroMesaSelect.style.display = 'block';
    }
}

function filterProducts() {
    const searchTerm = document.getElementById('buscarProducto').value.toLowerCase();
    const productos = document.querySelectorAll('.product-row');

    productos.forEach(producto => {
        const productName = producto.getAttribute('data-producto').toLowerCase();
        if (productName.includes(searchTerm)) {
            producto.style.display = 'flex';
        } else {
            producto.style.display = 'none';
        }
    });
}
