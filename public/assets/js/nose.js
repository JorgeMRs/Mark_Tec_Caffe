document.addEventListener('DOMContentLoaded', function() {
    const carouselItems = document.querySelectorAll('.carousel-item');
    const indicators = document.querySelectorAll('.carousel-indicators .indicator');
    const transitionLayer = document.createElement('div');
    transitionLayer.classList.add('transition-layer');
    document.querySelector('.carousel').appendChild(transitionLayer);

    let currentIndex = 0;
    const intervalTime = 5000; // 5 segundos

    function showSlide(index) {
        transitionLayer.classList.add('show');
        setTimeout(() => {
            carouselItems.forEach((item, i) => {
                item.classList.toggle('active', i === index);
            });
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('active', i === index);
            });
            transitionLayer.classList.remove('show');
        }, 1000); // Tiempo de la transición de la capa negra
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % carouselItems.length;
        showSlide(currentIndex);
    }

    let slideInterval = setInterval(nextSlide, intervalTime);

    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            clearInterval(slideInterval); // Detiene el cambio automático al hacer clic en un indicador
            currentIndex = index;
            showSlide(currentIndex);
            slideInterval = setInterval(nextSlide, intervalTime); // Reinicia el temporizador
        });
    });

    showSlide(currentIndex);
});
