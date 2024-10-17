<!DOCTYPE html>
<html lang="en">
<?php 

$pageTitle = 'Café Sabrosos - Políticas de Eliminación de Cuenta';

$customCSS = [
    '/public/assets/css/politicas.css',
    '/public/assets/css/nav.css',
    '/public/assets/css/footer.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'
];
$customJS = [
    '/public/assets/js/languageSelect.js',
    '/public/assets/js/updateCartCounter.js'
  ];
include 'templates/head.php'; 
?>
<body>
<header>
    <?php include 'templates/nav.php'; ?>
</header>
<br>
<main class="data-deletion-policy">
    <h1>Sobre Nosotros</h1>

    <div>
        <p>Fundado en 1990 por la familia Valdez, este acogedor rincón se convirtió en el punto de encuentro para los amantes del buen café y la conversación sincera. La abuela Carmen Valdez, con su sonrisa cálida y manos hábiles, fue la primera en moler los granos de café en aquel rincón.
        </p>
        
        <p>Sabrosos no es solo un café; es un lugar donde las historias se tejen entre sorbos de espresso. Los vecinos comparten sus alegrías y penas en las mesas de madera desgastada. Los estudiantes estudian para sus exámenes finales mientras el aroma del café flota en el aire. Los turistas, al descubrirlo, lo consideran su secreto mejor guardado. 
        </p>
        
        <p>Café Sabrosos teniene un fuerte compromiso con la comunidad. Patrocinamos eventos locales, donamos café a la biblioteca y ofrecemos descuentos a los maestros. La gente no solo veniene por el café, sino también por la calidez y el sentido de pertenencia que encontraban en Sabrosos. 
        </p>
    </div>
</main>
<br>
<footer>
    <?php include 'templates/footer.php'; ?>
</footer>
</body>
</html>
