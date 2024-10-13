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