document.addEventListener("DOMContentLoaded", function () {
  const avatarInput = document.getElementById("avatar");
  const avatarImage = document.querySelector(".avatar-image");
  const successAvatarDiv = document.querySelector(".success-avatar");
  const errorAvatarDiv = document.querySelector(".error-avatar");
  const deleteAvatarBtn = document.getElementById("deleteAvatarBtn");
  const cropperModal = document.getElementById("cropperModal");
  const cropperImage = document.getElementById("cropperImage");
  const cropImageBtn = document.getElementById("cropImageBtn");
  const cancelCropBtn = document.getElementById("cancelCropBtn");
  const closeCropperModal = document.querySelector(".cropper-close-button"); // Actualizado para usar la clase
  let cropper;

  function limpiarMensajes() {
    if (errorAvatarDiv) {
      errorAvatarDiv.style.display = "none";
    }
    if (successAvatarDiv) {
      successAvatarDiv.style.display = "none";
    }
  }

  if (avatarInput) {
    avatarInput.addEventListener("change", function (event) {
      limpiarMensajes();
      const file = event.target.files[0];

      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          cropperImage.src = e.target.result;
          cropperModal.style.display = "flex";

          if (cropper) {
            cropper.destroy();
          }

          cropper = new Cropper(cropperImage, {
            aspectRatio: 1, // Puedes ajustar la relación de aspecto
            viewMode: 1,
          });
        };
        reader.readAsDataURL(file);
      }
    });
  }

  cropImageBtn.addEventListener("click", function () {
    const canvas = cropper.getCroppedCanvas();
    canvas.toBlob(function (blob) {
      const formData = new FormData();
      formData.append("avatar", blob, "avatar.png");

      fetch("/src/uploads/avatarUpload.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            avatarImage.src =
              "/public/assets/img/avatars/" +
              encodeURIComponent(data.avatar) +
              "?t=" +
              new Date().getTime();
            successAvatarDiv.textContent = data.message;
            successAvatarDiv.style.display = "block";
            errorAvatarDiv.style.display = "none";
            deleteAvatarBtn.style.display = "block";
            cropperModal.style.display = "none";
            avatarInput.value = "";
          } else {
            errorAvatarDiv.textContent = data.message;
            errorAvatarDiv.style.display = "block";
            successAvatarDiv.style.display = "none";
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          errorAvatarDiv.textContent =
            "Error al subir el avatar. Por favor, intenta de nuevo.";
          errorAvatarDiv.style.display = "block";
          successAvatarDiv.style.display = "none";
        });
    }, "image/png");
  });

  cancelCropBtn.addEventListener("click", function () {
    cropperModal.style.display = "none";
    avatarInput.value = ""; // Resetea el campo de archivo
  });

  closeCropperModal.addEventListener("click", function () {
    cropperModal.style.display = "none";
    avatarInput.value = ""; // Resetea el campo de archivo
  });

  if (deleteAvatarBtn) {
    deleteAvatarBtn.addEventListener("click", function (event) {
      event.preventDefault();
      limpiarMensajes();

      if (confirm("¿Estás seguro de que deseas eliminar tu avatar?")) {
        fetch("/src/uploads/avatarDelete.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            action: "deleteAvatar",
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              avatarImage.src =
                "/public/assets/img/user-circle-svgrepo-com.svg";
              deleteAvatarBtn.style.display = "none";
              successAvatarDiv.textContent = data.message;
              successAvatarDiv.style.display = "block";
              errorAvatarDiv.style.display = "none";
              avatarInput.value = "";
            } else {
              errorAvatarDiv.textContent = data.message;
              errorAvatarDiv.style.display = "block";
              successAvatarDiv.style.display = "none";
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            errorAvatarDiv.textContent =
              "Error al eliminar el avatar. Por favor, intenta de nuevo.";
            errorAvatarDiv.style.display = "block";
            successAvatarDiv.style.display = "none";
          });
      }
    });
  }

  // Handle the first modal for account deletion
  document.getElementById("deleteAccountBtn").onclick = function () {
    document.getElementById("deleteAccountModal").style.display = "block";
  };

  document.getElementById("cancelDeleteBtn").onclick = function () {
    document.getElementById("deleteAccountModal").style.display = "none";
  };

  // Random code generation
  function generateRandomCode() {
    return Math.floor(100000 + Math.random() * 900000); // Generates a 6-digit random number
  }

  // Handle the second modal for code verification
  document.getElementById("confirmDeleteBtn").onclick = function () {
    // Close the first modal
    document.getElementById("deleteAccountModal").style.display = "none";

    // Generate and display the random code in the second modal
    var randomCode = generateRandomCode();
    document.getElementById("generatedCode").textContent =
      "Código: " + randomCode;

    // Show the second modal
    document.getElementById("codeVerificationModal").style.display = "block";

    // Verify the code
    document.getElementById("verifyCodeBtn").onclick = function () {
      var userCode = document.getElementById("userInputCode").value;
      if (userCode == randomCode) {
        // Fetch user ID
        fetch("/src/db/checkSession.php")
          .then((response) => response.json())
          .then((sessionData) => {
            if (sessionData.loggedIn) {
              const userId = sessionData.userId;
              // Proceed with account deletion
              fetch("/src/account/accountDelete.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                },
                body: JSON.stringify({
                  action: "deleteAccount",
                  user_id: userId,
                }),
              })
                .then((response) => response.json())
                .then((data) => {
                  if (data.success) {
                    // Redirige al usuario con parámetro en la URL
                    window.location.href = "/?accountDeleted=true";
                  } else {
                    alert("Error al eliminar la cuenta: " + data.message);
                  }
                })
                .catch((error) => {
                  console.error("Error:", error);
                  alert(
                    "Error al eliminar la cuenta. Por favor, intenta de nuevo."
                  );
                });
            } else {
              alert(
                "No se pudo verificar la sesión. Por favor, inicia sesión nuevamente."
              );
            }
          })
          .catch((error) => {
            console.error("Error al verificar la sesión:", error);
            alert("Error al verificar la sesión. Por favor, intenta de nuevo.");
          });
      } else {
        alert("Código incorrecto. Inténtalo de nuevo.");
      }
    };
  };

  document.getElementById("backToDeleteModalBtn").onclick = function () {
    document.getElementById("codeVerificationModal").style.display = "none";
    document.getElementById("deleteAccountModal").style.display = "block";
  };
});
