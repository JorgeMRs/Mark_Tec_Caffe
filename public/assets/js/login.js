document.addEventListener("DOMContentLoaded", () => {
  const signInButton = document.getElementById("signIn");
  const signUpButton = document.getElementById("signUp");
  const container = document.getElementById("container");

  if (signInButton && signUpButton && container) {
    signUpButton.addEventListener("click", () => {
      container.classList.add("right-panel-active");
    });

    signInButton.addEventListener("click", () => {
      container.classList.remove("right-panel-active");
    });
  }

  // Validaciones de registro
  document
    .querySelector("#registroForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Validación básica del lado del cliente
      let isValid = true;
      const email = document.querySelector(
        '#registroForm input[name="email"]'
      ).value;
      const password = document.querySelector(
        '#registroForm input[name="password"]'
      ).value;
      const passwordConfirm = document.querySelector(
        '#registroForm input[name="passwordConfirm"]'
      ).value;
      const recaptchaResponse = grecaptcha.getResponse(); // Obtener el token de reCAPTCHA
      const termsChecked = document.querySelector(
        '#registroForm input[name="terms"]'
      ).checked;

      const errorContainer = document.querySelector(
        "#registroForm #error-container2"
      );
      errorContainer.textContent = ""; // Limpiar errores previos

      if (!email || !password || !passwordConfirm) {
        isValid = false;
        errorContainer.textContent = "Por favor, completa todos los campos.";
      }
      if (password !== passwordConfirm) {
        isValid = false;
        errorContainer.textContent = "Las contraseñas no coinciden.";
      }
      if (password.length < 8) {
        isValid = false;
        errorContainer.textContent =
          "La contraseña debe tener al menos 8 caracteres.";
      }
      if (!recaptchaResponse) {
        isValid = false;
        errorContainer.textContent = "Por favor, completa el reCAPTCHA.";
      }

      if (isValid) {
        // Mostrar el modal de carga inmediatamente al enviar el formulario
        document.getElementById("loadingModal").style.display = "flex";

        let formData = new FormData(this);
        formData.append("g-recaptcha-response", recaptchaResponse); // Añadir el token de reCAPTCHA
        formData.append("terms", termsChecked ? "1" : "0"); // Añadir el valor del checkbox (1 si está marcado, 0 si no)

        fetch("/src/auth/register.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status === "success") {
              setTimeout(function () {
                window.location.href =
                  "https://cafesabrosos.myvnc.com/index.php?showModal=true";
              }, 1500);
            } else {
              errorContainer.textContent = data.message;
              // Reiniciar el reCAPTCHA en caso de error
              grecaptcha.reset();
              // Ocultar el modal si hay un error
              document.getElementById("loadingModal").style.display = "none";
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            errorContainer.textContent =
              "Se ha producido un error al procesar tu solicitud.";
            // Reiniciar el reCAPTCHA en caso de error
            grecaptcha.reset();
            // Ocultar el modal en caso de error
            document.getElementById("loadingModal").style.display = "none";
          });
      }
    });

  // Validaciones de inicio de sesión
  document
    .getElementById("loginForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      let isValid = true;
      const email = document.querySelector(
        '#loginForm input[name="email"]'
      ).value;
      const password = document.querySelector(
        '#loginForm input[name="password"]'
      ).value;

      if (!email || !password) {
        isValid = false;
      }

      if (isValid) {
        document.getElementById("loadingModal").style.display = "flex";

        let formData = new FormData(this);

        fetch("/src/auth/login.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              const errorContainer = document.querySelector(
                "#loginForm #error-container"
              );
              errorContainer.textContent = "";
              setTimeout(function () {
                window.location.href = data.redirect;
              }, 1500);
            } else {
              const errorContainer = document.querySelector(
                "#loginForm #error-container"
              );
              errorContainer.textContent = data.message;
              // Ocultar el modal si hay un error
              document.getElementById("loadingModal").style.display = "none";
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            const errorContainer = document.querySelector(
              "#loginForm #error-container"
            );
            errorContainer.textContent = "";
            errorContainer.textContent =
              "Se ha producido un error al procesar tu solicitud.";
            // Ocultar el modal en caso de error
            document.getElementById("loadingModal").style.display = "none";
          });
      } else {
        // Mostrar un mensaje de error de validación si el formulario no es válido
        const errorContainer = document.querySelector(
          "#loginForm #error-container"
        );
        errorContainer.textContent = "";
        errorContainer.textContent =
          "Por favor, completa todos los campos correctamente.";
      }
    });
});

// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js";
import {
  getAuth,
  signInWithPopup,
  GoogleAuthProvider,
} from "https://www.gstatic.com/firebasejs/10.13.2/firebase-auth.js";

const firebaseConfig = {
  apiKey: "AIzaSyD8FdKlXn3LZhHwpuhQ3-6rLwkDHx-C73k",
  authDomain: "cafesabrosos-c1175.firebaseapp.com",
  projectId: "cafesabrosos-c1175",
  storageBucket: "cafesabrosos-c1175.appspot.com",
  messagingSenderId: "728711728962",
  appId: "1:728711728962:web:210c9ba30f27c7d59c82db",
  measurementId: "G-NTKTFG6529",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
auth.languageCode = "es";
const provider = new GoogleAuthProvider();

const googleRegisterInBtn = document.getElementById("googleRegisterInBtn");

googleRegisterInBtn.addEventListener("click", function () {
  signInWithPopup(auth, provider)
    .then((result) => {
      const user = result.user;

      const errorContainer = document.querySelector(
        "#registroForm #error-container2"
      );
      const termsChecked = document.querySelector(
        '#registroForm input[name="terms"]'
      ).checked; 
      const recaptchaResponse = grecaptcha.getResponse(); // Obtener el token de reCAPTCHA

      const userData = {
        uid: user.uid,
        email: user.email,
        displayName: user.displayName,
        photoURL: user.photoURL,
        termsAccepted: termsChecked, 
        recaptchaResponse: recaptchaResponse,
      };

      fetch("/src/auth/googleRegister.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(userData),
      })
        .then((response) => response.json())
        .then((data) => {
          console.log("Respuesta del servidor:", data);
          errorContainer.textContent = ""; 

          if (data.status === "success") {
            window.location.href = data.redirect + "?registered=true";
          } else {
            grecaptcha.reset();
            errorContainer.textContent = data.message; 
            console.error(data.message); 
          }
        })
        .catch((error) => {
            grecaptcha.reset();
          console.error("Error al guardar el usuario:", error);
          errorContainer.textContent =
            "Error al procesar la solicitud. Intenta nuevamente más tarde."; 
        });
    })
    .catch((error) => {
      console.error(
        "Error durante el inicio de sesión:",
        error.code,
        error.message
      );
      errorContainer.textContent =
        "Error durante el inicio de sesión. Por favor, intenta de nuevo."; // Mensaje de error
    });
});

const googleSignInBtn = document.getElementById("googleSignInBtn");

googleSignInBtn.addEventListener("click", function () {
  signInWithPopup(auth, provider)
    .then((result) => {
      const user = result.user;

      const userData = {
        uid: user.uid,
        email: user.email,
        displayName: user.displayName,
      };

      fetch("/src/auth/googleLogin.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(userData),
      })
        .then((response) => response.json())
        .then((data) => {
          console.log("Respuesta del servidor:", data);
          const errorContainer = document.querySelector(
            "#loginForm #error-container"
          );

          if (data.success) {
            // Redirigir a la página principal
            window.location.href = data.redirect;
          } else {
            // Mostrar mensaje de error
            errorContainer.textContent = data.message;
            errorContainer.style.display = "block"; // Hacer visible el contenedor de errores
          }
        })
        .catch((error) => {
          console.error("Error al iniciar sesión:", error);
        });
    })
    .catch((error) => {
      console.error(
        "Error durante el inicio de sesión:",
        error.code,
        error.message
      );
    });
});


document.addEventListener('DOMContentLoaded', () => {

  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const passwordInput = document.getElementById(targetId);
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        this.innerHTML = '<i class="fas fa-eye-slash"></i>';
      } else {
        passwordInput.type = 'password';
        this.innerHTML = '<i class="fas fa-eye"></i>';
      }
    });
  });

});