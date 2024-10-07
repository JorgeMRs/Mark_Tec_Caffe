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

document.addEventListener('DOMContentLoaded', () => {
    const languageSelector = document.getElementById('language-selector');
    const elementsToTranslate = {
        title: document.querySelector('.contact-title'),
        nameLabel: document.querySelector('.contact-label[for="name"]'),
        emailLabel: document.querySelector('.contact-label[for="email"]'),
        subjectLabel: document.querySelector('.contact-label[for="subject"]'),
        messageLabel: document.querySelector('.contact-label[for="message"]'),
        submitButton: document.querySelector('.submit-text'),
        responseMessage: document.querySelector('.contact-response')
    };

    const loadTranslations = async (lang) => {
        const response = await fetch('/public/translations/contactos.json');
        const translations = await response.json();
        return translations[lang];
    };

    const updateText = (translations) => {
        elementsToTranslate.title.textContent = translations.contact_title;
        elementsToTranslate.nameLabel.textContent = translations.name_label;
        elementsToTranslate.emailLabel.textContent = translations.email_label;
        elementsToTranslate.subjectLabel.textContent = translations.subject_label;
        elementsToTranslate.messageLabel.textContent = translations.message_label;
        elementsToTranslate.submitButton.textContent = translations.submit_button;
        elementsToTranslate.responseMessage.textContent = translations.response_message;
    };

    const setLanguage = (lang) => {
        languageSelector.value = lang; // Actualiza el select
        loadTranslations(lang).then(updateText);
        localStorage.setItem('selectedLanguage', lang); // Guarda en localStorage
    };

    languageSelector.addEventListener('change', (event) => {
        const selectedLang = event.target.value;
        setLanguage(selectedLang);
    });

    // Cargar el idioma por defecto o el último seleccionado
    const savedLanguage = localStorage.getItem('selectedLanguage') || 'es';
    setLanguage(savedLanguage);
});


