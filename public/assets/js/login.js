document.addEventListener('DOMContentLoaded', () => {
    const signInButton = document.getElementById('signIn');
    const signUpButton = document.getElementById('signUp');
    const container = document.getElementById('container');

    if (signInButton && signUpButton && container) {
        signUpButton.addEventListener('click', () => {
            container.classList.add('right-panel-active');
        });
    
        signInButton.addEventListener('click', () => {
            container.classList.remove('right-panel-active');
        });
    }
    
    // Validaciones de registro
    document.querySelector('#registroForm').addEventListener('submit', function (event) {
        event.preventDefault();
    
        let formData = new FormData(this);
    
        fetch('/src/db/registro.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Redirigir a index.php con el parámetro showModal
                    window.location.href = 'https://cafesabrosos.myvnc.com/index.php?showModal=true';
                } else {
                    const errorContainer = document.querySelector('#registroForm #error-container2');
                    if (errorContainer) {
                        errorContainer.textContent = data.message;
                    } else {
                        // eslint-disable-next-line no-console
                        console.error('Error: No se encontró el contenedor de errores #error-container2');
                    }
                }
            })
            .catch(error => {
                // eslint-disable-next-line no-console
                console.error('Error:', error);
                const errorContainer = document.querySelector('#registroForm #error-container2');
                if (errorContainer) {
                    errorContainer.textContent = 'Se ha producido un error al procesar tu solicitud.';
                } else {
                    // eslint-disable-next-line no-console
                    console.error('Error: No se encontró el contenedor de errores #error-container2');
                }
            });
    });
    
    // Validaciones de inicio de sesión
    document.getElementById('loginForm').addEventListener('submit', function (event) {
        event.preventDefault();
    
        let formData = new FormData(this);
    
        fetch('/src/db/login.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                // eslint-disable-next-line no-console
                console.log('Respuesta del servidor:', data); // Imprime la respuesta del servidor en la consola
    
                const errorContainer = document.querySelector('#loginForm #error-container');
                if (data.success) {
                    // Guardar el ID del usuario en localStorage
                    if (data.user_id) {
                        localStorage.setItem('user_id', data.user_id);
                        window.location.href = data.redirect; // Redirige en caso de éxito
                    } else {
                        // eslint-disable-next-line no-console
                        console.error('Error: user_id no está definido en la respuesta del servidor');
                    }
                } else {
                    if (errorContainer) {
                        errorContainer.textContent = data.message; // Muestra mensaje de error
                    } else {
                        // eslint-disable-next-line no-console
                        console.error('Error: No se encontró el contenedor de errores #error-container');
                    }
                }
            })
            .catch(error => {
                const errorContainer = document.querySelector('#loginForm #error-container');
                if (errorContainer) {
                    errorContainer.textContent = 'Se ha producido un error al procesar tu solicitud.';
                } else {
                    // eslint-disable-next-line no-console
                    console.error('Error: No se encontró el contenedor de errores #error-container');
                }
            });
    });



    // Validaciones de registro
    document.querySelector('#registroForm').addEventListener('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch('/src/db/registro.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Redirigir a index.php con el parámetro showModal
                    window.location.href = 'https://cafesabrosos.myvnc.com/index.php?showModal=true';
                } else {
                    const errorContainer = document.querySelector('#registroForm #error-container2');
                    if (errorContainer) {
                        errorContainer.textContent = data.message;
                    } else {
                        // eslint-disable-next-line no-console
                        console.error('Error: No se encontró el contenedor de errores #error-container2');
                    }
                }
            })
            .catch(error => {
                // eslint-disable-next-line no-console
                console.error('Error:', error);
                const errorContainer = document.querySelector('#registroForm #error-container2');
                if (errorContainer) {
                    errorContainer.textContent = 'Se ha producido un error al procesar tu solicitud.';
                } else {
                    // eslint-disable-next-line no-console
                    console.error('Error: No se encontró el contenedor de errores #error-container2');
                }
            });
    });

    // Validaciones de inicio de sesión
    document.getElementById('loginForm').addEventListener('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch('/src/db/login.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                // eslint-disable-next-line no-console
                console.log('Respuesta del servidor:', data); // Imprime la respuesta del servidor en la consola

                const errorContainer = document.querySelector('#loginForm #error-container');
                if (data.success) {
                    // Guardar el ID del usuario en localStorage
                    if (data.user_id) {
                        localStorage.setItem('user_id', data.user_id);
                        window.location.href = data.redirect; // Redirige en caso de éxito
                    } else {
                        // eslint-disable-next-line no-console
                        console.error('Error: user_id no está definido en la respuesta del servidor');
                    }
                } else {
                    if (errorContainer) {
                        errorContainer.textContent = data.message; // Muestra mensaje de error
                    } else {
                        // eslint-disable-next-line no-console
                        console.error('Error: No se encontró el contenedor de errores #error-container');
                    }
                }
            })
            .catch(error => {
                const errorContainer = document.querySelector('#loginForm #error-container');
                if (errorContainer) {
                    errorContainer.textContent = 'Se ha producido un error al procesar tu solicitud.';
                } else {
                    // eslint-disable-next-line no-console
                    console.error('Error: No se encontró el contenedor de errores #error-container');
                }
            });
    });
});
