<?php
session_start();

// Verificar si el usuario ha iniciado sesión y si su rol es 'mozo'
if (!isset($_SESSION['employee_id']) || $_SESSION['role'] !== 'Mozo') {
    header('Location: /public/error/403.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal del Mozo</title>
    <style>
               body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            font-size: 18px;
            color: #fff;
            background-color: #1B0C0A; /* Added background color */
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }
        .button:hover {
            background-color: #74623c;
        }
        .icon {
            display: block;
            margin: 0 auto 10px auto;
            width: 170px;
            height: 170px;
        }
        .text {
            font-size: 16px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="pagina_pedidos.php" class="button">
            <img class="icon" src="/public/assets/img/pedidos.svg" alt="Icono por defecto">
            Pedidos
        </a>
        <a href="pagina_reservas.php" class="button">
            <img class="icon" src="/public/assets/img/reservas.svg" alt="Icono por defecto">
            Reservas
        </a>
    </div>
</body>
</html>
