function closeModal() {
    document.getElementById('activationModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';

    // Actualizar la URL para eliminar el parámetro 'showModal'
    const url = new URL(window.location);
    url.searchParams.delete('showModal');
    window.history.pushState({}, '', url);
}

document.addEventListener('DOMContentLoaded', function() {
    // Verificar si la página fue redirigida con el parámetro 'showModal'
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('showModal')) {
        const modal = document.getElementById('activationModal');
        const overlay = document.getElementById('overlay');

        // Mostrar el modal y la capa de fondo
        modal.style.display = 'block';
        overlay.style.display = 'block';

        // Cerrar el modal si el usuario hace clic fuera del modal
        window.onclick = function(event) {
            if (event.target == overlay) {
                closeModal();
            }
        }
    }

});

document.addEventListener('DOMContentLoaded', () => {
    const content = document.querySelector('.history .content');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                content.classList.add('active');
            }
        });
    });

    observer.observe(content);
});

document.addEventListener("DOMContentLoaded", function () {
    const featureItems = document.querySelectorAll('.feature-item');

    const observerOptions = {
        root: null, // Observa el viewport
        rootMargin: '0px',
        threshold: 0.1 // Disparar la animación cuando al menos el 10% del elemento es visible
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Activar animación
                entry.target.classList.add('animate');
            }
        });
    }, observerOptions);

    featureItems.forEach(item => {
        observer.observe(item);
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const menuLeft = document.querySelector('.menu-left');
    
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                menuLeft.classList.add('animate');
            }
        });
    }, { 
        threshold: 0.1, 
        rootMargin: '0px 0px -50px 0px' 
    });

    observer.observe(menuLeft);
});

document.addEventListener('DOMContentLoaded', () => {
    const section = document.querySelector('.data-2');
    const featureItems = document.querySelectorAll('.feature-item-2');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                featureItems.forEach((item, index) => {
                    // Aplicamos la clase animate con un pequeño retraso
                    setTimeout(() => {
                        item.classList.add('animate');
                    }, index * 200); // Ajusta el tiempo de retraso según sea necesario
                });
                // Dejar de observar después de que se haya activado
                observer.unobserve(section);
            }
        });
    }, {
        threshold: 0.1 // Puedes ajustar este valor según sea necesario
    });

    observer.observe(section);
});

document.addEventListener('DOMContentLoaded', () => {
    const textContainer = document.querySelector('.text-container');
    const section = document.querySelector('.mostSold');

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                textContainer.classList.add('visible');
                // Opcional: Deja de observar una vez que la animación se ha activado
                observer.unobserve(section);
            }
        });
    }, {
        root: null, // Observa en relación al viewport
        threshold: 0.1 // El porcentaje de la sección que debe estar visible
    });

    observer.observe(section);
});

document.addEventListener('DOMContentLoaded', function () {
    const counters = document.querySelectorAll('.feature-item-2 h3');

    const updateCount = (counter) => {
        const target = +counter.getAttribute('data-target');
        const speed = 5000; // Velocidad en milisegundos
        const duration = 5000; // Duración de la animación en milisegundos
        const start = Date.now();

        const increment = target / (duration / 16); // 16ms es aproximadamente el tiempo de un frame

        const animate = () => {
            const now = Date.now();
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);

            counter.innerText = Math.ceil(progress * target);

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                counter.innerText = target;
                counter.setAttribute('data-animated', 'true'); // Marca la animación como completada
            }
        };

        animate();
    };

    const handleIntersection = (entries) => {
        entries.forEach(entry => {
            const counter = entry.target;

            // Solo animar si no ha sido animado antes
            if (entry.isIntersecting && !counter.getAttribute('data-animated')) {
                updateCount(counter);
                observer.unobserve(counter);
            }
        });
    };

    const observer = new IntersectionObserver(handleIntersection, {
        threshold: 0.5 // Cambia según cuánto de la sección debe ser visible para activar la animación
    });

    counters.forEach(counter => {
        const savedValue = localStorage.getItem(counter.getAttribute('data-translate')); // Verifica si hay un valor guardado
        if (savedValue) {
            counter.innerText = savedValue; // Restablece el valor del contador
        } else {
            counter.innerText = '0';
            counter.removeAttribute('data-animated'); // Resetea la marca de animación
        }
        observer.observe(counter);
    });

    // Función para guardar los valores actuales de los contadores
    function saveCounterValues() {
        counters.forEach(counter => {
            localStorage.setItem(counter.getAttribute('data-translate'), counter.innerText);
        });
    }

    // Escucha el cambio de idioma y guarda los valores de los contadores
    const languageSelector = document.getElementById('language-selector');
    languageSelector.addEventListener('change', function () {
        saveCounterValues(); // Guarda los valores antes de cambiar de idioma
    });
});

// Carga y aplica las traducciones
document.addEventListener('DOMContentLoaded', function() {
    const languageSelector = document.getElementById('language-selector');
    const translationsUrl = '/public/translations/index.json'; // Ruta al archivo de traducciones
    const navTranslationsUrl = '/public/translations/nav.json'; // Ruta al archivo de traducciones para el nav
    const footerTranslationsUrl = '/public/translations/footer.json'; // Ruta al archivo de traducciones para el footer

    // Establece el idioma por defecto como español si no hay preferencia guardada
    const defaultLanguage = 'es';
    const savedLanguage = localStorage.getItem('preferredLanguage') || defaultLanguage;
    languageSelector.value = savedLanguage;

    languageSelector.addEventListener('change', function() {
        const selectedLanguage = languageSelector.value;
        localStorage.setItem('preferredLanguage', selectedLanguage); // Guarda la preferencia de idioma
        loadTranslations(selectedLanguage);
    });

    function loadTranslations(language) {
        Promise.all([
            fetch(translationsUrl).then(response => response.json()),
            fetch(navTranslationsUrl).then(response => response.json()),
            fetch(footerTranslationsUrl).then(response => response.json())
        ])
        .then(([translations, navTranslations, footerTranslations]) => {
            applyTranslations(translations, language);
            applyNavTranslations(navTranslations, language);
            applyFooterTranslations(footerTranslations, language);
            restoreCounterValues(); // Restaura los valores de los contadores después de aplicar las traducciones
        });
    }

    function applyTranslations(translations, language) {
        const elements = document.querySelectorAll('[data-translate]');
        elements.forEach(element => {
            const key = element.getAttribute('data-translate');
            if (translations[language] && translations[language][key]) {
                element.textContent = translations[language][key];
            }
        });
    }

    function applyNavTranslations(navTranslations, language) {
        const elements = document.querySelectorAll('[data-translate^="nav."]');
        elements.forEach(element => {
            const key = element.getAttribute('data-translate');
            if (navTranslations[language] && navTranslations[language][key.replace('nav.', '')]) {
                element.textContent = navTranslations[language][key.replace('nav.', '')];
            }
        });
    }

    function applyFooterTranslations(footerTranslations, language) {
        const elements = document.querySelectorAll('[data-translate^="footer."]');
        elements.forEach(element => {
            const key = element.getAttribute('data-translate');
            if (footerTranslations[language] && footerTranslations[language][key.replace('footer.', '')]) {
                element.textContent = footerTranslations[language][key.replace('footer.', '')];
            }
        });
    }

    function restoreCounterValues() {
        const counters = document.querySelectorAll('.feature-item-2 h3');
        counters.forEach(counter => {
            const key = counter.getAttribute('data-translate');
            const savedValue = localStorage.getItem(key);
            if (savedValue) {
                counter.innerText = savedValue;
            }
        });
    }

    // Carga las traducciones al cargar la página
    loadTranslations(savedLanguage);
});

document.addEventListener('DOMContentLoaded', function() {
    function getURLParameter(name) {
        const params = new URLSearchParams(window.location.search);
        return params.get(name);
    }
    
    // Comprobar si el parámetro 'registered' está presente
    const registered = getURLParameter('registered');
    
    const modal = document.getElementById("googleRegistrationModal");
    if (registered === 'true') {
        modal.style.display = "flex"; // Mostrar el modal si se ha registrado
    }
    
    // Cerrar el modal
    const closeButton = document.querySelector(".google-modal .close");
    closeButton.onclick = function() {
        window.history.replaceState({}, document.title, window.location.pathname); // Remover el parámetro de la URL
        modal.style.display = "none"; // Cerrar el modal
    };
    
    window.onclick = function(event) {
        if (event.target === modal) {
            window.history.replaceState({}, document.title, window.location.pathname);
            modal.style.display = "none";
        }
    };
});