-- MySQL dump 10.13  Distrib 8.0.39, for Linux (x86_64)
--
-- Host: localhost    Database: cafesabroso
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `carrito`
--

DROP TABLE IF EXISTS `carrito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrito` (
  `idCarrito` int NOT NULL AUTO_INCREMENT,
  `idCliente` int NOT NULL,
  `fechaCreacion` datetime NOT NULL,
  PRIMARY KEY (`idCarrito`),
  KEY `idx_cliente` (`idCliente`),
  CONSTRAINT `carrito_fk_cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito`
--

LOCK TABLES `carrito` WRITE;
/*!40000 ALTER TABLE `carrito` DISABLE KEYS */;
/*!40000 ALTER TABLE `carrito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carritodetalle`
--

DROP TABLE IF EXISTS `carritodetalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carritodetalle` (
  `idCarritoDetalle` int NOT NULL AUTO_INCREMENT,
  `idCarrito` int NOT NULL,
  `idProducto` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`idCarritoDetalle`),
  UNIQUE KEY `unique_carrito_producto` (`idCarrito`,`idProducto`),
  KEY `idx_carrito` (`idCarrito`),
  KEY `idx_producto` (`idProducto`),
  CONSTRAINT `carritoDetalle_fk_carrito` FOREIGN KEY (`idCarrito`) REFERENCES `carrito` (`idCarrito`),
  CONSTRAINT `carritoDetalle_fk_producto` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carritodetalle`
--

LOCK TABLES `carritodetalle` WRITE;
/*!40000 ALTER TABLE `carritodetalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `carritodetalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `idCategoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Cafés Especiales'),(2,'Cafés con Leche'),(3,'Cafés Fríos'),(4,'Pasteles y Postres'),(5,'Té'),(6,'Sandwiches y Bocadillos');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `idCliente` int NOT NULL AUTO_INCREMENT,
  `correo` varchar(255) NOT NULL,
  `contrasena` varchar(60) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fechaActualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `estadoActivacion` tinyint(1) DEFAULT '0',
  `tokenVerificacion` varchar(64) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idCliente`),
  UNIQUE KEY `correo_unique` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (31,'josesitovcf@gmail.com','$2y$10$/vsgn0lEpodhFwXuAGka1.hEK9XNidyvEcmjux4ImwcmB4ZRKlb2e','Jose','Sanchez','945345233','2024-08-15 22:36:49','2024-08-21 20:38:46',1,NULL,'2024-08-07','31_avatar.jpg'),(32,'andresdelgado050406@gmail.com','$2y$10$qCOc8XNofFM9WVE06vLKrOxUqBxwWf7x/YmGGMJP2fvZU6gNth97W','Andrés','Delgado','092111551','2024-08-15 23:42:17','2024-08-20 21:29:30',1,NULL,'2006-04-05','32_avatar.jpg'),(33,'root@gmail.com','$2y$10$CTxn2iXZHJml.KGcKu.XGuhfGQtyprz2p8C9ULLdMQZdZOKwyAYGi','root','root','','2024-08-16 01:14:59','2024-08-21 20:38:18',1,NULL,'2024-08-14','33_avatar.jpg');
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleado`
--

DROP TABLE IF EXISTS `empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado` (
  `idEmpleado` int NOT NULL AUTO_INCREMENT,
  `correo` varchar(255) NOT NULL,
  `contrasena` varchar(60) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `ci` varchar(13) NOT NULL,
  `idPuesto` int NOT NULL,
  `idSucursal` int NOT NULL,
  `fechaIngreso` date NOT NULL,
  `salario` decimal(10,2) NOT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  PRIMARY KEY (`idEmpleado`),
  UNIQUE KEY `correo_unique` (`correo`),
  KEY `idx_puesto` (`idPuesto`),
  KEY `idx_sucursal` (`idSucursal`),
  CONSTRAINT `empleado_fk_puesto` FOREIGN KEY (`idPuesto`) REFERENCES `puesto` (`idPuesto`),
  CONSTRAINT `empleado_fk_sucursal` FOREIGN KEY (`idSucursal`) REFERENCES `sucursal` (`idSucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado`
--

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
/*!40000 ALTER TABLE `empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mesa`
--

DROP TABLE IF EXISTS `mesa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mesa` (
  `idMesa` int NOT NULL AUTO_INCREMENT,
  `numero` int NOT NULL,
  `capacidad` int NOT NULL,
  `idSucursal` int NOT NULL,
  PRIMARY KEY (`idMesa`),
  KEY `mesa_fk_sucursal` (`idSucursal`),
  CONSTRAINT `mesa_fk_sucursal` FOREIGN KEY (`idSucursal`) REFERENCES `sucursal` (`idSucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mesa`
--

LOCK TABLES `mesa` WRITE;
/*!40000 ALTER TABLE `mesa` DISABLE KEYS */;
/*!40000 ALTER TABLE `mesa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido`
--

DROP TABLE IF EXISTS `pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido` (
  `idPedido` int NOT NULL AUTO_INCREMENT,
  `idEmpleado` int DEFAULT NULL,
  `idCliente` int NOT NULL,
  `idCarrito` int NOT NULL,
  `idSucursal` int NOT NULL,
  `estado` enum('Pendiente','En Preparación','Listo para Recoger','Completado','Cancelado') NOT NULL,
  `notas` text,
  `horaRecogida` time DEFAULT NULL,
  `metodoPago` enum('Efectivo','Tarjeta','Transferencia') NOT NULL,
  `tipoPedido` enum('En el local','Para llevar') NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fechaPedido` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaModificacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idPedido`),
  KEY `idx_carrito` (`idCarrito`),
  KEY `idx_cliente` (`idCliente`),
  KEY `idx_empleado` (`idEmpleado`),
  KEY `pedido_fk_sucursal` (`idSucursal`),
  CONSTRAINT `pedido_fk_carrito` FOREIGN KEY (`idCarrito`) REFERENCES `carrito` (`idCarrito`),
  CONSTRAINT `pedido_fk_cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`),
  CONSTRAINT `pedido_fk_empleado` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`),
  CONSTRAINT `pedido_fk_sucursal` FOREIGN KEY (`idSucursal`) REFERENCES `sucursal` (`idSucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidodetalle`
--

DROP TABLE IF EXISTS `pedidodetalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidodetalle` (
  `idDetallePedido` int NOT NULL AUTO_INCREMENT,
  `idPedido` int NOT NULL,
  `idProducto` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`idDetallePedido`),
  UNIQUE KEY `unique_pedido_producto` (`idPedido`,`idProducto`),
  KEY `idx_pedido` (`idPedido`),
  KEY `idx_producto` (`idProducto`),
  CONSTRAINT `pedidoDetalle_fk_pedido` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`idPedido`),
  CONSTRAINT `pedidoDetalle_fk_producto` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidodetalle`
--

LOCK TABLES `pedidodetalle` WRITE;
/*!40000 ALTER TABLE `pedidodetalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedidodetalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `idProducto` int NOT NULL AUTO_INCREMENT,
  `imagen` varchar(255) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `stock` int NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `idCategoria` int NOT NULL,
  PRIMARY KEY (`idProducto`),
  KEY `idx_categoria` (`idCategoria`),
  CONSTRAINT `producto_fk_categoria` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (15,'/public/assets/img/productos/Cafés Especiales/espresso.jpg','Espresso',50,'Café espresso doble shot con un sabor intenso y una crema rica. Ideal para los amantes del café fuerte.',3.50,1),(16,'/public/assets/img/productos/Cafés Especiales/americano.jpg','Café Americano',40,'Café negro estilo americano, con un sabor suave y equilibrado. Perfecto para disfrutar en cualquier momento del día.',2.50,1),(17,'/public/assets/img/productos/Cafés Especiales/mocha.jpg','Café Mocha',30,'Café con chocolate y leche, combinando el sabor intenso del café con la dulzura del chocolate. Un placer para el paladar.',4.00,1),(18,'/public/assets/img/productos/Cafés Especiales/latte.jpg','Café Latte',35,'Café con leche vaporizada, creando una bebida cremosa y suave. Ideal para aquellos que prefieren un café menos fuerte.',3.75,1),(19,'/public/assets/img/productos/Cafés Especiales/capuccino.jpg','Cappuccino',25,'Café con leche y espuma, ofreciendo una textura ligera y una mezcla perfecta de café y leche.',3.80,1),(20,'/public/assets/img/productos/Cafés Especiales/flat-white.jpg','Flat White',20,'Café con leche microespumada, proporcionando una bebida cremosa con una capa suave de espuma. Un clásico australiano.',3.90,1),(21,'/public/assets/img/productos/Cafés con Leche/latte-vainilla.jpg','Latte Vainilla',30,'Café con leche y jarabe de vainilla, creando una mezcla dulce y aromática. Perfecto para los amantes de sabores suaves.',4.20,2),(22,'/public/assets/img/productos/Cafés con Leche/latte-caramelo.jpg','Latte Caramelo',25,'Café con leche y jarabe de caramelo, ofreciendo una combinación rica y dulce de café y caramelo.',4.30,2),(23,'/public/assets/img/productos/Cafés con Leche/latte-avellana.jpg','Latte Avellana',20,'Café con leche y jarabe de avellana, proporcionando un sabor delicado y a nuez que complementa el café.',4.40,2),(24,'/public/assets/img/productos/Cafés con Leche/vienés.jpg','Café Vienés',15,'Café con crema batida, creando una bebida lujosa y suave con una capa generosa de crema.',4.50,2),(25,'/public/assets/img/productos/Cafés con Leche/cortado.jpg','Café Cortado',10,'Café con un toque de leche, ofreciendo una bebida más equilibrada entre el café y la leche. Ideal para una pausa rápida.',4.60,2),(26,'/public/assets/img/productos/Cafés con Leche/macchiato.jpg','Café Macchiato',5,'Café con una pequeña cantidad de leche, para aquellos que prefieren un café fuerte con solo un toque de leche.',4.70,2),(27,'/public/assets/img/productos/Cafés Fríos/cafe-con-hielo.jpg','Café con Hielo',50,'Café frío con hielo, una opción refrescante para los días calurosos. Mantiene todo el sabor del café en una bebida fría.',3.00,3),(28,'/public/assets/img/productos/Cafés Fríos/latte-con-hielo.jpg','Latte con Hielo',40,'Café con leche fría, ideal para disfrutar de un latte refrescante con hielo en los días de calor.',3.50,3),(29,'/public/assets/img/productos/Cafés Fríos/mocha-con-hielo.jpg','Mocha con Hielo',30,'Café con chocolate y leche frío, combinado con hielo para una bebida dulce y refrescante.',4.00,3),(30,'/public/assets/img/productos/Cafés Fríos/coldbrew.jpg','Cold Brew',20,'Café frío de extracción lenta, ofreciendo un sabor suave y menos ácido. Ideal para los que prefieren un café más suave.',3.75,3),(31,'/public/assets/img/productos/Cafés Fríos/frappuccino.jpg','Frappuccino',25,'Café con hielo y crema batida, creando una bebida cremosa y dulce, perfecta para un capricho refrescante.',4.50,3),(32,'/public/assets/img/productos/Cafés Fríos/affogato.jpg','Affogato',15,'Café con helado de vainilla, combinando el sabor intenso del café con la dulzura del helado.',4.75,3),(33,'/public/assets/img/productos/Pasteles y Postres/cheesecake.jpg','Cheesecake',10,'Pastel de queso con base de galleta, ofreciendo una textura cremosa y un sabor dulce. Perfecto para los amantes de los postres.',5.00,4),(34,'/public/assets/img/productos/Pasteles y Postres/brownie.jpg','Brownie',15,'Pastel de chocolate denso, con un sabor rico y una textura húmeda. Ideal para quienes buscan un capricho de chocolate.',3.50,4),(35,'/public/assets/img/productos/Pasteles y Postres/tarta-de-manzana.jpg','Tarta de Manzana',20,'Pastel de manzana con canela, ofreciendo una mezcla cálida y especiada de manzana y canela. Un clásico reconfortante.',4.00,4),(36,'/public/assets/img/productos/Pasteles y Postres/muffin-de-arandano.jpg','Muffin de Arándanos',25,'Muffin con arándanos frescos, combinando el sabor ácido de los arándanos con una textura suave y esponjosa.',2.75,4),(37,'/public/assets/img/productos/Pasteles y Postres/croissant.jpg','Croissant',30,'Croissant de mantequilla, con una textura ligera y crujiente por fuera y suave por dentro. Perfecto para el desayuno.',2.50,4),(38,'/public/assets/img/productos/Pasteles y Postres/macarons.jpg','Macarons',35,'Galletas francesas de almendra, con una textura crujiente por fuera y suave por dentro, disponibles en varios sabores.',3.00,4),(39,'/public/assets/img/productos/te/te-verde.jpg','Té Verde',40,'Té verde orgánico, con un sabor suave y ligeramente herbáceo. Ideal para una opción de té ligera y saludable.',2.50,5),(40,'/public/assets/img/productos/te/te-negro.jpg','Té Negro',35,'Té negro clásico, con un sabor robusto y ligeramente astringente. Perfecto para comenzar el día con energía.',2.75,5),(41,'/public/assets/img/productos/te/te-de-manzanilla.jpg','Té de Manzanilla',30,'Té de manzanilla relajante, con un sabor suave y floral. Ideal para relajarse y promover el bienestar.',2.50,5),(42,'/public/assets/img/productos/te/te-de-menta.jpg','Té de Menta',25,'Té de menta refrescante, ofreciendo un sabor fresco y vigorizante. Perfecto para revitalizarse durante el día.',2.75,5),(43,'/public/assets/img/productos/te/te-chai.jpg','Té Chai',20,'Té chai con especias, combinando té negro con una mezcla aromática de especias. Ideal para quienes disfrutan de sabores intensos.',3.00,5),(44,'/public/assets/img/productos/te/te-oolong.jpg','Té Oolong',15,'Té oolong semifermentado, con un sabor único que mezcla notas de té verde y té negro. Perfecto para una experiencia de té equilibrada.',3.25,5),(45,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-de-pollo.jpg','Sandwich de Pollo',20,'Sandwich de pollo con mayonesa, ofreciendo un sabor jugoso y cremoso con pollo fresco. Ideal para una comida rápida y satisfactoria.',4.50,6),(46,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-de-jamon-y-queso.jpg','Sandwich de Jamón y Queso',25,'Sandwich clásico de jamón y queso, con una combinación simple pero deliciosa de jamón y queso en pan fresco.',4.00,6),(47,'/public/assets/img/productos/Sandwich y Bocadillos/bocadillo-de-atun.jpg','Bocadillo de Atún',30,'Bocadillo de atún con lechuga, ofreciendo una mezcla fresca y ligera de atún con lechuga crujiente en pan.',4.25,6),(48,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-club.jpg','Sandwich Club',35,'Sándwich con pavo, tocino, lechuga y tomate, creando una combinación sabrosa y abundante con capas de ingredientes frescos.',4.75,6),(49,'/public/assets/img/productos/Sandwich y Bocadillos/bagel-con-salmon.jpg','Bagel con Salmón',15,'Bagel con salmón ahumado y queso crema, ofreciendo una combinación lujosa de salmón y queso en un pan bagel recién horneado.',5.00,6),(50,'/public/assets/img/productos/Sandwich y Bocadillos/wrap-de-pavo.jpg','Wrap de Pavo',10,'Wrap de pavo con aguacate, proporcionando una opción ligera y saludable con pavo tierno y aguacate cremoso envuelto en una tortilla.',4.50,6);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `puesto`
--

DROP TABLE IF EXISTS `puesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `puesto` (
  `idPuesto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  PRIMARY KEY (`idPuesto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `puesto`
--

LOCK TABLES `puesto` WRITE;
/*!40000 ALTER TABLE `puesto` DISABLE KEYS */;
/*!40000 ALTER TABLE `puesto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reserva`
--

DROP TABLE IF EXISTS `reserva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reserva` (
  `idReserva` int NOT NULL AUTO_INCREMENT,
  `fechaReserva` datetime NOT NULL,
  `idCliente` int NOT NULL,
  `idMesa` int NOT NULL,
  `estado` enum('reservado','disponible') NOT NULL,
  `idEmpleado` int NOT NULL,
  PRIMARY KEY (`idReserva`),
  KEY `idx_cliente` (`idCliente`),
  KEY `idx_empleado` (`idEmpleado`),
  KEY `idx_mesa` (`idMesa`),
  CONSTRAINT `reserva_fk_cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`),
  CONSTRAINT `reserva_fk_empleado` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`),
  CONSTRAINT `reserva_fk_mesa` FOREIGN KEY (`idMesa`) REFERENCES `mesa` (`idMesa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserva`
--

LOCK TABLES `reserva` WRITE;
/*!40000 ALTER TABLE `reserva` DISABLE KEYS */;
/*!40000 ALTER TABLE `reserva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retroalimentacion`
--

DROP TABLE IF EXISTS `retroalimentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `retroalimentacion` (
  `idRetroalimentacion` int NOT NULL AUTO_INCREMENT,
  `idCliente` int NOT NULL,
  `nivelSatisfaccion` enum('Muy bajo','Bajo','Medio','Alto','Muy alto') NOT NULL,
  `comentario` text,
  PRIMARY KEY (`idRetroalimentacion`),
  KEY `idx_cliente` (`idCliente`),
  CONSTRAINT `retroalimentacion_fk_cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retroalimentacion`
--

LOCK TABLES `retroalimentacion` WRITE;
/*!40000 ALTER TABLE `retroalimentacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `retroalimentacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sucursal`
--

DROP TABLE IF EXISTS `sucursal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sucursal` (
  `idSucursal` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `tel` varchar(15) NOT NULL,
  PRIMARY KEY (`idSucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sucursal`
--

LOCK TABLES `sucursal` WRITE;
/*!40000 ALTER TABLE `sucursal` DISABLE KEYS */;
/*!40000 ALTER TABLE `sucursal` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-23 20:55:28
