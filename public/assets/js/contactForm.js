// Funcion para enviar el formulario de contacto
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('#contact-form');
  const formMessage = document.querySelector('#formMessage');
  const loaderContainer = document.querySelector('#loaderContainer');
  const submitButton = document.querySelector('input[type="submit"]');
  const modalBackground = document.querySelector('#modal-background');
  const modalMessage = document.querySelector('#modal-message');
  const closeModalButton = document.querySelector('#close-modal');

 // Evento al enviar el formulario de contacto al servidor y mostrar un mensaje de exito o error
  form.addEventListener('submit', function (event) {
      event.preventDefault();

      const formData = new FormData(this);

      loaderContainer.style.display = 'block';
      submitButton.style.display = 'none';

      fetch(this.action, {
          method: 'POST',
          body: formData
      })
      .then(response => response.text())
      .then(data => {
        // Ocultar el contenedor de cargador y mostrar el contenedor de mensaje de exito o error
          loaderContainer.style.display = 'none';
          submitButton.style.display = 'block';
          formMessage.style.display = 'none';

          // En caso de que el correo se envie, mostrar un mensaje de exito
          if (data.includes('El mensaje se ha enviado correctamente.')) {
              modalMessage.textContent = 'Correo enviado correctamente';
              closeModalButton.classList.remove('error');

              // En caso de que el correo no se envie, mostrar un mensaje de error
          } else {
              modalMessage.textContent = 'Hubo un error al enviar el mensaje. Por favor, intenta de nuevo.';
              closeModalButton.classList.add('error');
          }
          
          modalBackground.style.display = 'flex';
          form.reset();
          
          closeModalButton.addEventListener('click', () => {
              modalBackground.style.display = 'none';
          });
      })
      .catch(error => {
          loaderContainer.style.display = 'none';
          submitButton.style.display = 'block';
          formMessage.style.display = 'block';
          formMessage.textContent = 'Hubo un error al enviar el mensaje. Por favor, intenta de nuevo.';
          console.error('Error:', error);
      });
  });
});
