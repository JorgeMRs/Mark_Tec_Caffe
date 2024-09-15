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
        const sucursalId = document.body.getAttribute('data-sucursal-id');
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
            
            if (data.role === 'Mozo' || data.userId) {
                capacidadMesa = capacidad;
                document.getElementById('mesaId').value = mesaId;
                document.getElementById('sucursalId').value = sucursalId;
                fillCantidadPersonas(capacidad);
                modal.style.display = 'block';
            } else {
                alert("Solo clientes o mozos pueden realizar reservas.");
            }
        });
    }
    function fillCantidadPersonas(max) {
        cantidadPersonasSelect.innerHTML = '';
        for (let i = 1; i <= max; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            cantidadPersonasSelect.appendChild(option);
        }
    }

    function validateHora(hora) {
        const [hours] = hora.split(':').map(Number);
        return hours >= 8 && hours < 20; // Entre 8:00 AM y 8:00 PM
    }

    btnReservar.forEach(button => {
        button.addEventListener('click', function() {
            const mesaId = this.getAttribute('data-mesa-id');
            const capacidad = parseInt(this.getAttribute('data-capacidad'), 10);
            openModal(mesaId, capacidad);
        });
    });
    spanClose.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    horaReservaInput.addEventListener('change', function() {
        if (!validateHora(this.value)) {
            alert('La hora debe estar entre las 8:00 AM y las 8:00 PM.');
            this.value = '';
        }
    });

    reservaForm.addEventListener('submit', function(event) {
        event.preventDefault();
        console.log({
            fechaReserva: fechaReservaInput.value,
            horaReserva: horaReservaInput.value,
            mesaId: document.getElementById('mesaId').value,
            sucursalId: document.getElementById('sucursalId').value,
            cantidadPersonas: cantidadPersonasSelect.value
        });

        const formData = new FormData(reservaForm);

        // enviar datos con fetch
        fetch(reservaForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = `/public/confirmacionReserva.php?codigoReserva=${encodeURIComponent(data.codigoReserva)}`;
            } else {
                errorReserva.textContent = data.message;
                errorReserva.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            errorReserva.textContent = 'Ocurrió un error al procesar la reserva.';
            errorReserva.style.display = 'block';
        });
    });
});