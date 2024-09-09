document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('reservaModal');
    const avisoModal = document.getElementById('avisoModal');
    const btnReservar = document.querySelectorAll('.btn-reservar');
    const spanClose = document.getElementsByClassName('close')[0];
    const avisoClose = document.getElementsByClassName('aviso-close')[0];
    const reservaForm = document.getElementById('reservaForm');
    const cantidadPersonasSelect = document.getElementById('cantidadPersonas');
    const fechaReservaInput = document.getElementById('fechaReserva');
    const horaReservaInput = document.getElementById('horaReserva');
    const errorReserva = document.getElementById('errorReserva');
    let capacidadMesa = 0;

    function openModal(mesaId, capacidad) {
        // Aquí se verificará la sesión del usuario mediante una llamada a la API
        fetch('/src/db/checkSession.php')
            .then(response => response.json())
            .then(data => {
                if (!data.loggedIn) {
                    avisoModal.style.display = 'block';
                    avisoClose.addEventListener('click', function() {
                        avisoModal.style.display = 'none';
                        window.location.href = '/public/login.html';
                    });
                    return;
                }
                capacidadMesa = capacidad;
                document.getElementById('mesaId').value = mesaId;
                document.getElementById('sucursalId').value = '<?php echo $sucursalId; ?>';
                fillCantidadPersonas(capacidad);
                modal.style.display = 'block';
            });
    }

    // Función para llenar el selector con opciones de cantidad de personas
    function fillCantidadPersonas(max) {
        cantidadPersonasSelect.innerHTML = '';
        for (let i = 1; i <= max; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            cantidadPersonasSelect.appendChild(option);
        }
    }

    // Validar la hora seleccionada
    function validateHora(hora) {
        const [hours] = hora.split(':').map(Number);
        return hours >= 8 && hours < 20; // Entre 8:00 AM y 8:00 PM
    }

    // Manejar clic en el botón de reservar
    btnReservar.forEach(button => {
        button.addEventListener('click', function() {
            const mesaId = this.getAttribute('data-mesa-id');
            const capacidad = parseInt(this.getAttribute('data-capacidad'), 10);
            openModal(mesaId, capacidad);
        });
    });

    // Manejar clic en la X para cerrar el modal
    spanClose.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Manejar clic fuera del modal para cerrarlo
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Restricciones para el campo de hora
    horaReservaInput.addEventListener('change', function() {
        if (!validateHora(this.value)) {
            alert('La hora debe estar entre las 8:00 AM y las 8:00 PM.');
            this.value = '';
        }
    });

    // Capturar el envío del formulario
    reservaForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar envío por defecto

        // Crear objeto con los datos del formulario
        const formData = new FormData(reservaForm);

        // Enviar datos con fetch
        fetch(reservaForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = `/public/confirmacion.php?codigoReserva=${encodeURIComponent(data.codigoReserva)}`;
            } else {
                errorReserva.textContent = data.message;
                errorReserva.style.display = 'block'; // Mostrar el mensaje de error
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            errorReserva.textContent = 'Ocurrió un error al procesar la reserva.';
            errorReserva.style.display = 'block';
        });
    });
});