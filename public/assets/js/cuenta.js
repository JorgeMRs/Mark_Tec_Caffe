document.addEventListener("DOMContentLoaded", function () {
  const avatarInput = document.getElementById("avatar");
  const avatarImage = document.querySelector(".avatar-image");
  const successAvatarDiv = document.querySelector(".success-avatar");
  const errorAvatarDiv = document.querySelector(".error-avatar");
  const deleteAvatarBtn = document.getElementById("deleteAvatarBtn");
  const cropperModal = document.getElementById("cropperModal");
  const cropperImage = document.getElementById("cropperImage");
  const cropImageBtn = document.getElementById("cropImageBtn");
  const cancelCropBtn = document.getElementById("cancelCropBtn");
  const closeCropperModal = document.querySelector(".cropper-close-button"); // Actualizado para usar la clase
  let cropper;

  function limpiarMensajes() {
    if (errorAvatarDiv) {
      errorAvatarDiv.style.display = "none";
    }
    if (successAvatarDiv) {
      successAvatarDiv.style.display = "none";
    }
  }

  if (avatarInput) {
    avatarInput.addEventListener("change", function (event) {
      limpiarMensajes();
      const file = event.target.files[0];

      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          cropperImage.src = e.target.result;
          cropperModal.style.display = "flex";

          cropperImage.onerror = function () {
            cropperModal.style.display = "none"; // Ocultar el modal
            errorAvatarDiv.textContent = "Error: No se pudo cargar la imagen. La imagen puede estar corrupta.";
            errorAvatarDiv.style.display = "block";
            avatarInput.value = ""; // Resetear el input
          };

          if (cropper) {
            cropper.destroy();
          }

          cropper = new Cropper(cropperImage, {
            aspectRatio: 1, // Puedes ajustar la relación de aspecto
            viewMode: 1,
          });
        };
        reader.readAsDataURL(file);
      }
    });
  }

  cropImageBtn.addEventListener("click", function () {
    const canvas = cropper.getCroppedCanvas();
    canvas.toBlob(function (blob) {
      const formData = new FormData();
      formData.append("avatar", blob, "avatar.png");

      fetch("/src/client/avatarUpload.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            avatarImage.src =
              "/public/assets/img/avatars/" +
              encodeURIComponent(data.avatar) +
              "?t=" +
              new Date().getTime();
            successAvatarDiv.textContent = data.message;
            successAvatarDiv.style.display = "block";
            errorAvatarDiv.style.display = "none";
            deleteAvatarBtn.style.display = "block";
            cropperModal.style.display = "none";
            avatarInput.value = "";
          } else {
            errorAvatarDiv.textContent = data.message;
            errorAvatarDiv.style.display = "block";
            successAvatarDiv.style.display = "none";
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          errorAvatarDiv.textContent =
            "Error al subir el avatar. Por favor, intenta de nuevo.";
          errorAvatarDiv.style.display = "block";
          successAvatarDiv.style.display = "none";
        });
    }, "image/png");
  });

  cancelCropBtn.addEventListener("click", function () {
    cropperModal.style.display = "none";
    avatarInput.value = ""; // Resetea el campo de archivo
  });

  closeCropperModal.addEventListener("click", function () {
    cropperModal.style.display = "none";
    avatarInput.value = ""; // Resetea el campo de archivo
  });

  if (deleteAvatarBtn) {
    deleteAvatarBtn.addEventListener("click", function (event) {
      event.preventDefault();
      limpiarMensajes();

      if (confirm("¿Estás seguro de que deseas eliminar tu avatar?")) {
        fetch("/src/client/avatarDelete.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            action: "deleteAvatar",
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              avatarImage.src =
                "/public/assets/img/user-circle-svgrepo-com.svg";
              deleteAvatarBtn.style.display = "none";
              successAvatarDiv.textContent = data.message;
              successAvatarDiv.style.display = "block";
              errorAvatarDiv.style.display = "none";
              avatarInput.value = "";
            } else {
              errorAvatarDiv.textContent = data.message;
              errorAvatarDiv.style.display = "block";
              successAvatarDiv.style.display = "none";
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            errorAvatarDiv.textContent =
              "Error al eliminar el avatar. Por favor, intenta de nuevo.";
            errorAvatarDiv.style.display = "block";
            successAvatarDiv.style.display = "none";
          });
      }
    });
  }

  // Handle the first modal for account deletion
  document.getElementById("deleteAccountBtn").onclick = function () {
    document.getElementById("deleteAccountModal").style.display = "block";
  };

  document.getElementById("cancelDeleteBtn").onclick = function () {
    document.getElementById("deleteAccountModal").style.display = "none";
  };

  // Random code generation
  function generateRandomCode() {
    return Math.floor(100000 + Math.random() * 900000); // Generates a 6-digit random number
  }

  // Handle the second modal for code verification
// Handle the second modal for code verification
document.getElementById("confirmDeleteBtn").onclick = function () {
  // Close the first modal
  document.getElementById("deleteAccountModal").style.display = "none";

  // Generate and display the random code in the second modal
  var randomCode = generateRandomCode();
  document.getElementById("generatedCode").textContent =
    "Código: " + randomCode;

  // Show the second modal
  document.getElementById("codeVerificationModal").style.display = "block";

  // Verify the code
  document.getElementById("verifyCodeBtn").onclick = function () {
      var userCode = document.getElementById("userInputCode").value;
      if (userCode == randomCode) {
          // Fetch user ID
          fetch("/src/db/checkSession.php")
            .then((response) => response.json())
            .then((sessionData) => {
                if (sessionData.loggedIn) {
                    const userId = sessionData.userId;
                    // Proceed with account deactivation
                    fetch("/src/client/account/accountDelete.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            action: "deactivateAccount",
                            user_id: userId,
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Redirect user with parameter in the URL
                            window.location.href = "/?accountDeactivated=true";
                        } else {
                            alert("Error al desactivar la cuenta: " + data.message);
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        alert(
                            "Error al desactivar la cuenta. Por favor, intenta de nuevo."
                        );
                    });
                } else {
                    alert(
                        "No se pudo verificar la sesión. Por favor, inicia sesión nuevamente."
                    );
                }
            })
            .catch((error) => {
                console.error("Error al verificar la sesión:", error);
                alert("Error al verificar la sesión. Por favor, intenta de nuevo.");
            });
      } else {
          alert("Código incorrecto. Inténtalo de nuevo.");
      }
  };
};

document.getElementById("backToDeleteModalBtn").onclick = function () {
  document.getElementById("codeVerificationModal").style.display = "none";
  document.getElementById("deleteAccountModal").style.display = "block";
};
});



let selectedPedidoId = null;

// Función para mostrar el modal de confirmación de cancelación
function showCancelConfirmationModal(pedidoId) {
    selectedPedidoId = pedidoId;
    document.getElementById('cancelConfirmationModal').style.display = 'flex';
}

// Función para cerrar el modal de confirmación de cancelación
function closeCancelConfirmationModal() {
    document.getElementById('cancelConfirmationModal').style.display = 'none';
    selectedPedidoId = null;
}

// Función para confirmar la cancelación del pedido
function confirmCancelPedido() {
  if (selectedPedidoId) {
      const notes = document.getElementById('cancelNotes').value;
      fetch('/src/client/cancelOrder.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
              idPedido: selectedPedidoId,
              notas: notes,
          }),
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              alert('Pedido cancelado exitosamente.');
              closeCancelConfirmationModal();
              renderPedidos(); // Actualiza la lista de pedidos
          } else {
              alert('Error al cancelar el pedido: ' + data.message);
          }
      })
      .catch(error => console.error('Error al cancelar el pedido:', error));
  }
}

document.addEventListener('DOMContentLoaded', function() {
    // Obtener referencias al modal de pedidos, botón de cancelar y nuevo modal
    const pedidosModal = document.getElementById('pedidosModal');
    const cancelConfirmationModal = document.getElementById('cancelConfirmationModal');
    const pedidosBtn = document.getElementById('viewPedidosBtn');
    const closeModal = document.querySelector('#pedidosModal .close');
    const cancelModalClose = document.querySelector('#cancelConfirmationModal .cancel-close');
    const confirmCancelBtn = document.getElementById('confirmCancel');
    const cancelCancelBtn = document.getElementById('cancelCancel');
    const cancelNotes = document.getElementById('cancelNotes');
    let pedidosData = [];
    let currentPage = 1;
    const itemsPerPage = 2;

    // Función para renderizar los pedidos en la página actual
    function renderPedidos() {
      const pedidosList = document.getElementById('pedidosList');
      pedidosList.innerHTML = ''; // Limpiar la lista de pedidos
  
      const startIndex = (currentPage - 1) * itemsPerPage;
      const endIndex = startIndex + itemsPerPage;
      const paginatedPedidos = pedidosData.slice(startIndex, endIndex);
  
      if (paginatedPedidos.length > 0) {
          paginatedPedidos.forEach(pedido => {
              const pedidoItem = document.createElement('div');
              pedidoItem.className = 'pedido-item';
              
              let productosHTML = '';
              if (pedido.productos && pedido.productos.length > 0) {
                  productosHTML = '<div class="productos">';
                  pedido.productos.forEach(producto => {
                      productosHTML += `
                          <div class="producto">
                              <div class="producto-name">${producto.nombre}</div>
                              <div class="producto-price">$${producto.precio} x ${producto.cantidad}</div>
                          </div>
                      `;
                  });
                  productosHTML += '</div>';
              } else {
                  productosHTML = '<p>No hay productos para este pedido.</p>';
              }
  
              pedidoItem.innerHTML = `
                  <h3>Pedido #${pedido.idPedido}</h3>
                  <p><strong>Fecha:</strong> ${pedido.fechaPedido}</p>
                  <p><strong>Estado:</strong> ${pedido.estado}</p>
                  <p><strong>Total:</strong> $${pedido.total}</p>
                  ${pedido.fechaCancelacion ? `<p><strong>Fecha de Cancelación:</strong> ${pedido.fechaCancelacion}</p>` : ''}
                  <div class="productos">${productosHTML}</div>
                  ${pedido.estado === 'Pendiente' ? '<button class="view-pedidos-btn" onclick="showCancelConfirmationModal(' + pedido.idPedido + ')">Cancelar Pedido</button>' : ''}
              `;
              pedidosList.appendChild(pedidoItem);
          });
  
          renderPaginationControls();
      } else {
          pedidosList.innerHTML = '<p>No tienes pedidos.</p>';
      }
  }

    // Función para renderizar los controles de paginación
    function renderPaginationControls() {
        const pagination = document.getElementById('pagination');
        const totalPages = Math.ceil(pedidosData.length / itemsPerPage);
        
        let paginationHtml = '';

        if (currentPage > 1) {
            paginationHtml += `<button class="pagination-btn" id="prevPage">Anterior</button>`;
        } else {
            paginationHtml += `<button class="pagination-btn disabled" id="prevPage" disabled>Anterior</button>`;
        }

        for (let i = 1; i <= totalPages; i++) {
            paginationHtml += `<button class="pagination-btn ${i === currentPage ? 'disabled' : ''}" data-page="${i}">${i}</button>`;
        }

        if (currentPage < totalPages) {
            paginationHtml += `<button class="pagination-btn" id="nextPage">Siguiente</button>`;
        } else {
            paginationHtml += `<button class="pagination-btn disabled" id="nextPage" disabled>Siguiente</button>`;
        }

        pagination.innerHTML = paginationHtml;

        // Añadir eventos a los botones de paginación
        document.getElementById('prevPage')?.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderPedidos();
            }
        });

        document.getElementById('nextPage')?.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderPedidos();
            }
        });

        document.querySelectorAll('#pagination .pagination-btn[data-page]').forEach(button => {
            button.addEventListener('click', () => {
                currentPage = parseInt(button.getAttribute('data-page'));
                renderPedidos();
            });
        });
    }

    // Función para abrir el modal y cargar los pedidos
    function openPedidosModal() {
        pedidosModal.style.display = 'flex';
        // Cargar pedidos mediante AJAX
        fetch('/src/client/getOrder.php')
            .then(response => response.json())
            .then(data => {
                pedidosData = data;
                currentPage = 1; // Reiniciar la página actual
                renderPedidos();
            })
            .catch(error => console.error('Error cargando los pedidos:', error));
    }

    // Abrir el modal cuando se hace clic en el botón
    if (pedidosBtn) {
        pedidosBtn.addEventListener('click', openPedidosModal);
    }

    // Cerrar el modal de pedidos cuando se hace clic en el botón de cerrar
    if (closeModal) {
        closeModal.addEventListener('click', () => {
            pedidosModal.style.display = 'none';
        });
    }

    // Cerrar el modal de confirmación de cancelación cuando se hace clic en el botón de cerrar
    if (cancelModalClose) {
        cancelModalClose.addEventListener('click', closeCancelConfirmationModal);
    }

    // Cerrar el modal de confirmación de cancelación si se hace clic fuera del contenido del modal
    window.addEventListener('click', (event) => {
        if (event.target === cancelConfirmationModal) {
            closeCancelConfirmationModal();
        }
    });

    // Confirmar la cancelación del pedido
    if (confirmCancelBtn) {
        confirmCancelBtn.addEventListener('click', confirmCancelPedido);
    }

    // Volver atrás en el modal de confirmación de cancelación
    if (cancelCancelBtn) {
        cancelCancelBtn.addEventListener('click', closeCancelConfirmationModal);
    }
});