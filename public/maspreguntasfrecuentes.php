<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas Frecuentes - Café Sabrosos</title>
    <link rel="stylesheet" href="/public/assets/css/maspreguntasfrecuentes.css">
    <link rel="stylesheet" href="/public/assets/css/nav.css">
    <link rel="stylesheet" href="/public/assets/css/footer.css">
</head>
<body>
<header>
    <?php include 'templates/nav.php'; ?>
</header>
    <main>
    <h1>Preguntas frecuentes</h1>
        <section class="faq">
            <div class="faq-item">
                <h2>1. Ingresé una dirección incorrecta</h2>
                <p>Te recomendamos cancelar el pedido rápidamente desde el botón “Cancelar pedido” que se encuentra en la pantalla de confirmación.</p>
            </div>
            <div class="faq-item">
                <h2>2. El local se encuentra cerrado</h2>
                <p>Si pediste por la app y al llegar al restaurante elegido estaba cerrado, comunícate con el Call Center (+34 912 345 678) para que podamos brindarte una solución personalizada.</p>
            </div>
            <div class="faq-item">
                <h2>3. He perdido algo en un restaurante, ¿cómo puedo recuperarlo?</h2>
                <p>Para ver si el restaurante ha encontrado el objeto perdido, por favor ponte en contacto con el restaurante directamente. (+34 912 345 678)</p>
            </div>
            <div class="faq-item">
                <h2>4. ¿Dónde está mi restaurante Café Sabrosos más cercano?</h2>
                <p>En el apartado de locales de la página podrás ver todos los locales de Café Sabrosos.</p>
            </div>
            <div class="faq-item">
                <h2>5. ¿Qué variedad de café se sirve en Café Sabrosos?</h2>
                <p>En Café Sabrosos solo se sirve café 100% arábica de la más alta calidad. La especie arábica supone un 60% de la producción mundial, crece mejor a altitudes elevadas, a partir de los 1200 m. de altitud. En estas altitudes las temperaturas son más bajas por la noche y más cálidas por el día lo que ayuda a mejorar el crecimiento del cafeto.</p>
            </div>
            <div class="faq-item">
                <h2>6. ¿Dónde se cultiva el café de Café Sabrosos?</h2>
                <p>Los árboles del café crecen en la zona ecuatorial entre los trópicos de Cáncer y Capricornio, conocido como “el cinturón del café”.

Hay 62 países productores de café en el mundo; Café Sabrosos compra más o menos a la mitad de ellos. Nuestros compradores de café viajan a través del Cinturón del Café para descubrir y comprar el mejor café verde que cada región tiene para ofrecer: cafés 100% Arábica de alta calidad que se seleccionan cuidadosamente para aquellos que definen características de sabor que distinguen sus orígenes.

Estas son las tres regiones principales de cultivo de café, cada una distinta en su paisaje, clima, y el sabor que imparte a los cafés cultivados y procesados allí.

Latinoamérica à Los cafés de América Latina tienden a tener sabores bien equilibrados a cacao o frutos secos, así como una intensa y fresca acidez.


África à Por lo general los cafés de esta región tienen notas florales y cítricas. Asia-Pacífico à Esta región es conocida por tener cafés con cuerpo intenso y sabores terrosos, herbales y especiado</p>
            </div>
            <div class="faq-item">
                <h2>7. Información nutricional de las bebidas</h2>
                <p>Si deseas obtener más información sobre las bebidas que disfrutas en Café Sabrosos, aquí tienes lo que buscabas. Esta carta contiene datos nutricionales sobre muchos de nuestros clásicos.


En Starbucks servimos un amplio abanico de bebidas deliciosas, y esta información te ayudará a estar seguro de que lo que elijas respete el estilo de vida que deseas llevar.</p>
            </div>
        </section>
    </main>
    <?php if (!isset($_COOKIE['cookie_preference'])) {
    include 'templates/cookies.php';
} ?>
<?php include 'templates/footer.php'; ?>
</body>
</html>