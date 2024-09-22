<!doctype html>
<html>

<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <title>Snippet - BBBootstrap</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="assets/css/opinion.css">
  <link rel="stylesheet" href="assets/css/nav.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</head>
<header>
  <?php include 'templates/nav.php'?>
</header>

<body>
  <div class="container rounded">
    <div class="feedback-container">
      <h2>TÚ OPINIÓN IMPORTA</h2>
      <div class="star-rating">
        <i class="fas fa-star" data-value="1"></i>
        <i class="fas fa-star" data-value="2"></i>
        <i class="fas fa-star" data-value="3"></i>
        <i class="fas fa-star" data-value="4"></i>
        <i class="fas fa-star" data-value="5"></i>
      </div>
      <textarea id="comment" placeholder="Deja un comentario..."></textarea>
      <div id="error-container" style="color: red; font-weight: bold; display:flex; justify-content:center; margin-bottom: 10px;"></div>
      <button id="submit-feedback" type="submit">Enviar</button>
    </div>
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <!-- Card 1 -->
        <div class="swiper-slide">
          <div class="feedback-card">
            <img src="assets/img/feedback/feedback1.jpg" alt="Jane Doe" class="profile-pic">
            <h3>Jane Doe</h3>
            <p>¡Me encantó el servicio!</p>
            <div class="stars">
              ★★★★☆
            </div>
          </div>
        </div>
        <!-- Card 2 -->
        <div class="swiper-slide">
          <div class="feedback-card">
            <img src="assets/img/feedback/feedback7.jpg" alt="John Smith" class="profile-pic">
            <h3>John Smith</h3>
            <p>Excelente calidad.</p>
            <div class="stars">
              ★★★★★
            </div>
          </div>
        </div>
        <!-- Card 3 -->
        <div class="swiper-slide">
          <div class="feedback-card">
            <img src="assets/img/feedback/feedback2.jpg" alt="Ana González" class="profile-pic">
            <h3>Ana González</h3>
            <p>Muy recomendado.</p>
            <div class="stars">
              ★★★★☆
            </div>
          </div>
        </div>
        <!-- Card 4 -->
        <div class="swiper-slide">
          <div class="feedback-card">
            <img src="assets/img/feedback/feedback5.jpg" alt="Pedro López" class="profile-pic">
            <h3>Pedro López</h3>
            <p>Buena experiencia.</p>
            <div class="stars">
              ★★★☆☆
            </div>
          </div>
        </div>
        <!-- Card 5 -->
        <div class="swiper-slide">
          <div class="feedback-card">
            <img src="assets/img/feedback/feedback4.jpg" alt="Laura Martínez" class="profile-pic">
            <h3>Laura Martínez</h3>
            <p>Lo disfruté mucho.</p>
            <div class="stars">
              ★★★★☆
            </div>
          </div>
        </div>
        <!-- Card 6 -->
        <div class="swiper-slide">
          <div class="feedback-card">
            <img src="assets/img/feedback/feedback6.jpg" alt="Carlos Ramírez" class="profile-pic">
            <h3>Carlos Ramírez</h3>
            <p>Muy buen servicio.</p>
            <div class="stars">
              ★★★★★
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include 'templates/footer.php' ?>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <script>
      const swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
          delay: 3000,
          disableOnInteraction: false,
        },
        slidesPerView: 1,
        spaceBetween: 10, // Reduce el espacio entre slides para móviles
        breakpoints: {
          320: { // Para teléfonos con pantallas muy pequeñas
            slidesPerView: 1,
            spaceBetween: 10,
          },
          480: { // Para teléfonos con pantallas pequeñas
            slidesPerView: 1,
            spaceBetween: 20,
          },
          640: {
            slidesPerView: 1, // Mantener una columna en dispositivos móviles
            spaceBetween: 20,
          },
          768: {
            slidesPerView: 2,
            spaceBetween: 30,
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
          1200: {
            slidesPerView: 4,
            spaceBetween: 40,
          },
        },
      });
    </script>


    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const stars = document.querySelectorAll('.star-rating .fas');
        let rating = 0;

        stars.forEach((star, index) => {
          star.addEventListener('mouseover', () => {
            highlightStars(index + 1);
          });

          star.addEventListener('click', () => {
            rating = index + 1;
            highlightStars(rating);
            console.log('Rating selected:', rating); // Agregado para verificar la cantidad de estrellas
          });

          star.addEventListener('mouseout', () => {
            highlightStars(rating);
          });
        });

        function highlightStars(count) {
          stars.forEach((star, index) => {
            star.style.color = index < count ? '#ffdd57' : '#ccc';
          });
        }
      });

      document.getElementById('submit-feedback').addEventListener('click', async () => {
        const stars = document.querySelectorAll('.star-rating .fas');
        let rating = 0;
        stars.forEach((star) => {
          if (star.style.color === 'rgb(255, 221, 87)') {
            rating = parseInt(star.getAttribute('data-value'));
          }
        });
        const comment = document.getElementById('comment').value;

        const response = await fetch('/src/db/submitFeedback.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            rating: rating,
            comment: comment
          })
        });

        const result = await response.json();

        const errorContainer = document.getElementById('error-container');
        if (result.status === 'success') {
          alert(result.message);
          // Opcional: limpiar el formulario o hacer algo adicional
        } else {
          if (errorContainer) {
            errorContainer.textContent = result.message;
          } else {
            alert(result.message);
          }
        }
      });
    </script>
    <script src="/public/assets/js/updateCartCounter.js"></script>
</body>

</html>