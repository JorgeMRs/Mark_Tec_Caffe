<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/assets/css/carrito.css">
</head>

<body>
    <div class="flex flex-col min-h-screen">
        <main class="flex-1 container mx-auto py-8 px-4 md:px-6">
            <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>
            <div id="padre2" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="col-span-2 space-y-4">
                    <div id="productos" class="bg-background rounded-lg shadow p-4">
                        <!-- Productos se insertarán aquí -->
                    </div>
                </div>
                <div class="totalP">
                    <div class="bg-background rounded-lg shadow p-6 space-y-4">
                        <h2 class="text-lg font-medium">Order Summary</h2>
                        <div class="flex items-center justify-between">
                            <span>Subtotal</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Tax</span>
                            <span id="tax">$0.00</span>
                        </div>
                        <div class="divider"></div>
                        <div class="flex items-center justify-between font-medium text-lg">
                            <span>Total</span>
                            <span id="total">$0.00</span>
                        </div>
                        <button class="checkout-btn">Proceed to Checkout</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="../public/assets/js/carritoP.js"></script>
    <script>
        session_start();
        $user_id = $_SESSION['user_id'];
        const userId = <?php echo json_encode($user_id); ?>; // ID del cliente desde la sesión
        // const userId = 10; // ID del cliente
        const ivaRate = 0.21; // Tasa de IVA (21%)

        fetch(`/src/db/get_cart_products.php?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                const productsContainer = document.getElementById('productos');
                let subtotal = 0;

                data.forEach(product => {
                    const totalPrice = product.precio * product.cantidad;
                    const productHTML = `
                       <div id="carta" class="flex items-center gap-4 bg-background" data-product-id="${product.idProducto}">
                            <div class="imagenP flex-1">
                                <img id="imagen" src="${product.imagen}" alt="${product.nombre}" width="80" height="80" class="rounded-md">
                            </div>
                            <div id="nombredesc" class="flex-1">
                                <h3 class="font-medium text-lg">${product.nombre}</h3>
                                <p class="text-muted-foreground text-sm">${product.descripcion}</p>
                            </div>
                            <div id="cantidad" class="flex items-center gap-2">
                                <button class="quantity-btn minus">-</button>
                                <span id="cantidadnum" data-precio="${product.precio}">${product.cantidad}</span>
                                <button class="quantity-btn plus">+</button>
                            </div>
                            <div id="precionu" class="font-medium">$${totalPrice.toFixed(2)}</div>
                            <button class="remove-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    `;

                    productsContainer.innerHTML += productHTML;

                    subtotal += totalPrice;
                });

                // Actualizar el subtotal en el DOM
                document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;

                // Calcular y actualizar el IVA y el total en el DOM
                const tax = subtotal * ivaRate;
                document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
                document.getElementById('total').textContent = `$${(subtotal + tax).toFixed(2)}`;

                // Añadir event listeners después de insertar los productos
                addEventListeners();
            })
            .catch(error => console.error('Error:', error));


        function updateSubtotalAndTax() {
            let subtotal = 0;
            document.querySelectorAll('#cantidadnum').forEach(span => {
                const quantity = parseInt(span.textContent);
                const price = parseFloat(span.getAttribute('data-precio'));
                subtotal += quantity * price;
            });
            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;

            // Calcular y actualizar el IVA y el total
            const tax = subtotal * ivaRate;
            document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
            document.getElementById('total').textContent = `$${(subtotal + tax).toFixed(2)}`;
        }
    </script>
</body>

</html>