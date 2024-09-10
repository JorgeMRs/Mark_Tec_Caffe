document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('registroForm');
    var modal = document.getElementById('resultadoModal');
    var span = document.getElementsByClassName('close')[0];
    var mensaje = document.getElementById('resultadoMensaje');
    
    // Asegúrate de que el modal esté oculto al cargar la página
    modal.style.display = 'none';
    mensaje.innerHTML = '';

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/public/src/back/', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                mensaje.innerHTML = xhr.responseText;
                if (mensaje.innerHTML.trim() !== '') {
                    modal.style.display = 'flex';
                }
            }
        };
        var formData = new FormData(form);
        var encodedData = new URLSearchParams(formData).toString();
        xhr.send(encodedData);
    });

    span.onclick = function() {
        modal.style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
});

