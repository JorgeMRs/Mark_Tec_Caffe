<?php
if (isset($_COOKIE['user_session'])) {

  header("Location: /");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-48x48.png">
  <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-64x64.png">
  <link rel="icon" type="image/x-icon" href="/public/assets/img/icons/favicon.ico">
  <link rel="stylesheet" href="assets/css/login.css" media="screen and (min-width: 769px)">
  <link rel="stylesheet" href="assets/css/loginmobile.css" media="screen and (max-width: 768px)">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <title>Café Sabrosos - Login</title>
</head>

<body>
  <div class="container" id="container">
    <div class="form-container sign-up-container">
      <form id="registroForm" action="/src/db/registro.php">
        <h1>Registrarte</h1>
        <div class="social-container">
          <a href="#" id="googleRegisterInBtn" class="social"><i class="fab fa-google"></i></a>
          <a href="#" id="facebookSignInBtn" class="social"><i class="fab fa-facebook-f"></i></a>
          <a href="#" id="appleSignInBtn" class="social"><i class="fa-brands fa-apple"></i></a>
        </div>
        <span>O usa tu email para registrarte</span>
        <input type="email" name="email" placeholder="Email">
        <div class="password-container">
  <input type="password" name="password" id="password" placeholder="Contraseña" required>
  <button type="button" class="toggle-password" data-target="password">
    <i class="fas fa-eye"></i>
  </button>
</div>
<div class="password-container">
  <input type="password" name="passwordConfirm" id="passwordConfirm" placeholder="Confirmar contraseña" required>
  <button type="button" class="toggle-password" data-target="passwordConfirm">
    <i class="fas fa-eye"></i>
  </button>
</div>
        <div id="error-container2" class="error-message" style="color: red;"></div>
        <div>
          <input type="checkbox" id="terms" name="terms">
          <label for="terms">
            Acepto los <a href="terminos-y-condiciones.php" target="_blank" style="color:#b8860b">Términos y
              Condiciones</a> y la
            <a href="politicas-de-privacidad.php" target="_blank" style="color: #b8860b;">Política de Privacidad</a>.
          </label>
        </div>
        <div class="g-recaptcha" name="g-recaptcha-response" data-sitekey="6LemoDEqAAAAABt_tJuEIjgcf55iauaO5PTSp7lk">
        </div>
        <br>
        <button type="submit" class="btn-login">Registrar</button>
        <br>
        <span class="ocultar-texto">O si ya tienes una cuenta</span>
      </form>
    </div>
    <div class="form-container sign-in-container">
      <form id="loginForm" action="/src/db/login.php" method="POST">
        <h1>Inicia sesión</h1>
        <div class="social-container">
          <a href="#" id="googleSignInBtn" class="social"><i class="fab fa-google"></i></a>
          <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social"><i class="fa-brands fa-apple"></i></a>
        </div>
        <span>O usa tu email</span>
        <input type="email" name="email" placeholder="Email">
        <div class="password-container">
  <input type="password" name="password" id="loginPassword" placeholder="Contraseña">
  <button type="button" class="toggle-password" data-target="loginPassword">
    <i class="fas fa-eye"></i>
  </button>
</div>
        <div id="error-container" class="error-message" style="color: red;"></div>
        <a href="/public/cambiarContrasena.php">¿Olvidaste tu contraseña?</a>
        <button class="btn-login">Iniciar sesión</button>
      </form>
    </div>
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h1>¿Ya tienes una cuenta?</h1>
          <p>Ingresa tus datos para poder utilizar el sitio</p>
          <button class="ghost btn-login" id="signIn">Iniciar sesión</button>
          <a href="/index.php" class="index-button">Inicio</a>
        </div>
        <div class="overlay-panel overlay-right">
          <h1>¡Bienvenido!</h1>
          <p>Registrate para poder utilizar el sitio</p>
          <button class="ghost btn-login" id="signUp">Registrarte</button>
          <a href="/index.php" class="index-button">Inicio</a>
        </div>
      </div>
    </div>
  </div>
  <style>
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      display: none;
      /* Oculto por defecto */
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    .aligner {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100%;
      width: 100%;
    }

    .steam-container {
      width: 60px;
      height: 50px;
      margin-left: 10px;
    }

    .coffee-container {
      width: 100px;
      height: 100px;
      position: relative;
    }

    .coffee-cup {
      width: 80px;
    }

    .squiggle-container {
      width: 10px;
      height: 30px;
      display: inline-block;
    }

    .squiggle-container-1 {
      transform: translateY(10px);
    }

    .squiggle-container-1 .squiggle {
      animation: move-and-fade 2.5s linear infinite;
      animation-delay: 0.2s;
      width: 10px;
    }

    @keyframes move-and-fade {
      0% {
        opacity: 0;
        transform: translateY(0);
      }

      50% {
        opacity: 1;
      }

      75% {
        opacity: 0;
      }

      100% {
        transform: translateY(-10px);
        opacity: 0;
      }
    }

    .squiggle-container-2 {
      transform: translateY(0px);
    }

    .squiggle-container-2 .squiggle {
      animation: move-and-fade 2.5s linear infinite;
      animation-delay: 0s;
      width: 10px;
    }

    @keyframes move-and-fade {
      0% {
        opacity: 0;
        transform: translateY(0);
      }

      50% {
        opacity: 1;
      }

      75% {
        opacity: 0;
      }

      100% {
        transform: translateY(-20px);
        opacity: 0;
      }
    }

    .squiggle-container-3 {
      transform: translateY(15px);
    }

    .squiggle-container-3 .squiggle {
      animation: move-and-fade 2.5s linear infinite;
      animation-delay: 0.4s;
      width: 10px;
    }

    @keyframes move-and-fade {
      0% {
        opacity: 0;
        transform: translateY(0);
      }

      50% {
        opacity: 1;
      }

      75% {
        opacity: 0;
      }

      100% {
        transform: translateY(-15px);
        opacity: 0;
      }
    }

    .loading-message {
      color: white;
      font-size: 16px;
      /* Tamaño de fuente por defecto */
      text-align: center;
      /* Alineación del texto */
      margin-top: 20px;
      /* Espaciado superior */
    }

    /* Media query para dispositivos móviles */
    @media (max-width: 768px) {
      .loading-message {
        font-size: 14px;
        /* Tamaño de fuente más pequeño en móviles */
        margin-top: 15px;
        /* Menor espaciado superior */
      }
    }

    @media (max-width: 768px) {

      .coffee-container {
        width: 80%;
        /* Reducir el ancho del contenedor en móviles */
        height: auto;
        /* Ajustar la altura automáticamente */
      }

      .steam-container,
      .coffee-cup-container {
        margin: 0px 100px 0 100px;
        /* Centrar los elementos dentro del contenedor */
      }
    }

    .squiggle {
      stroke-dasharray: 100;
    }

    .squiggle path {
      stroke: #b8860b;
    }

    @keyframes dash {
      0% {
        stroke-dashoffset: 1000;
      }

      50% {
        stroke-dashoffset: 500;
      }

      100% {
        stroke-dashoffset: 0;
      }
    }
  </style>
  <div id="loadingModal" class="modal-overlay">
    <div class="aligner">
      <div class="aligner-item coffee-container">
        <div class="steam-container">
          <div class="squiggle-container squiggle-container-1">
            <div class="squiggle">
              <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 28.1 80.6"
                style="enable-background:new 0 0 28.1 80.6;" xml:space="preserve">
                <path class="" fill="none" stroke-width="11" stroke-linecap="round" stroke-miterlimit="10"
                  d="M22.6,75.1c-8-5.6-15.2-10.5-15.2-19.9c0-12.1,14.1-17.2,14.1-29.6c0-9.1-6.7-15.7-16-20.1" />
              </svg>
            </div> <!-- end .squiggle-->
          </div>
          <div class="squiggle-container squiggle-container-2">
            <div class="squiggle">
              <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 28.1 80.6"
                style="enable-background:new 0 0 28.1 80.6;" xml:space="preserve">
                <path class="" fill="none" stroke="#fff" stroke-width="11" stroke-linecap="round" stroke-miterlimit="10"
                  d="M22.6,75.1c-8-5.6-15.2-10.5-15.2-19.9c0-12.1,14.1-17.2,14.1-29.6c0-9.1-6.7-15.7-16-20.1" />
              </svg>
            </div> <!-- end .squiggle-->
          </div>
          <div class="squiggle-container squiggle-container-3">
            <div class="squiggle">
              <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 28.1 80.6"
                style="enable-background:new 0 0 28.1 80.6;" xml:space="preserve">
                <path class="" fill="none" stroke="#fff" stroke-width="11" stroke-linecap="round" stroke-miterlimit="10"
                  d="M22.6,75.1c-8-5.6-15.2-10.5-15.2-19.9c0-12.1,14.1-17.2,14.1-29.6c0-9.1-6.7-15.7-16-20.1" />
              </svg>
            </div> <!-- end .squiggle-->
          </div>
        </div>
        <div class="coffee-cup-container">
          <svg class="coffee-cup" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42.15 31">
            <defs>
              <style>
                .a {
                  fill: #b8860b;
                }

                .b {
                  fill: #5d5d5d;
                }
              </style>
            </defs>
            <path class="a" d="M30.06,2V23.75c0,2.63-.87,5.13-5.12,5.13H7.06A4.86,4.86,0,0,1,2,23.81V2H30.06Z"
              transform="translate(0 -0.06)" />
            <path class="b"
              d="M40.64,9.52a10.2,10.2,0,0,0-8.64-5V0.06H0V23.81a7,7,0,0,0,7.06,7.24H24.94c2.34,0,6.06-.81,6.93-5.18a10.6,10.6,0,0,0,8.89-5.29A11.29,11.29,0,0,0,40.64,9.52ZM28,23.75c0,2.07-.42,3.31-3.06,3.31H7.06A3,3,0,0,1,4,23.81V4.06H28V23.75Zm9.26-5.17A7.13,7.13,0,0,1,32,21.78V8.45a7,7,0,0,1,5.18,3.1A7.24,7.24,0,0,1,37.26,18.58Z"
              transform="translate(0 -0.06)" />
          </svg>

        </div>
        <p class="loading-message">Procesando tu solicitud, por favor espera...</p>
      </div>
    </div>
  </div>
  <!-- Modal para mostrar el mensaje de advertencia -->
  <div id="alert-modal" class="alert-overlay" style="display: none;">
    <div class="alert-content">
      <span class="alert-close">&times;</span>
      <p>Para realizar un pedido necesitas tener una cuenta o iniciar sesión.</p>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      const modal = document.querySelector('.alert-overlay');
      if (urlParams.get('show_modal') === 'true') {
        modal.style.display = 'flex';
      }

      function closeModal() {
        modal.style.display = 'none';
        const url = new URL(window.location);
        url.searchParams.delete('show_modal');
        window.history.replaceState({}, '', url);
      }

      document.querySelector('.alert-overlay .alert-close').addEventListener('click', closeModal);

      window.addEventListener('click', (event) => {
        if (event.target === modal) {
          closeModal();
        }
      });
    });
  </script>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
  <script type="module" src="assets/js/login.js"></script>
</body>

</html>