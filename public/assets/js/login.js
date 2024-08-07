document.addEventListener('DOMContentLoaded', () => {
    const signInButton = document.getElementById('signIn');
    const signUpButton = document.getElementById('signUp');
    const container = document.getElementById('container');
    const form = document.getElementById('registroForm');

    if (signInButton && signUpButton && container) {
        signUpButton.addEventListener('click', () => {
            container.classList.add('right-panel-active');
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove('right-panel-active');
        });
    }

    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            // Validar los campos
            const { nombre, apellido, correo, genero, telefono, contrasena } = data;
            if (!nombre || !apellido || !correo || !genero || !telefono || !contrasena) {
                alert('Todos los campos son obligatorios.');
                return;
            }

            try {
                const response = await fetch('/src/db/Pruebaborrar.php', {
                    method: 'POST',
                    body: formData
                });

                const text = await response.text();
                console.log(text); // Agrega esto para ver la respuesta completa

                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const result = JSON.parse(text);
                    if (result.status === 'success') {
                        alert('Registro exitoso');
                    } else {
                        alert(result.message);
                    }
                } else {
                    alert('Error: Respuesta inesperada del servidor.');
                }
            } catch (error) {
                console.log(`Error: ${error.message}`);
            }
        });
    }
});