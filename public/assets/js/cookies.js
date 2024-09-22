document.addEventListener("DOMContentLoaded", function () {
  const footer = document.getElementById("cookie-footer");
  const modal = document.getElementById("info-modal");
  const closeModal = document.getElementById("close-modal");

  // Comprobar si ya hay una decisión guardada
  const cookies = document.cookie.split("; ");
  let cookiePreference = "";

  // Buscar la cookie 'cookie_preference'
  for (let cookie of cookies) {
    const [name, value] = cookie.split("=");
    if (name === "cookie_preference") {
      cookiePreference = value;
      break;
    }
  }
  if (!cookiePreference) {
    setTimeout(() => {
      footer.classList.add("show"); // Agregar la clase show para activar la animación
    }, 500); // Agregar un pequeño retraso para que se vea la transición
  }
  console.log("Preferencia de cookies:", cookiePreference);

  document
    .getElementById("accept-cookies")
    .addEventListener("click", function () {
      localStorage.setItem("cookie_preference", "accepted");

      // Realiza la solicitud fetch para aceptar cookies
      fetch("/src/client/account/setCookies.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({ action: "accept" }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            console.log(data.message);
            footer.style.opacity = "0"; // Cambiar la opacidad a 0
            footer.style.bottom = "-100px"; // Mover fuera de la vista

            setTimeout(() => {
              footer.style.display = "none"; // Oculta el footer
            }, 500); // Debe coincidir con la duración de la transición
          } else {
            console.error("Error al establecer la cookie:", data.message);
          }
        })
        .catch((error) => {
          console.error("Error en la solicitud:", error);
        });
    });

  document
    .getElementById("reject-cookies")
    .addEventListener("click", function () {
      localStorage.setItem("cookie_preference", "rejected");

      // Realiza la solicitud fetch para rechazar cookies
      fetch("/src/client/account/setCookies.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({ action: "reject" }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            console.log(data.message);
            footer.style.opacity = "0"; // Cambiar la opacidad a 0
            footer.style.bottom = "-100px"; // Mover fuera de la vista

            setTimeout(() => {
              footer.style.display = "none"; // Oculta el footer
            }, 500); // Debe coincidir con la duración de la transición
          } else {
            console.error("Error al establecer la cookie:", data.message);
          }
        })
        .catch((error) => {
          console.error("Error en la solicitud:", error);
        });
    });

  // Lógica para mostrar el modal
  document.getElementById("more-info").addEventListener("click", function () {
    modal.style.display = "flex"; // Asegúrate de que se muestre
    setTimeout(() => {
      modal.classList.add("show"); // Agregar la clase show después de mostrar
      modal.style.opacity = "1"; // Cambiar a opacidad 1
    }, 10); // Un pequeño retraso para que se aplique la transición
  });

  // Cerrar el modal al hacer clic en la X
  closeModal.addEventListener("click", function () {
    modal.style.opacity = "0"; // Cambiar la opacidad a 0
    setTimeout(() => {
      modal.classList.remove("show"); // Quitar la clase show
      modal.style.display = "none"; // Oculta el modal después de la transición
    }, 500); // Debe coincidir con la duración de la transición
  });
});
