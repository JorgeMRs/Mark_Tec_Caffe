<?php
session_start();

// Eliminar todas las variables de sesión
session_unset(); 

// Destruir la sesión
session_destroy();

// Eliminar la cookie user_token (JWT)
if (isset($_COOKIE['user_token'])) {
    setcookie('user_token', '', time() - 3600, '/', '', false, true); // Expira la cookie
}

// Eliminar la cookie employee_token (JWT)
if (isset($_COOKIE['employee_token'])) {
    setcookie('employee_token', '', time() - 3600, '/', '', false, true); // Expira la cookie
}

// Redirigir a la página de inicio de sesión
header("Location: ../../public/login.php");
exit(); 
?>
