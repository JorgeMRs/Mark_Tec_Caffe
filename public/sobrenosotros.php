<!DOCTYPE html>
<html lang="en">
<?php 

$pageTitle = 'Café Sabrosos - Sobre Nosotros';

$customCSS = [
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
<style>
        :root {
            --primary-color: #6b4226;
            --secondary-color: #d4a574;
            --text-color: #333;
            --background-color: #f9f5f1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            margin: 0;
            padding: 0;
            background-image: url("/public/assets/img/index/bg_4.jpg");
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        h1 {
            margin: 0;
            font-size: 2.5rem;
            color: #daa520;
        }
        main {
            padding: 2rem 0;
        }

        .about-section {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            align-items: center;
        }

        .about-image {
            flex: 1;
            min-width: 300px;
        }

        .about-image img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .about-content {
            flex: 2;
            min-width: 300px;
        }

        h2 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #daa520;
        }

        .values {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
        }

        .value-item {
            flex: 1;
            min-width: 200px;
            background-color: #1a0c0b;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .value-item h3 {
            color: #daa520;
            margin-top: 0;
        }

        p {
            color: white;
        }
        @media (max-width: 768px) {
            .about-section {
                flex-direction: column;
            }

            .about-image, .about-content {
                min-width: 100%;
            }
        }
    </style>
<main>
        <div class="container">
            <section class="about-section">
                <div class="about-image">
                    <img src="/public/assets/img/como-montar-cafeteria-.webp" alt="Café Sabrosos interior">
                    <img src="/public/assets/img/sobrenosotros.jpg" alt="Café Sabrosos interior">
                </div>
                <div class="about-content">
                    <h2 id="aboutTitle">Sobre Nosotros</h2>
                    <p id="aboutContent1">Fundado en 1990 por la familia Valdez, Café Sabrosos es más que un lugar donde disfrutar de un buen café; es un refugio de historias, recuerdos y momentos compartidos. En sus inicios, este acogedor rincón nació de la visión de la abuela Carmen Valdez, quien con su sonrisa cálida y sus manos hábiles comenzó a moler los granos de café en aquel pequeño espacio, llenando el aire con el aroma que ha conquistado generaciones. La abuela Carmen no solo ofrecía café; brindaba a cada visitante un hogar temporal, donde la hospitalidad y la conversación sincera eran tan esenciales como el propio café.
                    </p>
                    <p id="aboutContent2">Con el paso de los años, Sabrosos se ha convertido en un lugar de encuentro único, donde las historias se entrelazan con el sabor del espresso y el susurro del molinillo. Vecinos, amigos y visitantes encuentran aquí un lugar donde las mesas de madera desgastada han sido testigo de incontables charlas, risas, lágrimas y sueños. Es común ver a estudiantes repasando para sus exámenes finales, con la tranquilidad de que en cada rincón de este café encontrarán un espacio de inspiración y concentración. Para los turistas, descubrir Sabrosos es hallar un rincón escondido que se convierte en un pequeño tesoro personal, una historia que contar a su regreso.</p>
                    <p id="aboutContent3">Café Sabrosos se enorgullece de su fuerte compromiso con la comunidad. No solo es un negocio, sino un pilar para la vida local. Cada año, el café patrocina eventos que promueven el arte y la cultura de la zona, y ofrece apoyo a iniciativas que enriquecen la vida de sus vecinos. La familia Valdez se asegura de donar café a la biblioteca local, y ofrece descuentos especiales a maestros y a otros trabajadores de la comunidad como muestra de gratitud. Este sentido de comunidad y pertenencia es lo que realmente define a Sabrosos, un lugar donde todos son bienvenidos y donde cada taza de café lleva consigo un abrazo cálido.</p>
                </div>
            </section>

            <section class="values">
                <div class="value-item">
                    <h3 id="valueQuality">Calidad</h3>
                    <p id="valueQualityDesc">Nunca comprometemos la calidad de nuestro café o nuestros ingredientes. Cada taza está elaborada con cuidado y precisión.</p>
                </div>
                <div class="value-item">
                    <h3 id="valueCommunity">Comunidad</h3>
                    <p id="valueCommunityDesc">
                    Somos más que una simple cafetería; Somos un lugar de reunión para que amigos, familias y vecinos se conecten y compartan.</p>
                </div>
                <div class="value-item">
                    <h3 id="valueSustainability">Sostenibilidad</h3>
                    <p id="valueSustainabilityDesc">Desde nuestro abastecimiento de café hasta nuestras prácticas ecológicas, estamos comprometidos a minimizar nuestro impacto ambiental.</p>
                </div>
            </section>
        </div>
    </main>

<footer>
    <?php include 'templates/footer.php'; ?>
</footer>
</body>
</html>
