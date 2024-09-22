import QrScanner from './qr-scanner.min.js'; // if using plain es6 import

document.addEventListener("DOMContentLoaded", function () {
  const productosSeleccionadosContainer = document.getElementById(
    "productosSeleccionadosContainer"
  );

  function actualizarProductosSeleccionados() {
    const productosSeleccionados = document.querySelectorAll(
      '#productosContainer .product-row input[type="checkbox"]:checked'
    );
    console.log(productosSeleccionados);

    productosSeleccionadosContainer.innerHTML = "";

    if (productosSeleccionados.length === 0) {
      productosSeleccionadosContainer.textContent =
        "No hay productos seleccionados.";
      return;
    }

    let total = 0;

    productosSeleccionados.forEach(function (productoCheckbox) {
      const productId = productoCheckbox.value;
      const productoRow = productoCheckbox.parentElement;
      const nombreProducto = productoRow.getAttribute("data-producto");
      const precio = parseFloat(productoRow.getAttribute("data-precio"));
      const cantidadSpan = document.getElementById(`cantidad-${productId}`);
      const cantidad = cantidadSpan
        ? parseInt(cantidadSpan.textContent, 10)
        : 0;

      if (cantidad > 0) {
        const productoInfo = document.createElement("div");
        productoInfo.textContent = `${nombreProducto} - Cantidad: ${cantidad} - Precio: ${precio.toFixed(
          2
        )} €`;
        productosSeleccionadosContainer.appendChild(productoInfo);

        total += precio * cantidad;
      }
    });

    const totalDiv = document.createElement("div");
    totalDiv.textContent = `Total: ${total.toFixed(2)} €`;
    productosSeleccionadosContainer.appendChild(totalDiv);
  }

  function actualizarCantidad(idProducto) {
    const cantidadInput = document.getElementById(`cantidad-${idProducto}`);
    let cantidad = parseInt(cantidadInput ? cantidadInput.textContent : 0, 10);
    if (isNaN(cantidad)) cantidad = 0;
    const checkbox = document.querySelector(
      `.product-row input[type="checkbox"][value="${idProducto}"]`
    );

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

    const checkbox = document.querySelector(`.product-row input[type="checkbox"][value="${idProducto}"]`);

    // Si el checkbox no está seleccionado, seleccionarlo automáticamente
    if (!checkbox.checked) {
        checkbox.checked = true;
        seleccionarProducto(idProducto); // Asegurarte de inicializar la cantidad en 1 si no está seleccionado
    }

    // Ahora incrementar la cantidad si el checkbox está seleccionado
    if (checkbox.checked) {
        cantidadInput.textContent = cantidad + 1;
        actualizarCantidad(idProducto);
    }
}

  function decrementarCantidad(idProducto) {
    const cantidadInput = document.getElementById(`cantidad-${idProducto}`);
    let cantidad = parseInt(cantidadInput ? cantidadInput.textContent : 0, 10);
    if (isNaN(cantidad)) cantidad = 0;
    if (
      cantidad > 0 &&
      cantidadInput &&
      document.querySelector(
        `.product-row input[type="checkbox"][value="${idProducto}"]`
      ).checked
    ) {
      cantidadInput.textContent = cantidad - 1;
      actualizarCantidad(idProducto);
    }
  }

  const cantidadInputs = document.querySelectorAll(
    ".product-row .product-quantity button"
  );
  cantidadInputs.forEach(function (button) {
    button.addEventListener("click", function () {
      const productId = this.dataset.productId;
      if (this.classList.contains("increment")) {
        incrementarCantidad(productId);
      } else if (this.classList.contains("decrement")) {
        decrementarCantidad(productId);
      }
    });
  });

  document
    .querySelectorAll('.product-row input[type="checkbox"]')
    .forEach(function (checkbox) {
      checkbox.addEventListener("change", function () {
        const productId = this.value;
        if (this.checked) {
          seleccionarProducto(productId);
        } else {
          // Cuando se desmarca el checkbox, reinicia la cantidad a 0
          const cantidadInput = document.getElementById(
            `cantidad-${productId}`
          );
          cantidadInput.textContent = 0;
          actualizarCantidad(productId);
        }
      });
    });

  document
    .getElementById("crearPedidoForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      const formData = new FormData();
      const productosSeleccionados = document.querySelectorAll(
        '.product-row input[type="checkbox"]:checked'
      );
      let cantidadValida = false;

      productosSeleccionados.forEach(function (productoCheckbox) {
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
        alert(
          "Por favor, selecciona al menos un producto con cantidad válida."
        );
        return;
      }

      const tipoPedido = document.getElementById("tipoPedido").value;
      formData.append("tipoPedido", tipoPedido);

      if (tipoPedido === "Para llevar") {
        const horaRecogida = document.getElementById("horaRecogida").value;
        formData.append("horaRecogida", horaRecogida);
      } else {
        const idMesa = document.getElementById("numeroMesa").value; // ID de la mesa
        formData.append("idMesa", idMesa); // Se envía idMesa
      }

      const notas = document.getElementById("notas").value;
      formData.append("notas", notas);

      fetch("/src/db/mozoPedido.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Pedido creado con éxito.");
            window.location.href = "pedidos.php";
          } else {
            alert(`Error: ${data.message}`);
          }
        })
        .catch((error) => {
          alert("Ha ocurrido un error. Inténtalo de nuevo.");
        });
    });

  actualizarProductosSeleccionados();
});

function toggleHoraRecogida() {
  const tipoPedido = document.getElementById("tipoPedido").value;
  const horaRecogidaContainer = document.getElementById(
    "horaRecogidaContainer"
  );
  const numeroMesaSelect = document.getElementById("numeroMesa");

  if (tipoPedido === "Para llevar") {
    horaRecogidaContainer.style.display = "block";
    numeroMesaSelect.style.display = "none";
  } else {
    horaRecogidaContainer.style.display = "none";
    numeroMesaSelect.style.display = "block";
  }
}

function filterProducts() {
  const searchTerm = document
    .getElementById("buscarProducto")
    .value.toLowerCase();
  const productos = document.querySelectorAll(".product-row");

  productos.forEach((producto) => {
    const productName = producto.getAttribute("data-producto").toLowerCase();
    if (productName.includes(searchTerm)) {
      producto.style.display = "flex";
    } else {
      producto.style.display = "none";
    }
  });
}
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("detalleModal");
  const span = document.getElementsByClassName("close")[0];
  const cancelOrderButton = document.getElementById("cancelOrderButton");
  const confirmCancelModal = document.getElementById("confirmCancelModal");
  const confirmCancelButton = document.getElementById("confirmCancelButton");
  const cancelCancelButton = document.getElementById("cancelCancelButton");
  const cancelNotes = document.getElementById("cancelNotes");
  const closeConfirmSpan = document.querySelector(".close-confirm");

  document.querySelectorAll(".view-details-button").forEach((button) => {
    button.addEventListener("click", () => {
      const pedidoId = button.getAttribute("data-id");

      // Hacer una solicitud AJAX para obtener los detalles del pedido
      fetch(`/src/mozo/getOrderDetails.php?id=${encodeURIComponent(pedidoId)}`)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Error en la solicitud: " + response.status);
          }
          return response.json();
        })
        .then((data) => {
          if (data.error) {
            alert("Error: " + data.error);
            return;
          }

          // Asegúrate de que total es un número
          const total = parseFloat(data.total) || 0;
          const estadoColor = getEstadoColor(data.estado);
          const textoColor = getTextoColor(estadoColor);

          const detalleList = document.getElementById("pedidoDetalles");
          const productosList = document.getElementById("productosDetalles");
          detalleList.innerHTML = `
                      <li><span>ID Pedido:</span> ${data.idPedido}</li>
                      <li><span>Número de Pedido en Sucursal:</span> ${
                        data.numeroPedidoSucursal
                      }</li>
                      <li><span>Número de Mesa:</span> ${
                        data.numeroMesa || "N/A"
                      }</li>
                      <li><span>Tipo de Pedido:</span> ${data.tipoPedido}</li>
                      <li><span>Total:</span> ${total.toFixed(2)} €</li>
                      <li><span>Hora de Recogida:</span> ${
                        data.horaRecogida
                      }</li>
                      <li><span>Sucursal:</span> ${data.sucursal}</li>
                      <li><span>Notas:</span> ${data.notas || "N/A"}</li>
                      <li><span>Método de Pago:</span> ${data.metodoPago}</li>
                      <li><span>Fecha de Pedido:</span> ${new Date(
                        data.fechaPedido
                      ).toLocaleString()}</li>
                      <li><span>Estado:</span> <span style="background-color:${estadoColor}; color: ${textoColor}; padding: 2px 4px; border-radius: 4px;">${
            data.estado
          }</span></li>
                  `;

          // Renderizar productos
          if (data.productos && data.productos.length > 0) {
            const productosHTML = data.productos
              .map(
                (producto) => `
                          <li>
                              <span>Nombre:</span> ${producto.nombre} - 
                              <span>Precio:</span> ${producto.precio} € - 
                              <span>Cantidad:</span> ${producto.cantidad}
                          </li>
                      `
              )
              .join("");
            productosList.innerHTML = `<ul>${productosHTML}</ul>`;
          } else {
            productosList.innerHTML =
              "<p>No hay productos para este pedido.</p>";
          }

          // Mostrar el botón de cancelar si el estado es 'Pendiente'
          if (data.estado === "Pendiente") {
            cancelOrderButton.style.display = "block";
            cancelOrderButton.onclick = () => {
              modal.style.display = "none"; // Ocultar modal principal
              confirmCancelModal.style.display = "block"; // Mostrar modal de confirmación
            };

            // Confirmar cancelación
            confirmCancelButton.onclick = () => {
              const notas = cancelNotes.value;
              fetch("/src/mozo/cancelOrder.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                  id: pedidoId,
                  notas: notas,
                }),
              })
                .then((response) => response.json())
                .then((result) => {
                  if (result.success) {
                    alert("Pedido cancelado con éxito");
                    confirmCancelModal.style.display = "none";
                    window.location.reload(); // Recarga la página para actualizar la lista de pedidos
                  } else {
                    alert("Error al cancelar el pedido: " + result.message);
                  }
                })
                .catch((error) => {
                  console.error("Error al cancelar el pedido:", error);
                  alert("Ocurrió un error al cancelar el pedido.");
                });
            };

            // Cancelar la cancelación
            cancelCancelButton.onclick = () => {
              confirmCancelModal.style.display = "none"; // Ocultar modal de confirmación
              modal.style.display = "block"; // Volver a mostrar modal principal
            };
          } else {
            cancelOrderButton.style.display = "none";
          }

          modal.style.display = "block";
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Ocurrió un error al obtener los detalles del pedido.");
        });
    });
  });

  // Cerrar el modal principal
  span.onclick = () => {
    modal.style.display = "none";
  };

  window.onclick = (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  };

  // Cerrar el modal de confirmación
  closeConfirmSpan.onclick = () => {
    confirmCancelModal.style.display = "none";
  };
  const getEstadoColor = (estado) => {
    switch (estado) {
      case "Pendiente":
        return "#FFF3CD";
      case "En Preparación":
        return "#61c0bf";
      case "Listo para Recoger":
        return "#D5EDDB";
      case "Completado":
        return "#F9D7DB";
      case "Cancelado":
        return "#777";
      default:
        return "#fff";
    }
  };

  const getTextoColor = (fondo) => {
    // Convertir color hexadecimal a RGB
    const r = parseInt(fondo.substring(1, 3), 16);
    const g = parseInt(fondo.substring(3, 5), 16);
    const b = parseInt(fondo.substring(5, 7), 16);

    // Calcular el contraste
    const contraste = (r * 299 + g * 587 + b * 114) / 1000;
    return contraste > 128 ? "black" : "white";
  };


  let qrScanner; 
  
  document.addEventListener('DOMContentLoaded', function() {
    const scanBtn = document.getElementById('scanBtn');
  
    function checkDevice() {
        // Check if the screen width is less than or equal to 1024 pixels (tablet threshold)
        if (window.innerWidth <= 1024) {
            scanBtn.style.display = 'block'; // Show the button
        } else {
            scanBtn.style.display = 'none'; // Hide the button
        }
    }
  
    // Run on page load
    checkDevice();
  
    // Optionally, check on window resize
    window.addEventListener('resize', checkDevice);
  });
            document.getElementById('scanBtn').addEventListener('click', function() {
                videoModal.style.display = 'flex'; // Mostrar el modal
                qrScanner = new QrScanner(video, result => {
                    console.log('Código QR detectado:', result);
  
                    // Enviar el código QR al servidor
                    fetch('qr.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                qrData: result
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log("Respuesta del servidor:", data);
                            if (data.status === 'success') {
                                // Mostrar modal con detalles del pedido
                                showDetailsModal(data.data, data.detalles, data.message);
                                videoModal.style.display = 'none';
                            } else {}
                        })
                        .catch(error => {
                            console.error("Error al enviar los datos al servidor:", error);
  
                        });
                });
  
                qrScanner.start().then(() => {
                    console.log("Escáner iniciado correctamente");
                }).catch(err => {
                    videoModal.style.display = 'none'; // Ocultar el modal si hay error
                    console.error("Error al iniciar el escáner:", err);
                });
            });
            document.querySelector('.close-modal').addEventListener('click', function() {
                if (qrScanner) {
                    qrScanner.stop(); // Detener el escáner si está activo
                }
                videoModal.style.display = 'none'; // Ocultar el modal
            });
  
            function getValueOrZero(value) {
                return (value === null || value === undefined || value === "") ? 0 : value;
            }
            // Función para mostrar el modal de detalles del pedido
            function showDetailsModal(data, detalles, message) {
                const detalleModal = document.getElementById('detalleModal');
                const pedidoDetalles = document.getElementById('pedidoDetalles');
                const productosDetalles = document.getElementById('productosDetalles');
                const responseDiv = document.getElementById('response');
  
                const total = parseFloat(data.total) || 0;
                const estadoColor = getEstadoColor(data.estado);
                const textoColor = getTextoColor(estadoColor);
  
                // Clear previous details
                pedidoDetalles.innerHTML = '';
                productosDetalles.innerHTML = '';
  
                // Add order information
                pedidoDetalles.innerHTML = `
    <li><span>ID Pedido:</span> ${data.idPedido}</li>
    <li><span>Código de Pedido:</span> ${data.codigoVerificacion || "N/A"}</li>
    <li><span>Número de Pedido en Sucursal:</span> ${data.numeroPedidoSucursal}</li>
    <li><span>Número de Mesa:</span> ${data.numeroMesa || "N/A"}</li>
    <li><span>Tipo de Pedido:</span> ${data.tipoPedido}</li>
    <li><span>Total:</span> ${total.toFixed(2)} €</li>
    <li><span>Hora de Recogida:</span> ${data.horaRecogida || "N/A"}</li>
    <li><span>Sucursal:</span> ${data.nombreSucursal || "N/A"}</li>
    <li><span>Notas:</span> ${data.notas || "N/A"}</li>
    <li><span>Método de Pago:</span> ${data.metodoPago || "N/A"}</li>
    <li><span>Fecha de Pedido:</span> ${new Date(data.fechaPedido).toLocaleString() || "N/A"}</li>
    <li><span>Estado:</span> <span style="background-color:${estadoColor}; color: ${textoColor}; padding: 2px 4px; border-radius: 4px;">${data.estado}</span></li>
  `;
  
                // If there are product details to display, do so
                detalles.forEach(detalle => {
                    const div = document.createElement('div');
                    div.textContent = `Producto: ${detalle.nombreProducto}, Cantidad: ${detalle.cantidad}, Precio: ${detalle.precio}`;
                    productosDetalles.appendChild(div);
                });
  
                // Update the response div with the server message
                const isSuccess = message.includes("válido");
                responseDiv.innerHTML = "Respuesta del servidor: " + message;
                responseDiv.style.color = isSuccess ? 'green' : 'red'; // Green for valid, red for invalid
                detalleModal.style.display = 'block'; // Show the details modal
            }
            // Cerrar el modal al hacer clic en el botón de cerrar
            document.querySelector('.close').addEventListener('click', function() {
                detalleModal.style.display = 'none'; // Ocultar el modal
            });



});

