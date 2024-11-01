<?php
// require '../src/db/db_connect.php';

// $conn = getDbConnection();

// $sql = "SELECT nombre, direccion, pais, ciudad, tel FROM sucursal";
// $result = $conn->query($sql);

// $locations = [];

// if ($result->num_rows > 0) {
//     // Output data of each row
//     while ($row = $result->fetch_assoc()) {
//         $locations[$row['pais']] = $row;
//     }
// } else {
//     echo "0 results";
// }
// $conn->close();
?>


<!-- <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Descubre los locales de Café Sabrosos en todo el mundo. Disfruta de nuestro café de alta calidad y ambiente acogedor.">
    <meta name="keywords" content="Cafe, Sabrosos, Cafe Sabrosos, Sabrosos Cafe, locales, internacional">
    <meta name="author" content="Mark Tec">
    <title>Café Sabrosos - Locales Internacionales</title>
    <link rel="stylesheet" href="assets/css/stylelocal.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="48x48" href="assets/img/icons/favicon-64x64.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet' />
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map-container {
            width: 100%;
            height: 100vh; /* Ajusta la altura según tus necesidades */
            position: relative;
            border-radius: 15px; /* Bordes redondeados */
            overflow: hidden; /* Asegura que el contenido no se desborde */
        }

        #map {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
            border-radius: inherit; /* Hereda los bordes redondeados del contenedor */
        }

        .mapboxgl-popup {
            max-width: 300px;
            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
        }

        .custom-marker {
            background-color: #ff6347; /* Color de fondo */
            width: 30px; /* Ancho del marcador */
            height: 30px; /* Altura del marcador */
            border-radius: 50%; /* Forma circular */
            display: flex;
            align-items: center;
            justify-content: center;
            color: white; /* Color del texto */
            font-size: 14px; /* Tamaño del texto */
            font-weight: bold; /* Peso del texto */
            border: 2px solid white; /* Borde blanco */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Sombra */
            cursor: pointer; /* Cursor de mano */
        }

        .mapboxgl-popup-content {
            color: #333; /* Color del texto */
            background-color: #fff; /* Color de fondo */
            border-radius: 5px; /* Bordes redondeados */
            padding: 10px; /* Relleno */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra */
        }

        .mapboxgl-popup-content h3 {
            color: #ff6347; /* Color del título */
            margin: 0 0 5px; /* Márgenes */
            font-size: 16px; /* Tamaño del título */
        }

        .mapboxgl-popup-content p {
            color: #666; /* Color del párrafo */
            margin: 0; /* Márgenes */
            font-size: 14px; /* Tamaño del párrafo */
        }

        .mapboxgl-popup-content img {
            width: 100%; /* Ancho de la imagen */
            height: auto; /* Altura automática para mantener la proporción */
            border-radius: 5px; /* Bordes redondeados */
            margin-bottom: 10px; /* Espacio inferior */
        }
    </style>
</head>

<body class="body-local">
    <header>
        <?php include 'templates/nav.php' ?>
    </header>

    <br>
    <main>
        <section class="hero">
            <h1 class="sub-title">Mapa de Nuestros Locales</h1>
        </section>

        <section class="mapa-world">
            <div id="map-container">
                <div id="map"></div>
            </div>

        </section>
        <script>
            document.querySelectorAll('.pin').forEach(pin => {
                const pinDot = pin.querySelector('.pin-dot');
                const pinInfo = pin.querySelector('.pin-info');
                const countryLabel = pin.querySelector('.pin-country-label');

                pinDot.addEventListener('mouseover', () => {
                    pinInfo.style.opacity = '1';
                    pinInfo.style.transform = 'translateX(-50%) translateY(0)';
                    pin.classList.add('hover'); // Add a class to handle ::after
                });

                pinDot.addEventListener('mouseout', () => {
                    pinInfo.style.opacity = '0';
                    pinInfo.style.transform = 'translateX(-50%) translateY(10px)';
                    pin.classList.remove('hover');
                });

                pin.addEventListener('mouseleave', () => {
                    pinInfo.style.opacity = '0';
                    pinInfo.style.transform = 'translateX(-50%) translateY(10px)';
                    pin.classList.remove('hover');
                });
            });
        </script>
        <script>
            // fix para la superposicion del pin de España cuando se tiene abierto el pin-info de Francia
            document.querySelectorAll('.pin').forEach(pin => {
                const pinDot = pin.querySelector('.pin-dot');
                const pinInfo = pin.querySelector('.pin-info');

                pinDot.addEventListener('mouseover', () => {
                    // Cambia el z-index al hacer hover
                    pin.style.zIndex = '11';
                    pinInfo.style.opacity = '1';
                    pinInfo.style.transform = 'translateX(-50%) translateY(0)';
                });

                pinDot.addEventListener('mouseout', () => {
                    // Oculta el pinInfo y usa setTimeout para retrasar la restauración del z-index
                    pinInfo.style.opacity = '0';
                    pinInfo.style.transform = 'translateX(-50%) translateY(10px)';

                    // Retrasa el z-index restaurado por 1000 ms
                    setTimeout(() => {
                        pin.style.zIndex = '10';
                    }, 200);
                });
            });
        </script>
        <style>
            :root {
                --background-color: #f4f4f4;
                --primary-color: #D4AF37;
                /* Dorado */
                --primary-text: #ffffff;
                --secondary-color: #ffffff;
                --secondary-text: #4b3621;
                /* Marrón */
                --muted-text: #6b7280;
                --border-radius: 16px;
                --box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
                --transition-duration: 0.3s;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 40px 20px;
            }

            .locales-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
            }

            .local-card {
                background-color: var(--secondary-color);
                border-radius: var(--border-radius);
                overflow: hidden;
                box-shadow: var(--box-shadow);
                transition: transform var(--transition-duration) ease, box-shadow var(--transition-duration) ease;
            }

            .local-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            }

            .local-image {
                height: 250px;
                background-size: cover;
                background-position: center;
                position: relative;
            }

            .local-title,
            .local-hours p {
                color: #1b0d0b;
            }

            .label {
                background-color: var(--primary-color);
                color: var(--primary-text);
                padding: 8px 12px;
                border-radius: var(--border-radius);
                position: absolute;
                top: 10px;
                left: 10px;
                font-weight: 600;
            }

            .local-info {
                padding: 20px;
            }

            .local-title {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 10px;
            }

            .local-address {
                color: var(--muted-text);
                margin-bottom: 15px;
            }

            .local-hours {
                display: flex;
                align-items: center;
                margin-bottom: 20px;
            }

            .icon-clock {
                width: 20px;
                height: 20px;
                margin-right: 10px;
                fill: var(--muted-text);
            }

            .btn-secondary {
                background-color: var(--background-color);
                border: 1px solid var(--secondary-text);
                color: var(--secondary-text);
                padding: 10px;
                border-radius: var(--border-radius);
                text-align: center;
                width: 100%;
                font-weight: 600;
                transition: background-color var(--transition-duration) ease, color var(--transition-duration) ease;
            }

            .btn-secondary:hover {
                background-color: var(--secondary-text);
                color: var(--primary-text);
            }
        </style>
        <div class="container">
            <section class="locales-grid">
             
                <div class="local-card">
                    <div class="local-image"
                        style="background-image: url('/public/assets/img/hamza-nouasria-P2mIRmNIIPQ-unsplash.jpg');">
                        <div class="label">Francia</div>
                    </div>
                    <div class="local-info">
                        <h2 class="local-title">Café Sabrosos París</h2>
                        <p class="local-address">Boulevard Saint-Germain 56, Paris, Francia</p>
                        <div class="local-hours">
                            <i class="fa-solid fa-clock" style="color: #1b0d0b; margin-right: 10px;"></i>
                            <p>Lunes a Viernes: 7:00 - 19:00</p>
                        </div>
                        <button class="btn-secondary">Realizar reserva</button>
                    </div>
                </div>
             
                <div class="local-card">
                    <div class="local-image"
                        style="background-image: url('/public/assets/img/senya-mitin-PIy8Hrys8bQ-unsplash.jpg');">
                        <div class="label">Alemania</div>
                    </div>
                    <div class="local-info">
                        <h2 class="local-title">Café Sabrosos Berlin</h2>
                        <p class="local-address">Kurfürstendamm 100, Berlin, Alemania</p>
                        <div class="local-hours">
                            <i class="fa-solid fa-clock" style="color: #1b0d0b; margin-right: 10px;"></i>
                            <p>Lunes a Sábado: 8:00 - 20:00</p>
                        </div>
                        <button class="btn-secondary">Realizar reserva</button>
                    </div>
                </div>
               
                <div class="local-card">
                    <div class="local-image"
                        style="background-image: url('/public/assets/img/kishore-v-tf7Y9kMhETg-unsplash.jpg');">
                        <div class="label">Portugal</div>
                    </div>
                    <div class="local-info">
                        <h2 class="local-title">Café Sabrosos Lisboa</h2>
                        <p class="local-address">Rua de São Bento 123, Lisboa, Portugal</p>
                        <div class="local-hours">
                            <i class="fa-solid fa-clock" style="color: #1b0d0b; margin-right: 10px;"></i>
                            <p>Lunes a Domingo: 7:00 - 22:00</p>
                        </div>
                        <button class="btn-secondary">Realizar reserva</button>
                    </div>
                </div>
                <div class="local-card">
                    <div class="local-image" style="background-image: url('/public/assets/img/madrid.jpg');">
                        <div class="label">España</div>
                    </div>
                    <div class="local-info">
                        <h2 class="local-title">Café Sabrosos Madrid</h2>
                        <p class="local-address">Calle Gran Vía 45, Madrid, España</p>
                        <div class="local-hours">
                            <i class="fa-solid fa-clock" style="color: #1b0d0b; margin-right: 10px;"></i>
                            <p>Lunes a Domingo: 7:00 - 22:00</p>
                        </div>
                        <button class="btn-secondary">Realizar reserva</button>
                    </div>
                </div>
            </section>
        </div>
    </main>

     <?php include 'templates/footer.php'; ?>
</body>
<script src="/public/assets/js/updateCartCounter.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        mapboxgl.accessToken = 'pk.eyJ1Ijoiam9yZ2VtcnMiLCJhIjoiY20yaTYycXZhMGpwazJrcTMxZjJ3YXpjaiJ9.XWdkZoY8ZWW8aAteuvFkAA';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [2.3522, 48.8566], // París
            zoom: 4
        });

        var locations = [
            {
                name: "Rua de São Bento 123, Lisboa, Portugal",
                coordinates: [-9.1393, 38.7123],
                description: "Esta dirección está en Lisboa, la capital de Portugal.",
                image: "/public/assets/img/kishore-v-tf7Y9kMhETg-unsplash.jpg" // Ruta a la imagen
            },
            {
                name: "Kurfürstendamm 100, Berlín, Alemania",
                coordinates: [13.3295, 52.5033],
                description: "Esta dirección se encuentra en Berlín, la capital de Alemania. Kurfürstendamm es una avenida famosa en Berlín.",
                image: "/public/assets/img/senya-mitin-PIy8Hrys8bQ-unsplash.jpg" // Ruta a la imagen
            },
            {
                name: "Boulevard Saint-Germain 56, París, Francia",
                coordinates: [2.3390, 48.8539],
                description: "Esta dirección está en París, la capital de Francia. El Boulevard Saint-Germain es una de las avenidas más conocidas de París.",
                image: "/public/assets/img/hamza-nouasria-P2mIRmNIIPQ-unsplash.jpg" // Ruta a la imagen
            }
        ];


        map.on('load', function () {
            locations.forEach(function (location) {
                var popup = new mapboxgl.Popup({ offset: 25 }).setHTML(
                    '<img src="' + location.image + '" alt="' + location.name + '">' +
                    '<h3>' + location.name + '</h3><p>' + location.description + '</p>'
                );

                var el = document.createElement('div');
                el.className = 'custom-marker';
                el.innerHTML = location.name.charAt(0); // Mostrar la primera letra del nombre

                new mapboxgl.Marker(el)
                    .setLngLat(location.coordinates)
                    .setPopup(popup)
                    .addTo(map);
            });
        });
    });
</script>

</html> -->

<!DOCTYPE html>
<?php
require '../src/db/db_connect.php';

$conn = getDbConnection();

$locations = [
    [
        'id' => 1,
        'nombre' => 'Café Sabrosos Lisboa',
        'direccion' => 'Rua Augusta 24',
        'pais' => 'Portugal',
        'ciudad' => 'Lisboa',
        'tel' => '+351 21 346 7890',
        'latitud' => 38.7095,
        'longitud' => -9.1395,
        'img' => 'lisboa.jpg'
    ],
    [
        'id' => 2,
        'nombre' => 'Café Sabrosos Madrid',
        'direccion' => 'Calle Gran Vía 41',
        'pais' => 'España',
        'ciudad' => 'Madrid',
        'tel' => '+34 912 345 678',
        'latitud' => 40.4200,
        'longitud' => -3.7021,
        'img' => 'madrid.jpg'
    ],
    [
        'id' => 3,
        'nombre' => 'Café Sabrosos Berlin',
        'direccion' => 'Unter den Linden 77',
        'pais' => 'Alemania',
        'ciudad' => 'Berlín',
        'tel' => '+49 30 2345 6789',
        'latitud' => 52.5170,
        'longitud' => 13.3889,
        'img' => 'berlin.png'
    ],
    [
        'id' => 4,
        'nombre' => 'Café Sabrosos Paris',
        'direccion' => '24 Rue du Faubourg Saint-Honoré',
        'pais' => 'Francia',
        'ciudad' => 'París',
        'tel' => '+33 1 4567 8901',
        'latitud' => 48.8704,
        'longitud' => 2.3167,
        'img' => 'paris.jpg'
    ]
];

$pageTitle = 'Café Sabrosos - Locales';

$customCSS = [
    '/public/assets/css/stylelocal.css',
    '/public/assets/css/nav.css',
    '/public/assets/css/footer.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css',
    'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css'
];

$customJS = [
    'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js'
];

include 'templates/head.php';
?>

<body class="body-local">
    <header>
        <?php include 'templates/nav.php' ?>
    </header>

    <main>
        <div class="hero">
            <h1>Nuestros Locales</h1>
        </div>
        <div id="map" style="height: 500px; width: 100%;"></div>
        <div class="container">
            <section class="locales-grid">
                <?php foreach ($locations as $local): ?>
                    <div class="local-card">
                        <div class="local-image" style="background-image: url('/public/assets/img/sucursales/<?php echo strtolower($local['img']);?>');">
                            <div class="label"><?php echo $local['pais']; ?></div>
                        </div>
                        <div class="local-info">
                            <h2 class="local-title"><?php echo $local['nombre']; ?></h2>
                            <p class="local-address">Dirección: <?php echo $local['direccion']; ?></p>
                            <p class="local-address">Teléfono: <?php echo $local['tel']; ?></p>
                            <div class="local-hours">
                                <i class="fa-solid fa-clock"></i>
                                <p>Lunes a Domingo: 7:00 - 22:00</p>
                            </div>
                            <a href="mesas.php?sucursal=<?php echo $local['id']; ?>" class="btn-secondary">Hacer Reserva</a>
                            <button class="btn-secondary mt-2 view-on-map" data-lat="<?php echo $local['latitud']; ?>" data-lng="<?php echo $local['longitud']; ?>">Ver en el Mapa</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </div>
    </main>

    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <?php include 'templates/footer.php'; ?>

    <script>
        var map = L.map('map').setView([48.8566, 2.3522], 4); // Centrado en Europa

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var locations = <?php echo json_encode($locations); ?>;
        var markers = {};

        locations.forEach(function(location) {
            var marker = L.marker([location.latitud, location.longitud])
                .addTo(map)
                .bindPopup(location.nombre + '<br>' + location.direccion + '<br>Tel: ' + location.tel);
            markers[location.id] = marker;
        });

        document.querySelectorAll('.view-on-map').forEach(function(button) {
            button.addEventListener('click', function() {
                var lat = this.getAttribute('data-lat');
                var lng = this.getAttribute('data-lng');
                map.setView([lat, lng], 15);
                var localCard = this.closest('.local-card');
                var localId = localCard.querySelector('.btn-secondary').href.split('=')[1];
                if (markers[localId]) {
                    markers[localId].openPopup();
                }
            });
        });
    </script>
</body>
</html>