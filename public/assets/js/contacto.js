        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#contact-form');
            const loader = document.querySelector('.loader');
            const submitText = document.querySelector('.submit-text');
            const responseMessage = document.querySelector('#response-message');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);
                const captchaResponse = grecaptcha.getResponse(); // Obtener el token de reCAPTCHA

                if (!captchaResponse) {
                    responseMessage.textContent = 'Por favor, completa el reCAPTCHA.';
                    responseMessage.style.color = 'red'; // Color de error
                    return; // Detener el envío del formulario
                }

                formData.append('g-recaptcha-response', captchaResponse); // Añadir token al FormData

                // Ocultar texto de envío y mostrar loader
                submitText.style.display = 'none'; // Ocultar el texto
                loader.style.display = 'block'; // Mostrar el loader
                responseMessage.textContent = ''; // Limpiar mensaje de respuesta

                fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        // Mostrar texto de envío y ocultar loader
                        loader.style.display = 'none';
                        submitText.style.display = 'block'; // Mostrar de nuevo el texto

                        // Mostrar mensaje basado en la respuesta del servidor
                        if (data.includes('El mensaje se ha enviado correctamente.')) {
                            responseMessage.textContent = 'Correo enviado correctamente';
                            responseMessage.style.color = 'green'; // Color de éxito
                        } else {
                            responseMessage.textContent = 'Hubo un error al enviar el mensaje. Por favor, intenta de nuevo.';
                            responseMessage.style.color = 'red'; // Color de error
                            
                        }

                        form.reset();
                        grecaptcha.reset(); // Reiniciar el reCAPTCHA
                    })
                    .catch(error => {
                        loader.style.display = 'none';
                        submitText.style.display = 'block'; // Mostrar de nuevo el texto
                        responseMessage.textContent = 'Hubo un error al enviar el mensaje. Por favor, intenta de nuevo.';
                        responseMessage.style.color = 'red'; // Color de error
                        console.error('Error:', error);
                    });
            });
        });
        
        