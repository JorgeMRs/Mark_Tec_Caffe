<?php
require '../src/db/db_connect.php';

$conn = getDbConnection();

$sql = "SELECT nombre, direccion, pais, ciudad, tel FROM sucursal";
$result = $conn->query($sql);

$locations = [];

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $locations[$row['pais']] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();
?>


<!DOCTYPE html>
<?php 

$pageTitle = 'Café Sabrosos - Locales';

$customCSS = [
    '/public/assets/css/stylelocal.css',
    '/public/assets/css/nav.css',
    '/public/assets/css/footer.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'
];

include 'templates/head.php' ?>

<body class="body-local">
    <header>
        <?php include 'templates/nav.php' ?>
    </header>

    <br>
    <main>
         
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
                text-decoration: none;
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
                <!-- Local en Francia -->
                <div class="local-card">
                    <div class="local-image" style="background-image: url('/public/assets/img/hamza-nouasria-P2mIRmNIIPQ-unsplash.jpg');">
                        <div class="label">Francia</div>
                    </div>
                    <div class="local-info">
                        <h2 class="local-title">Café Sabrosos París</h2>
                        <p class="local-address">Dirección: Boulevard Saint-Germain 56, Francia</p>
                        <p class="local-address">Teléfono: +33 1 2345 6789</p>
                        <p class="local-address">Capacidad: 18 personas</p>
                        <div class="local-hours">
                        <i class="fa-solid fa-clock" style="color: #1b0d0b; margin-right: 10px;"></i> <p>Lunes a Viernes: 7:00 - 19:00</p>
                        </div>
                        <a href="mesas.php?sucursal=4" class="btn-secondary">Hacer Reserva</a>
                    </div>
                </div>
                <!-- Local en Alemania -->
                <div class="local-card">
                    <div class="local-image" style="background-image: url('/public/assets/img/como-montar-cafeteria-.webp');">
                        <div class="label">Alemania</div>
                    </div>
                    <div class="local-info">
                        <h2 class="local-title">Café Sabrosos Berlin</h2>
                        <p class="local-address">Dirección: Kurfürstendamm 100, Alemania</p>
                        <p class="local-address">Teléfono: +49 30 12345678</p>
                        <p class="local-address">Capacidad: 18 personas</p>
                        <div class="local-hours">
                        <i class="fa-solid fa-clock" style="color: #1b0d0b; margin-right: 10px;"></i><p>Lunes a Sábado: 8:00 - 20:00</p>
                        </div>
                        <a href="mesas.php?sucursal=3" class="btn-secondary">Hacer Reserva</a>
                    </div>
                </div>
                <!-- Local en Portugal -->
                <div class="local-card">
                    <div class="local-image" style="background-image: url('/public/assets/img/kishore-v-tf7Y9kMhETg-unsplash.jpg');">
                        <div class="label">Portugal</div>
                    </div>
                    <div class="local-info">
                        <h2 class="local-title">Café Sabrosos Lisboa</h2>
                        <p class="local-address">Dirección: Rua de São Bento 123, Portugal</p>
                        <p class="local-address">Teléfono: +351 213 456 789</p>
                        <p class="local-address">Capacidad: 18 personas</p>
                        <div class="local-hours">
                        <i class="fa-solid fa-clock" style="color: #1b0d0b; margin-right: 10px;"></i><p>Lunes a Domingo: 7:00 - 22:00</p>
                        </div>
                        <a href="mesas.php?sucursal=1" class="btn-secondary">Hacer Reserva</a>
                    </div>
                </div>
                <div class="local-card">
                    <div class="local-image" style="background-image: url('/public/assets/img/madrid.jpg');">
                        <div class="label">España</div>
                    </div>
                    <div class="local-info">
                        <h2 class="local-title">Café Sabrosos Madrid</h2>
                        <p class="local-address">Dirección: Calle Gran Vía 45, España</p>
                        <p class="local-address">Teléfono: +34 912 345 678</p>
                        <p class="local-address">Capacidad: 18 personas</p>
                        <div class="local-hours">
                        <i class="fa-solid fa-clock" style="color: #1b0d0b; margin-right: 10px;"></i> <p>Lunes a Domingo: 7:00 - 22:00</p>
                        </div>
                        <a href="mesas.php?sucursal=2" class="btn-secondary">Hacer Reserva</a>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
        include 'templates/cookies.php';
    } ?>
    <?php include 'templates/footer.php'; ?>
</body>
<script src="/public/assets/js/updateCartCounter.js"></script>
</html>