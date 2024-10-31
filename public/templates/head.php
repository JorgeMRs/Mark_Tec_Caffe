<?php
session_start();

require_once __DIR__ . '/../../vendor/autoload.php';


use Stichoza\GoogleTranslate\GoogleTranslate;

// Verificar si existe un idioma en la sesión si no establecer por defecto 'es'
$idioma = isset($_SESSION['idioma']) ? $_SESSION['idioma'] : 'es';

$tr = new GoogleTranslate($idioma);

// Traducir el título de la página
$pageTitleTranslated = isset($pageTitle) ? $tr->translate($pageTitle) : $tr->translate('Café Sabrosos');
?>

<html lang="<?php echo htmlspecialchars($idioma); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitleTranslated); ?></title>
    <meta name="description" content="Bienvenido a Café Sabrosos, tu tienda online para comprar los mejores cafés artesanales.">

    <!-- Meta etiquetas para Open Graph (redes sociales) -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitleTranslated); ?>">
    <meta property="og:description" content="Los mejores cafés artesanales disponibles en nuestra tienda online.">
    <meta property="og:image" content="/public/assets/images/cafe-sabrosos-og-image.jpg">
    <meta property="og:url" content="https://cafesabrosos.myvnc.com">

    <!-- Meta etiquetas para Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitleTranslated); ?>">
    <meta name="twitter:description" content="Los mejores cafés artesanales a tu alcance.">
    <meta name="twitter:image" content="/public/assets/images/cafe-sabrosos-twitter-image.jpg">

    <!-- Favicon para navegadores (16x16 o 32x32) -->
    <link rel="icon" href="/public/assets/icons/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/public/assets/icons/favicon.ico" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/icons/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/public/assets/icons/apple-touch-icon.png">
    <link rel="manifest" href="/public/assets/icons/site.webmanifest">
    <meta name="theme-color" content="#1B0D0B">

    <!-- CSS dinámico -->
    <?php
    if (isset($customCSS)) {
        foreach ($customCSS as $cssFile) {
            echo '<link rel="stylesheet" href="' . htmlspecialchars($cssFile) . '">' . PHP_EOL;
        }
    }
    ?>

    <!-- JS dinámico -->
    <?php
    if (isset($deferJS)) {
        foreach ($deferJS as $jsFile) {
            echo '<script src="' . htmlspecialchars($jsFile) . '" defer></script>' . PHP_EOL;
        }
    }

    if (isset($moduleJS)) {
        foreach ($moduleJS as $jsFile) {
            echo '<script src="' . htmlspecialchars($jsFile) . '" type="module"></script>' . PHP_EOL;
        }
    }
    
    if (isset($customJS)) {
        foreach ($customJS as $jsFile) {
            echo '<script src="' . htmlspecialchars($jsFile) . '"></script>' . PHP_EOL;
        }
    }
    ?>
</head>
