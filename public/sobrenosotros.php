<!DOCTYPE html>
<html lang="en">
<?php 

$pageTitle = 'Café Sabrosos - Sobre Nosotros';

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
        <p>Fundado en 1990 por la familia Valdez, Café Sabrosos es más que un lugar donde disfrutar de un buen café; es un refugio de historias, recuerdos y momentos compartidos. En sus inicios, este acogedor rincón nació de la visión de la abuela Carmen Valdez, quien con su sonrisa cálida y sus manos hábiles comenzó a moler los granos de café en aquel pequeño espacio, llenando el aire con el aroma que ha conquistado generaciones. La abuela Carmen no solo ofrecía café; brindaba a cada visitante un hogar temporal, donde la hospitalidad y la conversación sincera eran tan esenciales como el propio café.
        </p>
        
        <p>Con el paso de los años, Sabrosos se ha convertido en un lugar de encuentro único, donde las historias se entrelazan con el sabor del espresso y el susurro del molinillo. Vecinos, amigos y visitantes encuentran aquí un lugar donde las mesas de madera desgastada han sido testigo de incontables charlas, risas, lágrimas y sueños. Es común ver a estudiantes repasando para sus exámenes finales, con la tranquilidad de que en cada rincón de este café encontrarán un espacio de inspiración y concentración. Para los turistas, descubrir Sabrosos es hallar un rincón escondido que se convierte en un pequeño tesoro personal, una historia que contar a su regreso.
        </p>
        
        <p>Café Sabrosos se enorgullece de su fuerte compromiso con la comunidad. No solo es un negocio, sino un pilar para la vida local. Cada año, el café patrocina eventos que promueven el arte y la cultura de la zona, y ofrece apoyo a iniciativas que enriquecen la vida de sus vecinos. La familia Valdez se asegura de donar café a la biblioteca local, y ofrece descuentos especiales a maestros y a otros trabajadores de la comunidad como muestra de gratitud. Este sentido de comunidad y pertenencia es lo que realmente define a Sabrosos, un lugar donde todos son bienvenidos y donde cada taza de café lleva consigo un abrazo cálido.
        </p>
    </div>
</main>
<br>
<footer>
    <?php include 'templates/footer.php'; ?>
</footer>
</body>
</html>
