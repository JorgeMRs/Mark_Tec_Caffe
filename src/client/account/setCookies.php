<?php

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'accept') {
        // Establecer la cookie de preferencia aceptada
        setcookie("cookie_preference", "accepted", time() + (365 * 24 * 60 * 60), "/");
        echo json_encode(["status" => "success", "message" => "Cookie aceptada."]);
    } elseif ($_POST['action'] === 'reject') {
        // Establecer la cookie de preferencia rechazada con una expiración de 5 minutos
        setcookie("cookie_preference", "rejected", time() + 3600, "/");
        echo json_encode(["status" => "success", "message" => "Cookie rechazada."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Acción no válida."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No se recibió acción."]);
}
?>
