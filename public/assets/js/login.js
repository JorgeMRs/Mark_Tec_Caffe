document.addEventListener('DOMContentLoaded', () => {
    const signInButton = document.getElementById('signIn');
    const signUpButton = document.getElementById('signUp');
    const container = document.getElementById('container');

<<<<<<< HEAD
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
    
=======
	if (signInButton && signUpButton && container) {
		signUpButton.addEventListener('click', () => {
			container.classList.add('right-panel-active');
		});

		signInButton.addEventListener('click', () => {
			container.classList.remove('right-panel-active');
		});
	}

// Validaciones de registro
document.querySelector('#registroForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Validación básica del lado del cliente
    let isValid = true;
    const email = document.querySelector('#registroForm input[name="email"]').value;
    const password = document.querySelector('#registroForm input[name="password"]').value;
    const passwordConfirm = document.querySelector('#registroForm input[name="passwordConfirm"]').value;
    const recaptchaResponse = grecaptcha.getResponse(); // Obtener el token de reCAPTCHA

    const errorContainer = document.querySelector('#registroForm #error-container2');
    errorContainer.textContent = ''; // Limpiar errores previos

    if (!email || !password || !passwordConfirm) {
        isValid = false;
        errorContainer.textContent = 'Por favor, completa todos los campos.';
    }
    if (password !== passwordConfirm) {
        isValid = false;
        errorContainer.textContent = 'Las contraseñas no coinciden.';
    }
    if (password.length < 8) {
        isValid = false;
        errorContainer.textContent = 'La contraseña debe tener al menos 8 caracteres.';
    }
    if (!recaptchaResponse) {
        isValid = false;
        errorContainer.textContent = 'Por favor, completa el reCAPTCHA.';
    }

    if (isValid) {
        // Mostrar el modal de carga inmediatamente al enviar el formulario
        document.getElementById('loadingModal').style.display = 'flex';

        let formData = new FormData(this);
<<<<<<< HEAD
        
>>>>>>> 07c1bbe16b8418eff07af61e63f38a94b7f0d21a
=======
        formData.append('g-recaptcha-response', recaptchaResponse); // Añadir el token de reCAPTCHA

>>>>>>> e82cc75a02b7f17ee0984c21538c82723fdb7cc9
        fetch('/src/db/registro.php', {
            method: 'POST',
            body: formData
        })
<<<<<<< HEAD
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
    
=======
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                setTimeout(function() {
                    window.location.href = 'https://cafesabrosos.myvnc.com/index.php?showModal=true';
                }, 1500);
            } else {
                errorContainer.textContent = data.message;
                // Reiniciar el reCAPTCHA en caso de error
                grecaptcha.reset();
                // Ocultar el modal si hay un error
                document.getElementById('loadingModal').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorContainer.textContent = 'Se ha producido un error al procesar tu solicitud.';
            // Reiniciar el reCAPTCHA en caso de error
            grecaptcha.reset();
            // Ocultar el modal en caso de error
            document.getElementById('loadingModal').style.display = 'none';
        });
    }
});



// Validaciones de inicio de sesión
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    let isValid = true;
    const email = document.querySelector('#loginForm input[name="email"]').value;
    const password = document.querySelector('#loginForm input[name="password"]').value;

    if (!email || !password) {
        isValid = false;
    }

    if (isValid) {
        document.getElementById('loadingModal').style.display = 'flex';

        let formData = new FormData(this);
<<<<<<< HEAD
        
>>>>>>> 07c1bbe16b8418eff07af61e63f38a94b7f0d21a
=======

>>>>>>> e82cc75a02b7f17ee0984c21538c82723fdb7cc9
        fetch('/src/db/login.php', {
            method: 'POST',
            body: formData
        })
<<<<<<< HEAD
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
=======
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const errorContainer = document.querySelector('#loginForm #error-container');
                errorContainer.textContent = '';
                setTimeout(function() {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                const errorContainer = document.querySelector('#loginForm #error-container');
                errorContainer.textContent = data.message;
                // Ocultar el modal si hay un error
                document.getElementById('loadingModal').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorContainer = document.querySelector('#loginForm #error-container');
            errorContainer.textContent = '';
            errorContainer.textContent = 'Se ha producido un error al procesar tu solicitud.';
            // Ocultar el modal en caso de error
            document.getElementById('loadingModal').style.display = 'none';
        });
    } else {
        // Mostrar un mensaje de error de validación si el formulario no es válido
        const errorContainer = document.querySelector('#loginForm #error-container');
        errorContainer.textContent = '';
        errorContainer.textContent = 'Por favor, completa todos los campos correctamente.';
    }
});

});
  
>>>>>>> 07c1bbe16b8418eff07af61e63f38a94b7f0d21a
