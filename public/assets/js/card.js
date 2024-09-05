<<<<<<< HEAD
<<<<<<< HEAD
document.getElementById("tarjeta").addEventListener("input", function () {
  const tarjeta = this.value;
  const cardLogo = document.getElementById("card-logo");

  if (tarjeta.startsWith("5")) {
    cardLogo.src = "/public/assets/img/Mastercard.png";
    cardLogo.style.display = "block";
  } else if (tarjeta.startsWith("4")) {
    cardLogo.src = "/public/assets/img/visa.png";
    cardLogo.style.display = "block";
  } else if (tarjeta.startsWith("3")) {
    cardLogo.src = "/public/assets/img/americanexpress.png";
    cardLogo.style.display = "block";
  } else {
    cardLogo.style.display = "none";
  }
});
document.addEventListener("DOMContentLoaded", function () {
  const cvvField = document.getElementById("cvv");
  const cardNumberField = document.getElementById("tarjeta");

  // Función para determinar el tipo de tarjeta
  function determineCardType(cardNumber) {
    if (cardNumber.startsWith("3")) {
      return "amex";
    } else {
      return "other";
    }
  }

  // Evento para manejar el cambio en el campo del número de tarjeta
  cardNumberField.addEventListener("input", function () {
    const cardNumber = cardNumberField.value;

    if (determineCardType(cardNumber) === "amex") {
      cvvField.maxLength = 4;
      if (cvvField.value.length === 4) {
        return;
      }
    } else {
      cvvField.maxLength = 3;
      if (cvvField.value.length > 3) {
        cvvField.value = cvvField.value.slice(0, 3);
      }
    }
  });
});
=======
// Detectar tarjeta segun el numero
document.getElementById('tarjeta').addEventListener('input', function() {
    const tarjeta = this.value;
    const cardLogo = document.getElementById('card-logo');
=======
document.getElementById("tarjeta").addEventListener("input", function () {
  const tarjeta = this.value;
  const cardLogo = document.getElementById("card-logo");
>>>>>>> e82cc75a02b7f17ee0984c21538c82723fdb7cc9

  if (tarjeta.startsWith("5")) {
    cardLogo.src = "/public/assets/img/Mastercard.png";
    cardLogo.style.display = "block";
  } else if (tarjeta.startsWith("4")) {
    cardLogo.src = "/public/assets/img/visa.png";
    cardLogo.style.display = "block";
  } else if (tarjeta.startsWith("3")) {
    cardLogo.src = "/public/assets/img/americanexpress.png";
    cardLogo.style.display = "block";
  } else {
    cardLogo.style.display = "none";
  }
});
document.addEventListener("DOMContentLoaded", function () {
  const cvvField = document.getElementById("cvv");
  const cardNumberField = document.getElementById("tarjeta");

  // Función para determinar el tipo de tarjeta
  function determineCardType(cardNumber) {
    if (cardNumber.startsWith("3")) {
      return "amex";
    } else {
      return "other";
    }
<<<<<<< HEAD
});
>>>>>>> 07c1bbe16b8418eff07af61e63f38a94b7f0d21a
=======
  }

  // Evento para manejar el cambio en el campo del número de tarjeta
  cardNumberField.addEventListener("input", function () {
    const cardNumber = cardNumberField.value;

    if (determineCardType(cardNumber) === "amex") {
      cvvField.maxLength = 4;
      if (cvvField.value.length === 4) {
        return;
      }
    } else {
      cvvField.maxLength = 3;
      if (cvvField.value.length > 3) {
        cvvField.value = cvvField.value.slice(0, 3);
      }
    }
  });
});
>>>>>>> e82cc75a02b7f17ee0984c21538c82723fdb7cc9
