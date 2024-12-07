<?php

function encryptData($data, $encryptionKey) {
    $ivLength = openssl_cipher_iv_length('AES-256-CBC');
    $iv = openssl_random_pseudo_bytes($ivLength);
    $ciphertext = openssl_encrypt($data, 'AES-256-CBC', $encryptionKey, 0, $iv);
    return base64_encode($iv . '::' . $ciphertext);
}

// Función para descifrar datos
function decryptData($encryptedData, $encryptionKey) {
    list($iv, $ciphertext) = explode('::', base64_decode($encryptedData), 2);
    return openssl_decrypt($ciphertext, 'AES-256-CBC', $encryptionKey, 0, $iv);
}
?>