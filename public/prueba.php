<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/assets/css/prueba.css">
</head>

<body>
    <div class="flex flex-col min-h-screen">
        <main class="flex-1 container mx-auto py-8 px-4 md:px-6">
            <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>
            <div id="padre2" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="col-span-2 space-y-4">
                    <div id="productos" class="bg-background rounded-lg shadow p-4">
                        <!-- Product 1 -->
                        <!-- <div id="carta " class="flex items-center gap-4">
                            <div class="imagenP flex-1 ">
                                <img id="imagen" src="/public/assets/img/pruebaborrar/curso4.jpg" alt="Product Image"
                                    width="80" height="80" class="rounded-md">
                            </div>
                            <div id="nombredesc" class="flex-1">
                                <h3 class="font-medium text-lg">Cozy Blanket</h3>
                                <p class="text-muted-foreground text-sm">Warm and Soft for Chilly Nights</p>
                            </div>
                            <div id="cantidad" class="flex items-center gap-2">
                                <button class="quantity-btn minus">-</button>
                                <span id="cantidadnum">2</span>
                                <button class="quantity-btn plus">+</button>
                            </div>
                            <div id="precionu" class="font-medium">$59.99</div>
                            <button class="remove-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                      
                        <div id="carta" class="flex items-center gap-4">
                            <div class="imagenP flex-1 ">
                                <img id="imagen" src="/public/assets/img/pruebaborrar/curso4.jpg" alt="Product Image"
                                    width="80" height="80" class="rounded-md">
                            </div>
                            <div id="nombredesc" class="flex-1">
                                <h3 class="font-medium text-lg">Cozy Blanket</h3>
                                <p class="text-muted-foreground text-sm">Warm and Soft for Chilly Nights</p>
                            </div>
                            <div id="cantidad" class="flex items-center gap-2">
                                <button class="quantity-btn minus">-</button>
                                <span id="cantidadnum">2</span>
                                <button class="quantity-btn plus">+</button>
                            </div>
                            <div id="precionu" class="font-medium">$59.99</div>
                            <button class="remove-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div> -->
                    </div>

                </div>
                <div class="totalP" >
                    <div class="bg-background rounded-lg shadow p-6 space-y-4">
                        <h2 class="text-lg font-medium">Order Summary</h2>
                        <div class="flex items-center justify-between">
                            <span>Subtotal</span>
                            <span>$72.98</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Tax</span>
                            <span>$5.84</span>
                        </div>
                        <div class="divider"></div>
                        <div class="flex items-center justify-between font-medium text-lg">
                            <span>Total</span>
                            <span>$78.82</span>
                        </div>
                        <button class="checkout-btn">Proceed to Checkout</button>
                    </div>
                </div>
            </div>
        </main>
        <!-- <footer class="bg-muted/40 py-6 px-4 md:px-6">
            <div class="container mx-auto flex items-center justify-between">
                <p class="text-muted-foreground text-sm">© 2024 Acme Store. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a class="text-muted-foreground hover:text-foreground" href="#">Privacy Policy</a>
                </div>
            </div>
        </footer> -->
    </div>
    <script src="../public/assets/js/prueba.js"></script>
    <script>
        const userId = 10; // ID del cliente

        fetch(`/src/db/get_cart_products.php?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                const productsContainer = document.getElementById('productos');
                let subtotal = 0;

                data.forEach(product => {
                    const productHTML = `
                        <div id="carta" class="flex items-center gap-4">
                            <div class="imagenP flex-1">
                                <img id="imagen" src="${product.imagen}" alt="${product.nombre}" width="80" height="80" class="rounded-md">
                            </div>
                            <div id="nombredesc" class="flex-1">
                                <h3 class="font-medium text-lg">${product.nombre}</h3>
                                <p class="text-muted-foreground text-sm">${product.descripcion}</p>
                            </div>
                            <div id="cantidad" class="flex items-center gap-2">
                                <button class="quantity-btn minus">-</button>
                                <span id="cantidadnum">${product.cantidad}</span>
                                <button class="quantity-btn plus">+</button>
                            </div>
                            <div id="precionu" class="font-medium">$${product.precio}</div>
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

                    subtotal += product.precio * product.cantidad;
                });

                // Aquí puedes actualizar el subtotal en el DOM si es necesario
                // document.getElementById('subtotal').innerText = `$${subtotal.toFixed(2)}`;
            })
            .catch(error => console.error('Error:', error));
    </script>
</body>

</html>