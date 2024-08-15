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
    document.querySelector('#registroForm').addEventListener('submit', function(event) {
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
                const errorContainer = document.querySelector('#registroForm #error-container');
                errorContainer.textContent = data.message;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorContainer = document.querySelector('#registroForm #error-container');
            errorContainer.textContent = 'Se ha producido un error al procesar tu solicitud.';
        });
    });

    // Validaciones de inicio de sesión
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        let formData = new FormData(this);
        
        fetch('/src/db/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const errorContainer = document.querySelector('#loginForm #error-container');
            if (data.success) {
                window.location.href = data.redirect; // Redirige en caso de éxito
            } else {
                errorContainer.textContent = data.message; // Muestra mensaje de error
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorContainer = document.querySelector('#loginForm #error-container');
            errorContainer.textContent = 'Se ha producido un error al procesar tu solicitud.';
        });
    });
});
  
