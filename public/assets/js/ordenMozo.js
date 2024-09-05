document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('orderForm');
    const sendToKitchenBtn = document.getElementById('sendToKitchen');
    const viewHistoryBtn = document.getElementById('viewHistory');

    sendToKitchenBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const orderData = Object.fromEntries(formData);
        console.log('Orden enviada a cocina:', orderData);
        // Aquí puedes agregar la lógica para enviar la orden a la cocina
    });

    viewHistoryBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Ver historial de órdenes');
        // Aquí puedes agregar la lógica para ver el historial de órdenes
        
    });
});
