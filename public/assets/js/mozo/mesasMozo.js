document.addEventListener("DOMContentLoaded", function () {
  const reservaModal = document.getElementById("reservaModal");
  const reservaForm = document.getElementById("reservaForm");
  const avisoModal = document.getElementById("avisoModal");
  const avisoClose = document.querySelector(".aviso-close");
  const cantidadPersonasSelect = document.getElementById("cantidadPersonas");
  const btnReservar = document.querySelectorAll(".btn-reservar");
  const errorReserva = document.getElementById("errorReserva");

  function openModal(mesaId, capacidad) {
    const sucursalId = document.body.getAttribute("data-sucursal-id");
    document.getElementById("mesaId").value = mesaId;
    document.getElementById("sucursalId").value = sucursalId;
    fillCantidadPersonas(capacidad);
    reservaModal.style.display = "block";
  }

  function fillCantidadPersonas(max) {
    cantidadPersonasSelect.innerHTML = "";
    for (let i = 1; i <= max; i++) {
      const option = document.createElement("option");
      option.value = i;
      option.textContent = i;
      cantidadPersonasSelect.appendChild(option);
    }
  }

  // Asignar evento de clic a los botones de reserva
  btnReservar.forEach((button) => {
    button.addEventListener("click", function () {
      const mesaId = this.getAttribute("data-mesa-id");
      const capacidad = parseInt(this.getAttribute("data-capacidad"), 10);
      openModal(mesaId, capacidad);
    });
  });

  // Cerrar el modal al hacer clic en la "x"
  document.querySelector(".close").addEventListener("click", function () {
    reservaModal.style.display = "none";
  });

  // Interceptar el formulario para enviarlo con fetch
  reservaForm.addEventListener("submit", function (e) {
    e.preventDefault(); // Evita que el formulario se envíe de la manera tradicional

    const formData = new FormData(reservaForm);
    console.log("Formulario enviado con:", formData); // Verifica el contenido del FormData

    // Enviar los datos con fetch a la API PHP
    fetch("/src/mozo/createReservation.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Respuesta del servidor:", data); // Muestra la respuesta del servidor
        if (data.success) {
          // Mostrar un mensaje de éxito y cerrar el modal
          alert("Reserva realizada exitosamente");
          reservaModal.style.display = "none";
          location.reload(); // Recargar la página para actualizar las reservas
        } else {
          // Mostrar el error en el formulario
          errorReserva.textContent =
            data.message || "Error al realizar la reserva";
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        errorReserva.textContent = "Ocurrió un error al procesar la reserva.";
      });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  // Existing code for "Cambiar Estado" buttons
  const botonesCambiarEstado = document.querySelectorAll(".btn-cambiar-estado");

  botonesCambiarEstado.forEach((boton) => {
    boton.addEventListener("click", function () {
      const mesaId = this.getAttribute("data-mesa-id");

      fetch("/src/mozo/cambiarEstado.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `mesa_id=${mesaId}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Actualiza la UI para la mesa
            const mesaDiv = this.closest(".mesa");
            mesaDiv.classList.remove("reservado");
            mesaDiv.classList.add("ocupada");
            mesaDiv.querySelector(".mensaje-no-disponible").textContent =
              "Mesa ocupada";

            // Oculta el botón "Cambiar Estado"
            this.style.display = "none"; // Oculta el botón "Cambiar Estado"

            // Recarga la página para mostrar los cambios
            location.reload();
          } else {
            alert(data.message);
          }
        })
        .catch((error) => console.error("Error:", error));
    });
  });

  // Add event listener for "Finalizar reserva" buttons
  const botonesFinalizarReserva = document.querySelectorAll(
    ".btn-finalizar-reserva"
  );

  botonesFinalizarReserva.forEach((boton) => {
    boton.addEventListener("click", function () {
      const mesaId = this.getAttribute("data-mesa-id");

      // Mostrar el modal de confirmación
      const modal = document.getElementById("modalConfirmacion");
      modal.style.display = "block";

      // Obtener botones de confirmar y cancelar
      const confirmarBtn = document.getElementById("confirmarFinalizar");
      const cancelarBtn = document.getElementById("cancelarFinalizar");

      // Funcionalidad para el botón de confirmar
      confirmarBtn.onclick = function () {
        fetch("/src/mozo/finalizarReserva.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `mesa_id=${mesaId}`,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              const mesaDiv = boton.closest(".mesa");
              mesaDiv.classList.remove("ocupada");
              mesaDiv.classList.add("disponible");
              mesaDiv.querySelector(".mensaje-no-disponible").textContent =
                "Mesa disponible";

              // Ocultar el botón "Finalizar reserva"
              boton.style.display = "none";

              // Recargar la página para mostrar los cambios
              location.reload();
            } else {
              alert(data.message);
            }
          })
          .catch((error) => console.error("Error:", error));

        // Ocultar el modal después de confirmar
        modal.style.display = "none";
      };

      // Funcionalidad para el botón de cancelar
      cancelarBtn.onclick = function () {
        modal.style.display = "none"; // Ocultar modal al cancelar
      };

      // Cerrar el modal al hacer clic en el "x"
      const closeModal = document.getElementsByClassName("close")[0];
      closeModal.onclick = function () {
        modal.style.display = "none";
      };

      // Cerrar el modal si el usuario hace clic fuera del contenido del modal
      window.onclick = function (event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      };
    });
  });
});

function fetchReservationDetails(mesaId) {
  fetch(`/src/mozo/obtenerReserva.php?mesaId=${mesaId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Get the reservation state
        const estado = data.reserva.estado;

        // Capitalize the first letter of the estado
        const estadoCapitalizado =
          estado.charAt(0).toUpperCase() + estado.slice(1);

        // Fetch the color based on the reservation state
        const color = getMesaEstadoColor(estado);

        // Populate the modal with reservation details
        const detailsContent = `
<p>ID Reserva: ${data.reserva.idReserva}</p>
<p>Fecha Reserva: ${data.reserva.fechaReserva}</p>
<p style="background-color: ${
          estado === "reservado" || estado === "ocupado" ? color : "transparent"
        }; 
       padding: 5px; 
       border-radius: 5px; 
       font-weight: bold;">
Estado: ${estadoCapitalizado}
</p>
<p>Cantidad de Personas: ${data.reserva.cantidadPersonas}</p>
`;
        document.getElementById("detallesReservaContent").innerHTML =
          detailsContent;
        document.getElementById("detallesModal").style.display = "block";
      } else {
        alert("No se pudo obtener los detalles de la reserva.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error al obtener los detalles de la reserva.");
    });
}

// Function to get color based on the estado (to be added in your JavaScript)
function getMesaEstadoColor(estado) {
  switch (estado) {
    case "reservado":
      return "#FFF3CD"; // Light yellow
    case "ocupado":
      return "#F8D7DA"; // Light blue for "ocupado"
    case "disponible":
      return "#D5EDDB"; // Light green
    case "cancelado":
      return "#F9D7DB"; // Light red
    case "finalizado":
      return "#777"; // Gray
    default:
      return "#fff"; // Default white
  }
}

function closeDetailsModal() {
  document.getElementById("detallesModal").style.display = "none";
}

function openCancelModal(reservaId) {
  const modal = document.getElementById("cancelModal");
  modal.style.display = "block";

  const confirmButton = document.getElementById("confirmCancel");
  confirmButton.onclick = function () {
    const notes = document.getElementById("notes").value;

    if (!notes) {
      alert("Las notas son obligatorias.");
      return;
    }

    // Llamar a la función de cancelación con la reserva y las notas
    cancelReservation(reservaId, notes);
    modal.style.display = "none"; // Cerrar el modal después de enviar
  };

  const closeModal = document.getElementById("closeModal");
  closeModal.onclick = function () {
    modal.style.display = "none"; // Cerrar el modal al hacer clic en la "X"
  };

  const cancelButton = document.getElementById("cancelButton");
  cancelButton.onclick = function () {
    modal.style.display = "none"; // Cerrar el modal al hacer clic en "Cancelar"
  };

  // Cerrar el modal al hacer clic fuera de él
  window.onclick = function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  };
}

// Modificar la función cancelReservation para incluir notas
function cancelReservation(reservaId, notes) {
  fetch("/src/mozo/cancelReservation.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      idReserva: reservaId,
      notes: notes, // Incluir notas en el cuerpo de la solicitud
    }),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error en la cancelación de la reserva");
      }
      return response.json();
    })
    .then((data) => {
      // Mostrar mensaje de éxito o error en el modal
      openConfirmationModal();
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("No se pudo cancelar la reserva. Inténtalo de nuevo más tarde.");
    });
}

function openConfirmationModal() {
    const confirmationModal = document.getElementById("confirmationModal");
    confirmationModal.style.display = "block";

    const closeConfirmationModal = document.getElementById("closeConfirmationModal");
    closeConfirmationModal.onclick = function () {
        confirmationModal.style.display = "none"; // Cerrar el modal al hacer clic en la "X"
    };

    const okButton = document.getElementById("okButton");
    okButton.onclick = function () {
        confirmationModal.style.display = "none"; // Cerrar el modal al hacer clic en "OK"
        location.reload(); // Recargar la página después de cerrar el modal
    };

}