// Detectar tarjeta segun el numero
document.getElementById('tarjeta').addEventListener('input', function() {
    const tarjeta = this.value;
    const cardLogo = document.getElementById('card-logo');

    if (tarjeta.startsWith('5')) {
        cardLogo.src = "/public/assets/img/Mastercard.png";
        cardLogo.style.display = 'block';
    } else if (tarjeta.startsWith('4')) {
        cardLogo.src = '/public/assets/img/visa.png';
        cardLogo.style.display = 'block';
    } else {
        cardLogo.style.display = 'none';
    }
});