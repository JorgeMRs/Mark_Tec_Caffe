const prevButton = document.getElementById('prev');
const nextButton = document.getElementById('next');
const images = document.querySelectorAll('.carousel-image');
const indicators = document.querySelectorAll('.indicator');
let currentIndex = 0;

function showImage(index) {
    images.forEach((img, i) => {
        img.classList.remove('active');
        indicators[i].classList.remove('active');
        if (i === index) {
            img.classList.add('active');
            indicators[i].classList.add('active');
        }
    });
}

prevButton.addEventListener('click', () => {
    currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
    showImage(currentIndex);
});

nextButton.addEventListener('click', () => {
    currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
    showImage(currentIndex);
});

setInterval(() => {
    currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
    showImage(currentIndex);
}, 3000);

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('nav ul');

    menuToggle.addEventListener('click', function() {
        nav.classList.toggle('open');
    });

    // Cerrar el menú cuando se hace clic en un enlace
    const navLinks = document.querySelectorAll('nav ul li a');
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            nav.classList.remove('open');
        });
    });

    // Cerrar el menú cuando se hace clic fuera de él
    document.addEventListener('click', function(event) {
        if (!event.target.closest('nav') && nav.classList.contains('open')) {
            nav.classList.remove('open');
        }
    });

    // Evitar que se cierre el menú si se hace clic dentro de él
    nav.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});
