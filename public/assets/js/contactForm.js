document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#contact-form');
    const formMessage = document.querySelector('#formMessage'); // Añadir un elemento para mensajes
    const loaderContainer = document.querySelector('#loaderContainer'); // Añadir un elemento para el loader
    const submitButton = document.querySelector('input[type="submit"]');
  
    form.addEventListener('submit', function (event) {
      event.preventDefault(); // Evitar el envío normal del formulario
  
      const formData = new FormData(this);
  
      // Añadir imágenes seleccionadas desde el carrusel si es necesario
      // formData.append('selected_images', JSON.stringify(selectedImages));
  
      fetch(this.action, {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        formMessage.style.display = 'block';
        formMessage.textContent = data; // Mostrar la respuesta del servidor
        alert("Enviado con exito");
        form.reset(); // Resetear el formulario después de enviar
        setTimeout(() => {
          formMessage.style.display = 'none';
        }, 10000); // Mostrar el mensaje durante 10 segundos
        console.log(data);
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