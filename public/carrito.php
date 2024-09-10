<?php
session_start();

// Verifica si se está enviando la solicitud para realizar el pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['order_placed'] = true;
    header('Location: pagar.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Sabrosos - Carrito de Compras</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/assets/css/carrito.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>
    <header>
        <?php include 'templates/nav.php'; ?>
    </header>
    <div class="flex flex-col min-h-screen">
        <main class="flex-1 container mx-auto py-8 px-4 md:px-6">
            <h1 class="text-2xl font-bold mb-6">Carrito de Compras</h1>
            <div id="padre2" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="col-span-2 space-y-4">
                    <div id="productos" class="bg-background rounded-lg shadow p-4">
                        <!-- Productos se insertarán aquí -->
                    </div>
                </div>
                <div class="totalP">
                    <div class="bg-background rounded-lg shadow p-6 space-y-4">
                        <h2 class="text-lg font-medium">Resumen del pedido</h2>
                        <div class="flex items-center justify-between">
                            <span>Subtotal</span>
                            <span id="subtotal">€0.00</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>IVA</span>
                            <span id="tax">€0.00</span>
                        </div>
                        <div class="divider"></div>
                        <div class="flex items-center justify-between font-medium text-lg">
                            <span>Total</span>
                            <span id="total">€0.00</span>
                        </div>
                        <form action="carrito.php" method="post">
                            <button type="submit" class="checkout-btn">Realizar pedido</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <footer>
        <?php include 'templates/footer.php'; ?>
    </footer>
    <script src="/public/assets/js/carrito.js"></script>
    <script src="/public/assets/js/updateCartCounter.js"></script>
</body>

</html>