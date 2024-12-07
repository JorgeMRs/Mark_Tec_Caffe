-- MySQL dump 10.13  Distrib 8.0.40, for Linux (x86_64)
--
-- Host: localhost    Database: cafesabrosos
-- ------------------------------------------------------
-- Server version	8.0.40

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
-- Table structure for table `cancelacionpedido`
--

DROP TABLE IF EXISTS `cancelacionpedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cancelacionpedido` (
  `idCancelacion` int NOT NULL AUTO_INCREMENT,
  `idPedido` int NOT NULL,
  `idEmpleado` int DEFAULT NULL,
  `idCliente` int DEFAULT NULL,
  `fechaCancelacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notas` text,
  `tipoCancelacion` enum('Empleado','Cliente') NOT NULL,
  PRIMARY KEY (`idCancelacion`),
  KEY `fk_cancelacion_empleado` (`idEmpleado`),
  KEY `fk_cancelacion_cliente` (`idCliente`),
  KEY `fk_cancelacion_pedido` (`idPedido`),
  CONSTRAINT `fk_cancelacion_cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`),
  CONSTRAINT `fk_cancelacion_empleado` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`),
  CONSTRAINT `fk_cancelacion_pedido` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`idPedido`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cancelacionpedido`
--

LOCK TABLES `cancelacionpedido` WRITE;
/*!40000 ALTER TABLE `cancelacionpedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `cancelacionpedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cancelacionreserva`
--

DROP TABLE IF EXISTS `cancelacionreserva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cancelacionreserva` (
  `idCancelacion` int NOT NULL AUTO_INCREMENT,
  `idReserva` int NOT NULL,
  `idEmpleado` int DEFAULT NULL,
  `notas` text NOT NULL,
  `fechaCancelacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idCancelacion`),
  KEY `fk_cancelacionreserva_reserva` (`idReserva`),
  KEY `fk_cancelacionreserva_empleado` (`idEmpleado`),
  CONSTRAINT `fk_cancelacionreserva_empleado` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`),
  CONSTRAINT `fk_cancelacionreserva_reserva` FOREIGN KEY (`idReserva`) REFERENCES `reserva` (`idReserva`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cancelacionreserva`
--

LOCK TABLES `cancelacionreserva` WRITE;
/*!40000 ALTER TABLE `cancelacionreserva` DISABLE KEYS */;
/*!40000 ALTER TABLE `cancelacionreserva` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito`
--

LOCK TABLES `carrito` WRITE;
/*!40000 ALTER TABLE `carrito` DISABLE KEYS */;
INSERT INTO `carrito` VALUES (1,53,'2024-09-15 19:47:33'),(3,41,'2024-09-16 00:53:59'),(4,458,'2024-09-24 17:48:30'),(5,35,'2024-09-24 17:54:27'),(6,461,'2024-10-08 18:44:09'),(7,460,'2024-10-15 23:19:55'),(8,460,'2024-10-15 23:19:55'),(9,53,'2024-09-15 19:47:33'),(10,53,'2024-09-15 19:47:33'),(11,53,'2024-09-15 19:47:33'),(12,53,'2024-09-15 19:47:33'),(13,53,'2024-09-15 19:47:33'),(14,53,'2024-09-15 19:47:33'),(15,53,'2024-09-15 19:47:33'),(16,53,'2024-09-15 19:47:33'),(17,53,'2024-09-15 19:47:33'),(18,53,'2024-09-15 19:47:33'),(19,53,'2024-09-15 19:47:33'),(20,53,'2024-09-15 19:47:33');
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
  `cantidad` int NOT NULL DEFAULT '0',
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`idCarritoDetalle`),
  UNIQUE KEY `unique_carrito_producto` (`idCarrito`,`idProducto`),
  KEY `idx_carrito` (`idCarrito`),
  KEY `idx_producto` (`idProducto`),
  CONSTRAINT `carritoDetalle_fk_carrito` FOREIGN KEY (`idCarrito`) REFERENCES `carrito` (`idCarrito`),
  CONSTRAINT `carritoDetalle_fk_producto` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carritodetalle`
--

LOCK TABLES `carritodetalle` WRITE;
/*!40000 ALTER TABLE `carritodetalle` DISABLE KEYS */;
INSERT INTO `carritodetalle` VALUES (5,4,21,1,4.20),(30,7,19,5,3.80),(31,7,16,1,2.50),(34,1,16,9,2.50);
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
  `imagen` varchar(255) DEFAULT NULL,
  `estadoActivacion` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Café Especiales','/public/assets/img/categorias/cafe-especiales.jpg',1),(2,'Café con Leche','/public/assets/img/categorias/cafe-con-leche.jpg',1),(3,'Café Frío','/public/assets/img/categorias/cafe-frio.jpg',1),(4,'Pastel y Tortas','/public/assets/img/categorias/pastel-y-tortas.jpg',1),(5,'Tipos de Té','/public/assets/img/categorias/tipos-de-te.jpg',1),(6,'Sandwich y Bocadillos','/public/assets/img/categorias/bocadillo.jpg',1);
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
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fechaActualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `estadoActivacion` tinyint(1) DEFAULT '0',
  `tokenVerificacion` varchar(64) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idCliente`),
  UNIQUE KEY `correo_unique` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=469 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (1,'user1@example.com','password1','John','Doe','1234567890','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token1','1990-01-01','avatar1.png',NULL),(2,'user2@example.com','password2','Jane','Smith','1234567891','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token2','1991-02-02','avatar2.png',NULL),(3,'user3@example.com','password3','Alice','Johnson','1234567892','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token3','1992-03-03','avatar3.png',NULL),(4,'user4@example.com','password4','Bob','Brown','1234567893','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token4','1993-04-04','avatar4.png',NULL),(5,'user5@example.com','password5','Charlie','Davis','1234567894','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token5','1994-05-05','avatar5.png',NULL),(6,'user6@example.com','password6','David','Wilson','1234567895','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token6','1995-06-06','avatar6.png',NULL),(7,'user7@example.com','password7','Eve','Garcia','1234567896','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token7','1996-07-07','avatar7.png',NULL),(8,'user8@example.com','password8','Frank','Martinez','1234567897','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token8','1997-08-08','avatar8.png',NULL),(9,'user9@example.com','password9','Grace','Hernandez','1234567898','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token9','1998-09-09','avatar9.png',NULL),(10,'user10@example.com','password10','Hank','Lopez','1234567899','2024-09-13 04:50:59','2024-11-01 01:51:24',1,'token10','1999-10-10','avatar10.png',NULL),(11,'user11@example.com','password11','Ivy','Gonzalez','1234567800','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token11','2000-11-11','avatar11.png',NULL),(12,'user12@example.com','password12','Jack','Perez','1234567801','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token12','2001-12-12','avatar12.png',NULL),(13,'user13@example.com','password13','Kathy','Wilson','1234567802','2024-09-13 04:50:59','2024-11-01 01:51:24',1,'token13','2002-01-13','avatar13.png',NULL),(14,'user14@example.com','password14','Leo','Anderson','1234567803','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token14','2003-02-14','avatar14.png',NULL),(15,'user15@example.com','password15','Mia','Thomas','1234567804','2024-09-13 04:50:59','2024-11-01 01:51:24',1,'token15','2004-03-15','avatar15.png',NULL),(16,'user16@example.com','password16','Nina','Taylor','1234567805','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token16','2005-04-16','avatar16.png',NULL),(17,'user17@example.com','password17','Oscar','Moore','1234567806','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token17','2006-05-17','avatar17.png',NULL),(18,'user18@example.com','password18','Paul','Jackson','1234567807','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token18','2007-06-18','avatar18.png',NULL),(19,'user19@example.com','password19','Quinn','Martin','1234567808','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token19','2008-07-19','avatar19.png',NULL),(20,'user20@example.com','password20','Rita','Lee','1234567809','2024-09-13 04:50:59','2024-11-01 01:51:23',1,'token20','2009-08-20','avatar20.png',NULL),(33,'root@gmail.com','$2y$10$CTxn2iXZHJml.KGcKu.XGuhfGQtyprz2p8C9ULLdMQZdZOKwyAYGi','root','root','','2024-08-16 01:14:59','2024-08-21 20:38:18',1,NULL,'2024-08-14','33_avatar.jpg',NULL),(35,'lucianobritos154@gmail.com','$2y$10$M/gazcvT7gcnS73BvzRTb.SbLCCnCjLJreFXdo2r.VTVLb3bg7MDi','Luciano',NULL,NULL,'2024-08-30 17:31:22','2024-10-31 05:23:18',1,NULL,NULL,'35_avatar.png',NULL),(41,'andresdelgado050406@gmail.com','$2y$10$X3Y8InM2XTUnDWhjTWqhRe0iJ.W2wOnQM71LPHhzRYcFcSki8jEdC','Eduardo','Delgado','','2024-09-01 19:42:10','2024-10-28 21:41:01',1,NULL,'2006-04-05','41_avatar.png',NULL),(43,'josesitovcf3@gmail.com','$2y$10$yrKOUzNMHNb0MLVbQa/2Iuf0m5W5WTdY0kRaO6OQ2l4mEhKOvRAMG',NULL,NULL,NULL,'2024-09-03 20:15:20','2024-09-09 16:25:51',1,NULL,NULL,'43_avatar.jpg',NULL),(46,'Fabriciodeleon007@gmail.com','$2y$10$9kQE7g6Q1toPA8yrI5nq4.sa8xnOf/rWcijatd3VrBQMe9B.10PqK',NULL,NULL,NULL,'2024-09-07 14:55:11','2024-09-08 07:25:00',1,NULL,NULL,'46_avatar.jpg',NULL),(53,'josesitovcf@gmail.com','$2y$10$qppRQ30yqsxJhPhjkVU3DuivZn..WM2jgnISdq.zieLd51.LUTiHK','José','Sanchez','099761830','2024-09-10 01:56:20','2024-11-01 04:07:01',1,NULL,'2003-11-25','53_avatar.jpg',NULL),(458,'elpepeinsanowaza666@gmail.com','$2y$10$G9faoVCHizw9tJM7JXOYcO8lchiUXO9d707sCjGUK5h5pIw7khpO.',NULL,NULL,NULL,'2024-09-24 17:47:22','2024-09-24 17:47:44',1,NULL,NULL,NULL,NULL),(460,'vaexco@gmail.com','$2y$10$zZYYfuEdNbPQzhsAvGmEoun9VTDIcfIIBNxjwujAkMxv2R62KPC0m',NULL,NULL,NULL,'2024-10-02 00:36:43','2024-10-31 19:51:21',1,NULL,NULL,'460_avatar.png',NULL),(461,'vaexco1@gmail.com','$2y$10$yLJl9lCh1Y0WgwJRK/vkROskI9ds./ZOwGB/wKU4piFntrpq69qhq','Alexis','Bentancor','0999999','2024-10-03 14:06:48','2024-10-08 18:46:15',1,NULL,NULL,'461_avatar.jpg',NULL),(465,'marktecs.a.soficial@gmail.com','$2y$10$g2gFrOpTh.DKwivRXmZ24udRtwdBE8hMCfXBpuPdSg8qjiiBg42Ha','José Sanchez',NULL,NULL,'2024-10-08 23:39:50','2024-10-08 23:39:50',1,NULL,NULL,'avatar_465.jpg','AIu0T0oHbkhAvE87Y0tcS8iYUcQ2'),(466,'luchotetas2007@gmail.com','$2y$10$kAV86HTF7Mz881lrNvAupel0tVr5MyWFvdmhmpQL4B2C3sFgwGGLW',NULL,NULL,NULL,'2024-10-14 18:24:54','2024-10-28 22:43:01',1,NULL,NULL,NULL,NULL),(467,'cuentaffcd@gmail.com','$2y$10$7fXqpMxsJF.efqV.Fum73.GLLIr5/G2F1zisGxrTZV35W6G/KMmPK',NULL,NULL,NULL,'2024-10-28 22:40:14','2024-10-28 22:40:14',0,'e143972f117412817ece75f2a89ac3ea',NULL,NULL,NULL),(468,'marktecs.a.s@gmail.com','$2y$10$ZfJScndi0eKETpCLnLPpwu6Ivi5nlWaXdmnPSeuPBqGviqZjNj5Ga',NULL,NULL,NULL,'2024-10-29 23:10:20','2024-10-29 23:10:20',0,'3d7e141085145b43d43adc9f2c637885',NULL,NULL,NULL);
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
  `idPuesto` int NOT NULL,
  `idSucursal` int NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contrasena` varchar(60) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `ci` varchar(13) NOT NULL,
  `fechaIngreso` date NOT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `estadoActivacion` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idEmpleado`),
  UNIQUE KEY `correo_unique` (`correo`),
  KEY `idx_puesto` (`idPuesto`),
  KEY `idx_sucursal` (`idSucursal`),
  CONSTRAINT `empleado_fk_puesto` FOREIGN KEY (`idPuesto`) REFERENCES `puesto` (`idPuesto`),
  CONSTRAINT `empleado_fk_sucursal` FOREIGN KEY (`idSucursal`) REFERENCES `sucursal` (`idSucursal`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado`
--

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
INSERT INTO `empleado` VALUES (1,1,2,'admin@cafesabrosos.com','$2a$12$yA8LCWRRsnSwJd9t/qzB6OvRZ4BTd2yxGjW5NAXvG9HC8bAVZeB0q','José','Sanchez','12345678901','2024-01-01','+351 213 456 790','1980-05-15',1),(2,2,1,'gerente1@cafesabrosos.com','$2a$12$qKmsJDHg7/vRTqU2cKM6wObjrxpaY68kKLG6CVqgVqg/7DabJpWIa','Carlos','Ríos','23456789012','2024-01-01','+351 213 456 791','1985-07-22',1),(3,3,1,'chef1@cafesabrosos.com','$2a$12$WrKTrxWGQki52obn0rbXxu7VyGfRXuak5NCDHldSDBVZ4TEyP1S.2','Ana','López','34567890123','2024-01-01','+351 213 456 792','1990-10-10',1),(4,4,1,'mozo1@cafesabrosos.com','$2a$12$xN30oO55V/LwOrv/lw2Wj.TPzclXUl8yQ40EBDbKuDFoLrIPMBLwm','Sofía','Martín','45678901234','2024-01-01','+351 213 456 793','1995-03-20',1),(5,2,2,'gerente2@cafesabrosos.com','$2a$12$qKmsJDHg7/vRTqU2cKM6wObjrxpaY68kKLG6CVqgVqg/7DabJpWIa','Andrés','Fernández','67890123456','2024-01-01','+34 912 345 681','1985-07-22',1),(6,3,2,'chef2@cafesabrosos.com','$2a$12$WrKTrxWGQki52obn0rbXxu7VyGfRXuak5NCDHldSDBVZ4TEyP1S.2','Eva','Rodríguez','78901234567','2024-01-01','+34 912 345 682','1990-10-10',1),(7,4,2,'mozo2@cafesabrosos.com','$2a$12$E7aCBVsEB0lhnPqDeTpzee7O8kxr7zKf5MDZTwvRs8EBu08lGfBkO','Isabel','García','89012345678','2024-01-01','+34 912 345 683','1995-03-20',1),(8,2,3,'gerente3@cafesabrosos.com','$2a$12$qKmsJDHg7/vRTqU2cKM6wObjrxpaY68kKLG6CVqgVqg/7DabJpWIa','Martin','Müller','01234567890','2024-01-01','+49 30 12345681','1985-07-22',1),(9,3,3,'chef3@cafesabrosos.com','$2a$12$WrKTrxWGQki52obn0rbXxu7VyGfRXuak5NCDHldSDBVZ4TEyP1S.2','Clara','Bauer','12345678901','2024-01-01','+49 30 12345682','1990-10-10',1),(10,4,3,'mozo3@cafesabrosos.com','$2a$12$E7aCBVsEB0lhnPqDeTpzee7O8kxr7zKf5MDZTwvRs8EBu08lGfBkO','Laura','Weber','23456789012','2024-01-01','+49 30 12345683','1995-03-20',1),(11,2,4,'gerente4@cafesabrosos.com','$2a$12$qKmsJDHg7/vRTqU2cKM6wObjrxpaY68kKLG6CVqgVqg/7DabJpWIa','Jean','Dupont','45678901234','2024-01-01','+33 1 2345 6781','1985-07-22',1),(12,3,4,'chef4@cafesabrosos.com','$2a$12$WrKTrxWGQki52obn0rbXxu7VyGfRXuak5NCDHldSDBVZ4TEyP1S.2','Marie','Lefevre','56789012345','2024-01-01','+33 1 2345 6782','1990-10-10',1),(13,4,4,'mozo4@cafesabrosos.com','$2a$12$QzLIVw0vstjmO99lyLsdTeEaSm7GzL7yWZ.eZVMRUa6x5iEj1eNwi','Lucie','Bernard','67890123456','2024-01-01','+33 1 2345 6783','1995-03-20',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mesa`
--

LOCK TABLES `mesa` WRITE;
/*!40000 ALTER TABLE `mesa` DISABLE KEYS */;
INSERT INTO `mesa` VALUES (1,1,4,1),(2,2,2,1),(3,3,6,1),(4,4,4,1),(5,5,2,1),(6,1,4,2),(7,2,2,2),(8,3,6,2),(9,4,4,2),(10,5,2,2),(11,1,4,3),(12,2,2,3),(13,3,6,3),(14,4,4,3),(15,5,2,3),(16,1,4,4),(17,2,2,4),(18,3,6,4),(19,4,4,4),(20,5,2,4);
/*!40000 ALTER TABLE `mesa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `numeropedidosucursal`
--

DROP TABLE IF EXISTS `numeropedidosucursal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `numeropedidosucursal` (
  `idSucursal` int NOT NULL,
  `numeroPedido` int NOT NULL,
  PRIMARY KEY (`idSucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `numeropedidosucursal`
--

LOCK TABLES `numeropedidosucursal` WRITE;
/*!40000 ALTER TABLE `numeropedidosucursal` DISABLE KEYS */;
INSERT INTO `numeropedidosucursal` VALUES (1,14),(2,6),(3,2),(4,2);
/*!40000 ALTER TABLE `numeropedidosucursal` ENABLE KEYS */;
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
  `idCliente` int DEFAULT NULL,
  `idCarrito` int DEFAULT NULL,
  `idMesa` int DEFAULT NULL,
  `idSucursal` int DEFAULT NULL,
  `estado` enum('Pendiente','En Preparación','Listo para Recoger','Completado','Cancelado') NOT NULL,
  `notas` text,
  `horaRecogida` time DEFAULT NULL,
  `metodoPago` enum('Efectivo','Tarjeta','Transferencia') NOT NULL,
  `tipoPedido` enum('En el local','Para llevar') NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fechaPedido` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaModificacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `codigoVerificacion` varchar(12) DEFAULT NULL,
  `numeroPedidoCliente` int DEFAULT NULL,
  `numeroPedidoSucursal` int DEFAULT NULL,
  PRIMARY KEY (`idPedido`),
  UNIQUE KEY `codigoVerificacion_unique` (`codigoVerificacion`),
  KEY `pedido_fk_mesa` (`idMesa`),
  KEY `pedido_fk_sucursal` (`idSucursal`),
  KEY `idx_carrito` (`idCarrito`),
  KEY `idx_cliente` (`idCliente`),
  KEY `idx_empleado` (`idEmpleado`),
  CONSTRAINT `pedido_fk_carrito` FOREIGN KEY (`idCarrito`) REFERENCES `carrito` (`idCarrito`),
  CONSTRAINT `pedido_fk_cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`),
  CONSTRAINT `pedido_fk_empleado` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`),
  CONSTRAINT `pedido_fk_mesa` FOREIGN KEY (`idMesa`) REFERENCES `mesa` (`idMesa`),
  CONSTRAINT `pedido_fk_sucursal` FOREIGN KEY (`idSucursal`) REFERENCES `sucursal` (`idSucursal`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (1,1,1,5,1,1,'Completado','Sin salsa extra','12:30:00','Efectivo','En el local',150.50,'2024-11-01 02:00:08','2024-11-01 02:25:24','ABC123456789',1,101),(2,2,2,6,2,1,'En Preparación','Con queso extra','13:00:00','Tarjeta','Para llevar',200.75,'2024-11-01 02:00:08','2024-11-01 02:00:08','DEF123456789',2,102),(3,3,3,7,1,2,'Listo para Recoger','Sin gluten','14:00:00','Transferencia','Para llevar',125.00,'2024-11-01 02:00:08','2024-11-01 02:00:08','GHI123456789',3,103),(4,4,4,8,3,2,'Completado','Sin lactosa','15:00:00','Efectivo','En el local',75.50,'2024-11-01 02:00:08','2024-11-01 02:00:08','JKL123456789',4,104),(5,5,5,9,2,1,'Cancelado','Extra salsa','16:00:00','Tarjeta','Para llevar',180.20,'2024-11-01 02:00:08','2024-11-01 02:00:08','MNO123456789',5,105),(6,1,6,10,4,2,'Pendiente','Sin azúcar','17:30:00','Efectivo','En el local',90.10,'2024-11-01 02:00:08','2024-11-01 02:00:08','PQR123456789',6,106),(7,2,7,11,3,1,'En Preparación','Extra queso','18:15:00','Tarjeta','Para llevar',110.80,'2024-11-01 02:00:08','2024-11-01 02:00:08','STU123456789',7,107),(8,3,8,12,2,2,'Listo para Recoger','Con limón','19:20:00','Transferencia','Para llevar',45.25,'2024-11-01 02:00:08','2024-11-01 02:00:08','VWX123456789',8,108),(9,4,9,13,1,1,'Completado','Sin cebolla','12:00:00','Efectivo','En el local',60.90,'2024-11-01 02:00:08','2024-11-01 02:00:08','YZA123456789',9,109),(10,5,10,14,3,2,'Cancelado','Sin tomate','13:45:00','Tarjeta','Para llevar',220.40,'2024-11-01 02:00:08','2024-11-01 02:00:08','BCD123456789',10,110),(11,1,11,15,4,1,'Pendiente','Sin picante','11:30:00','Efectivo','En el local',170.30,'2024-11-01 02:00:08','2024-11-01 02:00:08','EFG123456789',11,111),(12,2,12,16,3,2,'En Preparación','Sin huevo','14:15:00','Tarjeta','Para llevar',55.60,'2024-11-01 02:00:08','2024-11-01 02:00:08','HIJ123456789',12,112),(13,3,13,17,2,1,'Listo para Recoger','Extra servilletas','15:45:00','Transferencia','Para llevar',120.10,'2024-11-01 02:00:08','2024-11-01 02:00:08','KLM123456789',13,113),(14,4,14,18,1,2,'Completado','Sin mantequilla','16:30:00','Efectivo','En el local',85.40,'2024-11-01 02:00:08','2024-11-01 02:00:08','NOP123456789',14,114),(15,5,15,19,4,1,'Cancelado','Sin mostaza','17:00:00','Tarjeta','Para llevar',195.00,'2024-11-01 02:00:08','2024-11-01 02:00:08','QRS123456789',15,115),(16,1,16,20,3,2,'Pendiente','Con cebolla extra','18:30:00','Efectivo','En el local',210.75,'2024-11-01 02:00:08','2024-11-01 02:00:08','TUV123456789',16,116),(17,2,17,5,2,1,'En Preparación','Sin ajo','19:00:00','Tarjeta','Para llevar',30.25,'2024-11-01 02:00:08','2024-11-01 02:00:08','WXY123456789',17,117),(18,3,18,6,4,2,'Listo para Recoger','Con ajo extra','20:30:00','Transferencia','Para llevar',95.15,'2024-11-01 02:00:08','2024-11-01 02:00:08','ZAB123456789',18,118),(19,4,19,7,1,1,'Completado','Sin mayonesa','10:45:00','Efectivo','En el local',130.20,'2024-11-01 02:00:08','2024-11-01 02:00:08','CDE123456789',19,119),(20,5,20,8,3,2,'Cancelado','Sin pepino','11:15:00','Tarjeta','Para llevar',160.50,'2024-11-01 02:00:08','2024-11-01 02:00:08','FGH123456789',20,120),(21,NULL,41,3,NULL,2,'Pendiente','sin azucar',NULL,'Tarjeta','En el local',4.20,'2024-11-01 03:06:57','2024-11-01 03:06:57','PEDIDO4079bf',1,5),(22,NULL,41,3,NULL,2,'Pendiente','sin azucar',NULL,'Tarjeta','En el local',4.20,'2024-11-01 03:08:16','2024-11-01 03:08:16','PEDIDOe40387',2,6),(23,NULL,41,3,NULL,1,'Pendiente','',NULL,'Tarjeta','En el local',4.20,'2024-11-01 03:18:23','2024-11-01 03:18:23','PEDIDO3005d5',3,14);
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
  `cantidad` int NOT NULL DEFAULT '0',
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`idDetallePedido`),
  UNIQUE KEY `unique_pedido_producto` (`idPedido`,`idProducto`),
  KEY `idx_pedido` (`idPedido`),
  KEY `idx_producto` (`idProducto`),
  CONSTRAINT `pedidoDetalle_fk_pedido` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`idPedido`),
  CONSTRAINT `pedidoDetalle_fk_producto` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidodetalle`
--

LOCK TABLES `pedidodetalle` WRITE;
/*!40000 ALTER TABLE `pedidodetalle` DISABLE KEYS */;
INSERT INTO `pedidodetalle` VALUES (1,1,15,2,10.00),(2,1,16,1,8.50),(3,1,17,3,12.00),(4,1,18,8,7.25),(5,1,19,2,5.75),(6,1,20,4,6.50),(7,2,21,1,15.00),(8,2,22,2,11.00),(9,2,23,1,9.50),(10,2,24,3,20.00),(11,2,25,1,13.50),(12,2,26,1,17.00),(13,3,27,1,14.00),(14,3,28,5,6.00),(15,3,29,2,9.00),(16,3,30,1,10.50),(17,3,31,2,8.25),(18,3,32,3,12.50),(19,4,33,1,18.00),(20,4,34,4,5.50),(21,4,35,2,7.75),(22,4,36,1,11.25),(23,4,37,2,6.50),(24,4,38,3,4.75),(25,5,39,1,19.00),(26,5,40,2,8.00),(27,5,41,3,5.25),(28,5,42,1,9.00),(29,5,43,2,11.50),(30,5,44,1,13.00),(31,6,45,1,20.00),(32,6,46,3,7.50),(33,6,47,2,12.00),(34,6,48,1,14.50),(35,6,49,4,9.00),(36,6,50,2,8.00),(37,7,15,2,10.50),(38,7,16,1,8.75),(39,8,17,1,12.50),(40,8,18,3,11.00),(41,9,19,2,6.50),(42,9,20,4,5.75),(43,10,21,1,15.50),(44,10,22,2,11.50),(45,10,23,3,7.25),(46,11,24,1,9.00),(47,11,25,2,4.50),(48,11,26,1,6.00),(49,12,27,2,10.50),(50,12,28,1,11.75),(51,12,29,1,13.25),(52,13,30,3,5.00),(53,13,31,1,10.00),(54,13,32,2,7.50),(55,14,33,1,12.00),(56,14,34,2,5.50),(57,15,35,1,4.25),(58,15,36,1,8.00),(59,16,37,2,3.50),(60,16,38,3,2.25),(61,17,39,1,10.75),(62,17,40,1,15.25),(63,18,41,1,8.00),(64,19,42,1,12.50),(65,20,43,1,5.00),(66,20,44,1,8.00),(68,8,19,1,12.50),(69,8,20,1,12.50),(70,8,22,1,12.50),(71,21,15,1,3.50),(72,22,15,1,3.50),(73,23,15,1,3.50);
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
  `stock` int NOT NULL DEFAULT '0',
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `idCategoria` int NOT NULL,
  `estadoActivacion` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idProducto`),
  KEY `idx_categoria` (`idCategoria`),
  CONSTRAINT `producto_fk_categoria` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (15,'/public/assets/img/productos/Cafés Especiales/espresso.jpg','Espresso223',10,'Café espresso doble shot con un sabor intenso y una crema rica. Ideal para los amantes del café fuerte.',3.50,1,1),(16,'/public/assets/img/productos/Cafés Especiales/americano.jpg','Café Americano',18,'Café negro estilo americano, con un sabor suave y equilibrado. Perfecto para disfrutar en cualquier momento del día.',2.50,1,1),(17,'/public/assets/img/productos/Cafés Especiales/mocha.jpg','Café Mocha',30,'Café con chocolate y leche, combinando el sabor intenso del café con la dulzura del chocolate. Un placer para el paladar.',4.00,1,1),(18,'/public/assets/img/productos/Cafés Especiales/latte.jpg','Café Latte',35,'Café con leche vaporizada, creando una bebida cremosa y suave. Ideal para aquellos que prefieren un café menos fuerte.',3.75,1,1),(19,'/public/assets/img/productos/Cafés Especiales/capuccino.jpg','Cappuccino',25,'Café con leche y espuma, ofreciendo una textura ligera y una mezcla perfecta de café y leche.',3.80,1,1),(20,'/public/assets/img/productos/Cafés Especiales/flat-white.jpg','Flat White',20,'Café con leche microespumada, proporcionando una bebida cremosa con una capa suave de espuma. Un clásico australiano.',3.90,1,1),(21,'/public/assets/img/productos/Cafés con Leche/latte-vainilla.jpg','Latte Vainilla',30,'Café con leche y jarabe de vainilla, creando una mezcla dulce y aromática. Perfecto para los amantes de sabores suaves.',4.20,2,1),(22,'/public/assets/img/productos/Cafés con Leche/latte-caramelo.jpg','Latte Caramelo',25,'Café con leche y jarabe de caramelo, ofreciendo una combinación rica y dulce de café y caramelo.',4.30,2,1),(23,'/public/assets/img/productos/Cafés con Leche/latte-avellana.jpg','Latte Avellana',20,'Café con leche y jarabe de avellana, proporcionando un sabor delicado y a nuez que complementa el café.',4.40,2,1),(24,'/public/assets/img/productos/Cafés con Leche/vienés.jpg','Café Vienés',15,'Café con crema batida, creando una bebida lujosa y suave con una capa generosa de crema.',4.50,2,1),(25,'/public/assets/img/productos/Cafés con Leche/cortado.jpg','Café Cortado',10,'Café con un toque de leche, ofreciendo una bebida más equilibrada entre el café y la leche. Ideal para una pausa rápida.',4.60,2,1),(26,'/public/assets/img/productos/Cafés con Leche/macchiato.jpg','Café Macchiato',5,'Café con una pequeña cantidad de leche, para aquellos que prefieren un café fuerte con solo un toque de leche.',4.70,2,1),(27,'/public/assets/img/productos/Cafés Fríos/cafe-con-hielo.jpg','Café con Hielo',50,'Café frío con hielo, una opción refrescante para los días calurosos. Mantiene todo el sabor del café en una bebida fría.',3.00,3,1),(28,'/public/assets/img/productos/Cafés Fríos/latte-con-hielo.jpg','Latte con Hielo',40,'Café con leche fría, ideal para disfrutar de un latte refrescante con hielo en los días de calor.',3.50,3,1),(29,'/public/assets/img/productos/Cafés Fríos/mocha-con-hielo.jpg','Mocha con Hielo',30,'Café con chocolate y leche frío, combinado con hielo para una bebida dulce y refrescante.',4.00,3,1),(30,'/public/assets/img/productos/Cafés Fríos/coldbrew.jpg','Cold Brew',20,'Café frío de extracción lenta, ofreciendo un sabor suave y menos ácido. Ideal para los que prefieren un café más suave.',3.75,3,1),(31,'/public/assets/img/productos/Cafés Fríos/frappuccino.jpg','Frappuccino',25,'Café con hielo y crema batida, creando una bebida cremosa y dulce, perfecta para un capricho refrescante.',4.50,3,1),(32,'/public/assets/img/productos/Cafés Fríos/affogato.jpg','Affogato',12,'Café con helado de vainilla, combinando el sabor intenso del café con la dulzura del helado.',4.75,3,1),(33,'/public/assets/img/productos/Pasteles y Postres/cheesecake.jpg','Cheesecake',10,'Pastel de queso con base de galleta, ofreciendo una textura cremosa y un sabor dulce. Perfecto para los amantes de los postres.',5.00,4,1),(34,'/public/assets/img/productos/Pasteles y Postres/brownie.jpg','Brownie',10,'Pastel de chocolate denso, con un sabor rico y una textura húmeda. Ideal para quienes buscan un capricho de chocolate.',3.50,4,1),(35,'/public/assets/img/productos/Pasteles y Postres/tarta-de-manzana.jpg','Tarta de Manzana',20,'Pastel de manzana con canela, ofreciendo una mezcla cálida y especiada de manzana y canela. Un clásico reconfortante.',4.00,4,1),(36,'/public/assets/img/productos/Pasteles y Postres/muffin-de-arandano.jpg','Muffin de Arándanos',25,'Muffin con arándanos frescos, combinando el sabor ácido de los arándanos con una textura suave y esponjosa.',2.75,4,1),(37,'/public/assets/img/productos/Pasteles y Postres/croissant.jpg','Croissant',30,'Croissant de mantequilla, con una textura ligera y crujiente por fuera y suave por dentro. Perfecto para el desayuno.',2.50,4,1),(38,'/public/assets/img/productos/Pasteles y Postres/macarons.jpg','Macarons',35,'Galletas francesas de almendra, con una textura crujiente por fuera y suave por dentro, disponibles en varios sabores.',3.00,4,1),(39,'/public/assets/img/productos/te/te-verde.jpg','Té Verde',40,'Té verde orgánico, con un sabor suave y ligeramente herbáceo. Ideal para una opción de té ligera y saludable.',2.50,5,1),(40,'/public/assets/img/productos/te/te-negro.jpg','Té Negro',35,'Té negro clásico, con un sabor robusto y ligeramente astringente. Perfecto para comenzar el día con energía.',2.75,5,1),(41,'/public/assets/img/productos/te/te-de-manzanilla.jpg','Té de Manzanilla',30,'Té de manzanilla relajante, con un sabor suave y floral. Ideal para relajarse y promover el bienestar.',2.50,5,1),(42,'/public/assets/img/productos/te/te-de-menta.jpg','Té de Menta',25,'Té de menta refrescante, ofreciendo un sabor fresco y vigorizante. Perfecto para revitalizarse durante el día.',2.75,5,1),(43,'/public/assets/img/productos/te/te-chai.jpg','Té Chai',20,'Té chai con especias, combinando té negro con una mezcla aromática de especias. Ideal para quienes disfrutan de sabores intensos.',3.00,5,1),(44,'/public/assets/img/productos/te/te-oolong.jpg','Té Oolong',15,'Té oolong semifermentado, con un sabor único que mezcla notas de té verde y té negro. Perfecto para una experiencia de té equilibrada.',3.25,5,1),(45,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-de-pollo.jpg','Sandwich de Pollo',20,'Sandwich de pollo con mayonesa, ofreciendo un sabor jugoso y cremoso con pollo fresco. Ideal para una comida rápida y satisfactoria.',4.50,6,1),(46,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-de-jamon-y-queso.jpg','Sandwich de Jamón y Queso',25,'Sandwich clásico de jamón y queso, con una combinación simple pero deliciosa de jamón y queso en pan fresco.',4.00,6,1),(47,'/public/assets/img/productos/Sandwich y Bocadillos/bocadillo-de-atun.jpg','Bocadillo de Atún',30,'Bocadillo de atún con lechuga, ofreciendo una mezcla fresca y ligera de atún con lechuga crujiente en pan.',4.25,6,1),(48,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-club.jpg','Sandwich Club',35,'Sándwich con pavo, tocino, lechuga y tomate, creando una combinación sabrosa y abundante con capas de ingredientes frescos.',4.75,6,1),(49,'/public/assets/img/productos/Sandwich y Bocadillos/bagel-con-salmon.jpg','Bagel con Salmón',90,'Bagel con salmón ahumado y queso crema, ofreciendo una combinación lujosa de salmón y queso en un pan bagel recién horneado.',5.00,6,1),(50,'/public/assets/img/productos/Sandwich y Bocadillos/wrap-de-pavo.jpg','Wrap de Pavo',10,'Wrap de pavo con aguacate, proporcionando una opción ligera y saludable con pavo tierno y aguacate cremoso envuelto en una tortilla.',4.50,6,1);
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
  `salario` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`idPuesto`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `puesto`
--

LOCK TABLES `puesto` WRITE;
/*!40000 ALTER TABLE `puesto` DISABLE KEYS */;
INSERT INTO `puesto` VALUES (1,'Admin',5000.00),(2,'Gerente',4000.00),(3,'Chef',3000.00),(4,'Mozo',2000.00);
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
  `idCliente` int DEFAULT NULL,
  `idMesa` int NOT NULL,
  `idEmpleado` int DEFAULT NULL,
  `fechaReserva` datetime NOT NULL,
  `estado` enum('reservado','disponible','cancelado','ocupado','finalizado') NOT NULL,
  `cantidadPersonas` int NOT NULL,
  `codigoReserva` varchar(10) NOT NULL,
  PRIMARY KEY (`idReserva`),
  UNIQUE KEY `unique_codigoReserva` (`codigoReserva`),
  KEY `idx_cliente` (`idCliente`),
  KEY `idx_empleado` (`idEmpleado`),
  KEY `idx_mesa` (`idMesa`),
  CONSTRAINT `reserva_fk_cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`),
  CONSTRAINT `reserva_fk_empleado` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`),
  CONSTRAINT `reserva_fk_mesa` FOREIGN KEY (`idMesa`) REFERENCES `mesa` (`idMesa`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserva`
--

LOCK TABLES `reserva` WRITE;
/*!40000 ALTER TABLE `reserva` DISABLE KEYS */;
INSERT INTO `reserva` VALUES (1,1,1,2,'2024-11-01 18:00:00','reservado',4,'RES001'),(2,2,2,3,'2024-11-01 19:00:00','ocupado',2,'RES002'),(3,3,3,1,'2024-11-01 20:00:00','finalizado',6,'RES003'),(4,4,4,2,'2024-11-02 12:30:00','reservado',3,'RES004'),(5,5,5,3,'2024-11-02 13:00:00','disponible',5,'RES005'),(6,6,1,1,'2024-11-02 18:30:00','cancelado',2,'RES006'),(7,7,2,3,'2024-11-02 19:00:00','ocupado',4,'RES007'),(8,8,3,2,'2024-11-03 20:00:00','reservado',3,'RES008'),(9,9,4,1,'2024-11-03 21:00:00','finalizado',5,'RES009'),(10,10,5,2,'2024-11-03 12:00:00','reservado',2,'RES010'),(11,11,1,1,'2024-11-04 18:00:00','disponible',3,'RES011'),(12,12,2,3,'2024-11-04 19:30:00','cancelado',4,'RES012'),(13,13,3,2,'2024-11-04 20:30:00','reservado',6,'RES013'),(14,14,4,1,'2024-11-05 13:00:00','ocupado',2,'RES014'),(15,15,5,3,'2024-11-05 14:30:00','finalizado',5,'RES015'),(16,16,1,1,'2024-11-06 15:00:00','reservado',3,'RES016'),(17,17,2,2,'2024-11-06 16:15:00','disponible',4,'RES017'),(18,18,3,3,'2024-11-06 17:30:00','cancelado',2,'RES018'),(19,19,4,1,'2024-11-07 19:00:00','ocupado',6,'RES019'),(20,20,5,2,'2024-11-07 20:30:00','finalizado',4,'RES020'),(21,41,11,NULL,'2186-10-11 09:03:00','reservado',2,'MESA56c52d');
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
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retroalimentacion`
--

LOCK TABLES `retroalimentacion` WRITE;
/*!40000 ALTER TABLE `retroalimentacion` DISABLE KEYS */;
INSERT INTO `retroalimentacion` VALUES (35,1,'Alto','El servicio fue excelente y la comida deliciosa. Muy satisfecho.'),(36,2,'Bajo','La espera fue demasiado larga y la comida no cumplió mis expectativas.'),(37,3,'Muy alto','¡Increíble! Todo estuvo perfecto, definitivamente volveré.'),(38,4,'Medio','La experiencia fue buena, pero hubo un par de errores en mi pedido.'),(39,5,'Alto','El ambiente es agradable y el personal muy atento.'),(40,6,'Bajo','La mesa estaba sucia y no había suficientes opciones en el menú.'),(41,7,'Muy bajo','No me gustó la comida, no volveré.'),(42,8,'Medio','La comida estaba bien, pero el servicio podría mejorar.'),(43,9,'Muy alto','Una experiencia maravillosa. ¡Todo fue espectacular!'),(44,10,'Alto','Recomendaría este lugar a mis amigos. Buen trabajo.'),(45,11,'Medio','Aceptable, pero hay margen para mejorar.'),(46,12,'Bajo','No estoy satisfecho con mi experiencia, esperaba más.'),(47,13,'Alto','Gran lugar para comer, definitivamente volveré.'),(48,14,'Muy alto','¡Me encantó! No tengo quejas.'),(49,15,'Bajo','El personal era poco amable y la comida no era buena.'),(50,16,'Medio','Todo fue correcto, pero nada excepcional.'),(51,17,'Alto','La calidad de la comida es impresionante, un lugar que vale la pena visitar.'),(52,18,'Muy alto','Todo fue perfecto, desde el servicio hasta la comida.'),(53,19,'Alto','Buena experiencia en general, aunque el tiempo de espera fue un poco largo.'),(54,20,'Bajo','No recomendaría este lugar basado en mi experiencia.'),(55,41,'Muy alto','tremenda obra maestra 20/10 y god');
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
  `tel` varchar(20) NOT NULL,
  `horarioApertura` time DEFAULT NULL,
  `horarioClausura` time DEFAULT NULL,
  `capacidad` int DEFAULT NULL,
  PRIMARY KEY (`idSucursal`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sucursal`
--

LOCK TABLES `sucursal` WRITE;
/*!40000 ALTER TABLE `sucursal` DISABLE KEYS */;
INSERT INTO `sucursal` VALUES (1,'Café Sabrosos Lisboa','Rua de São Bento 123','Portugal','Lisboa','+351 213 456 789','08:00:00','20:00:00',18),(2,'Café Sabrosos Madrid','Calle Gran Vía 45','España','Madrid','+34 912 345 678','08:00:00','20:00:00',18),(3,'Café Sabrosos Berlín','Kurfürstendamm 100','Alemania','Berlín','+49 30 12345678','08:00:00','20:00:00',18),(4,'Café Sabrosos París','Boulevard Saint-Germain 56','Francia','París','+33 1 2345 6789','08:00:00','20:00:00',18);
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

-- Dump completed on 2024-11-20  4:10:51
