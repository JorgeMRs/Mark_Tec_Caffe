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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cancelacionpedido`
--

LOCK TABLES `cancelacionpedido` WRITE;
/*!40000 ALTER TABLE `cancelacionpedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `cancelacionpedido` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito`
--

LOCK TABLES `carrito` WRITE;
/*!40000 ALTER TABLE `carrito` DISABLE KEYS */;
INSERT INTO `carrito` VALUES (1,53,'2024-09-15 19:47:33'),(2,40,'2024-09-15 20:04:59'),(3,41,'2024-09-16 00:53:59');
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
  `imagen` varchar(255) DEFAULT NULL,
  `estadoActivacion` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  PRIMARY KEY (`idCliente`),
  UNIQUE KEY `correo_unique` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=458 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (33,'root@gmail.com','$2y$10$CTxn2iXZHJml.KGcKu.XGuhfGQtyprz2p8C9ULLdMQZdZOKwyAYGi','root','root','','2024-08-16 01:14:59','2024-08-21 20:38:18',1,NULL,'2024-08-14','33_avatar.jpg'),(35,'lucianobritos154@gmail.com','$2y$10$M/gazcvT7gcnS73BvzRTb.SbLCCnCjLJreFXdo2r.VTVLb3bg7MDi',NULL,NULL,NULL,'2024-08-30 17:31:22','2024-09-12 16:08:19',1,NULL,NULL,NULL),(40,'vaexco@gmail.com','$2y$10$ec7oT3531SJnWfkDXaSSR.EMr99AMDdUCy5AUG2AObuIN35eiOPcy','Alexis','Bentancor','','2024-09-01 19:07:18','2024-09-09 21:23:34',1,NULL,'2024-09-18','40_avatar.jpg'),(41,'andresdelgado050406@gmail.com','$2y$10$X3Y8InM2XTUnDWhjTWqhRe0iJ.W2wOnQM71LPHhzRYcFcSki8jEdC','Eduardo','Delgado','','2024-09-01 19:42:10','2024-09-02 19:28:32',1,NULL,'2006-04-05','41_avatar.jpg'),(43,'josesitovcf3@gmail.com','$2y$10$yrKOUzNMHNb0MLVbQa/2Iuf0m5W5WTdY0kRaO6OQ2l4mEhKOvRAMG',NULL,NULL,NULL,'2024-09-03 20:15:20','2024-09-09 16:25:51',1,NULL,NULL,'43_avatar.jpg'),(46,'Fabriciodeleon007@gmail.com','$2y$10$9kQE7g6Q1toPA8yrI5nq4.sa8xnOf/rWcijatd3VrBQMe9B.10PqK',NULL,NULL,NULL,'2024-09-07 14:55:11','2024-09-08 07:25:00',1,NULL,NULL,'46_avatar.jpg'),(53,'josesitovcf@gmail.com','$2y$10$ScpMzyovRECzhSLCXIC7Ceog.W2q78ygYMkF/W9ODq.m5Mh.PGegC','José','Sanchez','099761830','2024-09-10 01:56:20','2024-09-16 21:55:18',0,NULL,'2024-09-05','53_avatar.jpg'),(354,'user1@example.com','password1','John','Doe','1234567890','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token1','1990-01-01','avatar1.png'),(355,'user2@example.com','password2','Jane','Smith','1234567891','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token2','1991-02-02','avatar2.png'),(356,'user3@example.com','password3','Alice','Johnson','1234567892','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token3','1992-03-03','avatar3.png'),(357,'user4@example.com','password4','Bob','Brown','1234567893','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token4','1993-04-04','avatar4.png'),(358,'user5@example.com','password5','Charlie','Davis','1234567894','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token5','1994-05-05','avatar5.png'),(359,'user6@example.com','password6','David','Wilson','1234567895','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token6','1995-06-06','avatar6.png'),(360,'user7@example.com','password7','Eve','Garcia','1234567896','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token7','1996-07-07','avatar7.png'),(361,'user8@example.com','password8','Frank','Martinez','1234567897','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token8','1997-08-08','avatar8.png'),(362,'user9@example.com','password9','Grace','Hernandez','1234567898','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token9','1998-09-09','avatar9.png'),(363,'user10@example.com','password10','Hank','Lopez','1234567899','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token10','1999-10-10','avatar10.png'),(364,'user11@example.com','password11','Ivy','Gonzalez','1234567800','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token11','2000-11-11','avatar11.png'),(365,'user12@example.com','password12','Jack','Perez','1234567801','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token12','2001-12-12','avatar12.png'),(366,'user13@example.com','password13','Kathy','Wilson','1234567802','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token13','2002-01-13','avatar13.png'),(367,'user14@example.com','password14','Leo','Anderson','1234567803','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token14','2003-02-14','avatar14.png'),(368,'user15@example.com','password15','Mia','Thomas','1234567804','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token15','2004-03-15','avatar15.png'),(369,'user16@example.com','password16','Nina','Taylor','1234567805','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token16','2005-04-16','avatar16.png'),(370,'user17@example.com','password17','Oscar','Moore','1234567806','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token17','2006-05-17','avatar17.png'),(371,'user18@example.com','password18','Paul','Jackson','1234567807','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token18','2007-06-18','avatar18.png'),(372,'user19@example.com','password19','Quinn','Martin','1234567808','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token19','2008-07-19','avatar19.png'),(373,'user20@example.com','password20','Rita','Lee','1234567809','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token20','2009-08-20','avatar20.png'),(374,'user21@example.com','password21','Sam','Perez','1234567810','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token21','2010-09-21','avatar21.png'),(375,'user22@example.com','password22','Tina','Thompson','1234567811','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token22','2011-10-22','avatar22.png'),(376,'user23@example.com','password23','Ursula','White','1234567812','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token23','2012-11-23','avatar23.png'),(377,'user24@example.com','password24','Victor','Harris','1234567813','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token24','2013-12-24','avatar24.png'),(378,'user25@example.com','password25','Wendy','Clark','1234567814','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token25','2014-01-25','avatar25.png'),(379,'user26@example.com','password26','Xander','Lewis','1234567815','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token26','2015-02-26','avatar26.png'),(380,'user27@example.com','password27','Yara','Robinson','1234567816','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token27','2016-03-27','avatar27.png'),(381,'user28@example.com','password28','Zane','Walker','1234567817','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token28','2017-04-28','avatar28.png'),(382,'user29@example.com','password29','Amy','Hall','1234567818','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token29','2018-05-29','avatar29.png'),(383,'user30@example.com','password30','Brian','Allen','1234567819','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token30','2019-06-30','avatar30.png'),(384,'user31@example.com','password31','Cathy','Young','1234567820','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token31','2020-07-31','avatar31.png'),(385,'user32@example.com','password32','Derek','King','1234567821','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token32','2021-08-01','avatar32.png'),(386,'user33@example.com','password33','Ella','Scott','1234567822','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token33','2022-09-02','avatar33.png'),(387,'user34@example.com','password34','Fiona','Green','1234567823','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token34','2023-10-03','avatar34.png'),(388,'user35@example.com','password35','George','Adams','1234567824','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token35','2024-11-04','avatar35.png'),(389,'user36@example.com','password36','Holly','Baker','1234567825','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token36','2025-12-05','avatar36.png'),(390,'user37@example.com','password37','Ian','Gonzalez','1234567826','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token37','2026-01-06','avatar37.png'),(391,'user38@example.com','password38','Jade','Nelson','1234567827','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token38','2027-02-07','avatar38.png'),(392,'user39@example.com','password39','Kyle','Carter','1234567828','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token39','2028-03-08','avatar39.png'),(393,'user40@example.com','password40','Liam','Mitchell','1234567829','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token40','2029-04-09','avatar40.png'),(394,'user41@example.com','password41','Maya','Perez','1234567830','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token41','2030-05-10','avatar41.png'),(395,'user42@example.com','password42','Nina','Roberts','1234567831','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token42','2031-06-11','avatar42.png'),(396,'user43@example.com','password43','Owen','Turner','1234567832','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token43','2032-07-12','avatar43.png'),(397,'user44@example.com','password44','Paula','Phillips','1234567833','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token44','2033-08-13','avatar44.png'),(398,'user45@example.com','password45','Quincy','Campbell','1234567834','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token45','2034-09-14','avatar45.png'),(399,'user46@example.com','password46','Rita','Parker','1234567835','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token46','2035-10-15','avatar46.png'),(400,'user47@example.com','password47','Steve','Evans','1234567836','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token47','2036-11-16','avatar47.png'),(401,'user48@example.com','password48','Tina','Edwards','1234567837','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token48','2037-12-17','avatar48.png'),(402,'user49@example.com','password49','Uma','Collins','1234567838','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token49','2038-01-18','avatar49.png'),(403,'user50@example.com','password50','Vera','Stewart','1234567839','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token50','2039-02-19','avatar50.png'),(404,'user51@example.com','password51','Will','Sanchez','1234567840','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token51','2040-03-20','avatar51.png'),(405,'user52@example.com','password52','Xena','Morris','1234567841','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token52','2041-04-21','avatar52.png'),(406,'user53@example.com','password53','Yasmin','Rogers','1234567842','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token53','2042-05-22','avatar53.png'),(407,'user54@example.com','password54','Zara','Reed','1234567843','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token54','2043-06-23','avatar54.png'),(408,'user55@example.com','password55','Aaron','Cook','1234567844','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token55','2044-07-24','avatar55.png'),(409,'user56@example.com','password56','Bella','Morgan','1234567845','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token56','2045-08-25','avatar56.png'),(410,'user57@example.com','password57','Cody','Bell','1234567846','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token57','2046-09-26','avatar57.png'),(411,'user58@example.com','password58','Diana','Murphy','1234567847','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token58','2047-10-27','avatar58.png'),(412,'user59@example.com','password59','Ethan','Rivera','1234567848','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token59','2048-11-28','avatar59.png'),(413,'user60@example.com','password60','Fiona','Cooper','1234567849','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token60','2049-12-29','avatar60.png'),(414,'user61@example.com','password61','Gabe','Richardson','1234567850','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token61','2050-01-30','avatar61.png'),(415,'user62@example.com','password62','Holly','Cox','1234567851','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token62','2045-02-27','avatar62.png'),(416,'user63@example.com','password63','Ian','Howard','1234567852','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token63','2052-03-01','avatar63.png'),(417,'user64@example.com','password64','Jenna','Ward','1234567853','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token64','2053-04-02','avatar64.png'),(418,'user65@example.com','password65','Kyle','Torres','1234567854','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token65','2054-05-03','avatar65.png'),(419,'user66@example.com','password66','Liam','Peterson','1234567855','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token66','2055-06-04','avatar66.png'),(420,'user67@example.com','password67','Mia','Gray','1234567856','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token67','2056-07-05','avatar67.png'),(421,'user68@example.com','password68','Nina','James','1234567857','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token68','2057-08-06','avatar68.png'),(422,'user69@example.com','password69','Oscar','Watson','1234567858','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token69','2058-09-07','avatar69.png'),(423,'user70@example.com','password70','Paula','Brooks','1234567859','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token70','2059-10-08','avatar70.png'),(424,'user71@example.com','password71','Quinn','Kelly','1234567860','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token71','2060-11-09','avatar71.png'),(425,'user72@example.com','password72','Rita','Sanders','1234567861','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token72','2061-12-10','avatar72.png'),(426,'user73@example.com','password73','Steve','Price','1234567862','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token73','2062-01-11','avatar73.png'),(427,'user74@example.com','password74','Tina','Bennett','1234567863','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token74','2063-02-12','avatar74.png'),(428,'user75@example.com','password75','Uma','Wood','1234567864','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token75','2064-03-13','avatar75.png'),(429,'user76@example.com','password76','Vera','Barnes','1234567865','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token76','2065-04-14','avatar76.png'),(430,'user77@example.com','password77','Will','Hawkins','1234567866','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token77','2066-05-15','avatar77.png'),(431,'user78@example.com','password78','Xena','Chavez','1234567867','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token78','2067-06-16','avatar78.png'),(432,'user79@example.com','password79','Yasmin','Gonzalez','1234567868','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token79','2068-07-17','avatar79.png'),(433,'user80@example.com','password80','Zara','Hernandez','1234567869','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token80','2069-08-18','avatar80.png'),(434,'user81@example.com','password81','Aaron','Morris','1234567870','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token81','2070-09-19','avatar81.png'),(435,'user82@example.com','password82','Bella','Rogers','1234567871','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token82','2071-10-20','avatar82.png'),(436,'user83@example.com','password83','Cody','Reed','1234567872','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token83','2072-11-21','avatar83.png'),(437,'user84@example.com','password84','Diana','Cook','1234567873','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token84','2073-12-22','avatar84.png'),(438,'user85@example.com','password85','Ethan','Morgan','1234567874','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token85','2074-01-23','avatar85.png'),(439,'user86@example.com','password86','Fiona','Bell','1234567875','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token86','2075-02-24','avatar86.png'),(440,'user87@example.com','password87','Gabe','Murphy','1234567876','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token87','2076-03-25','avatar87.png'),(441,'user88@example.com','password88','Holly','Rivera','1234567877','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token88','2077-04-26','avatar88.png'),(442,'user89@example.com','password89','Ian','Cooper','1234567878','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token89','2078-05-27','avatar89.png'),(443,'user90@example.com','password90','Jenna','Richardson','1234567879','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token90','2079-06-28','avatar90.png'),(444,'user91@example.com','password91','Kyle','Cox','1234567880','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token91','2080-07-29','avatar91.png'),(445,'user92@example.com','password92','Liam','Howard','1234567881','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token92','2081-08-30','avatar92.png'),(446,'user93@example.com','password93','Mia','Ward','1234567882','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token93','2082-09-27','avatar93.png'),(447,'user94@example.com','password94','Nina','Torres','1234567883','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token94','2083-10-01','avatar94.png'),(448,'user95@example.com','password95','Oscar','Peterson','1234567884','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token95','2084-11-02','avatar95.png'),(449,'user96@example.com','password96','Paula','Gray','1234567885','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token96','2085-12-03','avatar96.png'),(450,'user97@example.com','password97','Quinn','James','1234567886','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token97','2086-01-04','avatar97.png'),(451,'user98@example.com','password98','Rita','Watson','1234567887','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token98','2087-02-05','avatar98.png'),(452,'user99@example.com','password99','Steve','Brooks','1234567888','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token99','2088-03-06','avatar99.png'),(453,'user100@example.com','password100','Tina','Kelly','1234567889','2024-09-13 04:50:59','2024-09-13 04:50:59',1,'token100','2089-04-07','avatar100.png'),(455,'josesitovcf2@gmail.com','$2y$10$ScpMzyovRECzhSLCXIC7Ceog.W2q78ygYMkF/W9ODq.m5Mh.PGegC','José','Sanchez','099761830','2024-09-10 01:56:20','2024-09-18 20:17:29',1,NULL,'2024-09-05','455_avatar.jpg'),(456,'josesitovcf34@gmail.com','$2y$10$1c00i7tFjMAgVHf0lMvA2eJahBIMmFJ.usPGmusbNOqy01edUwzaC',NULL,NULL,NULL,'2024-09-18 20:45:29','2024-09-18 20:45:29',0,'c18560f617212abb3c68e0fd1c969e6e',NULL,NULL),(457,'josesitovcf234234@gmail.com','$2y$10$lqOjPRb563uVR5lDGg2gR..o7WM4DXIz1ZRz2HpnKG7/ziBOsKO86',NULL,NULL,NULL,'2024-09-18 20:48:43','2024-09-18 20:48:43',0,'37dfa0a71f35a5600dd27117cbcb0277',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado`
--

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
INSERT INTO `empleado` VALUES (1,1,2,'admin@cafesabrosos.com','admin12367','José','Sanchez','12345678901','2024-01-01','+351 213 456 790','1980-05-15',1),(2,2,1,'gerente1@cafesabrosos.com','gerente12367','Carlos','Ríos','23456789012','2024-01-01','+351 213 456 791','1985-07-22',1),(3,3,1,'chef1@cafesabrosos.com','$2a$12$DYfbuiBZCn2URo9h7J3LBeOYUj5gPiOUiWNklBF8SIBwUK2RKVi42','Ana','López','34567890123','2024-01-01','+351 213 456 792','1990-10-10',1),(4,4,1,'mozo1@cafesabrosos.com','$2a$12$pJf2BkL7x632b.DV2aNSeeA611KDspu.PSMvmstaZcTixAJw9D1Ly','Sofía','Martín','45678901234','2024-01-01','+351 213 456 793','1995-03-20',1),(5,2,2,'andres.fernandez@example.com','gerente12367','Andrés','Fernández','67890123456','2024-01-01','+34 912 345 681','1985-07-22',1),(6,3,2,'eva.rodriguez@example.com','chef12367','Eva','Rodríguez','78901234567','2024-01-01','+34 912 345 682','1990-10-10',1),(7,4,2,'isabel.garcia@example.com','$2a$12$E7aCBVsEB0lhnPqDeTpzee7O8kxr7zKf5MDZTwvRs8EBu08lGfBkO','Isabel','García','89012345678','2024-01-01','+34 912 345 683','1995-03-20',1),(8,2,3,'martin.mueller@example.com','gerente12367','Martin','Müller','01234567890','2024-01-01','+49 30 12345681','1985-07-22',1),(9,3,3,'clara.bauer@example.com','chef12367','Clara','Bauer','12345678901','2024-01-01','+49 30 12345682','1990-10-10',1),(10,4,3,'laura.weber@example.com','$2a$12$E7aCBVsEB0lhnPqDeTpzee7O8kxr7zKf5MDZTwvRs8EBu08lGfBkO','Laura','Weber','23456789012','2024-01-01','+49 30 12345683','1995-03-20',1),(11,2,4,'jean.dupont@example.com','gerente12367','Jean','Dupont','45678901234','2024-01-01','+33 1 2345 6781','1985-07-22',1),(12,3,4,'marie.lefevre@example.com','chef12367','Marie','Lefevre','56789012345','2024-01-01','+33 1 2345 6782','1990-10-10',1),(13,4,4,'lucie.bernard@example.com','mozo12367','Lucie','Bernard','67890123456','2024-01-01','+33 1 2345 6783','1995-03-20',1);
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
INSERT INTO `numeropedidosucursal` VALUES (1,1),(3,1),(4,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (1,NULL,53,1,NULL,3,'Pendiente','',NULL,'Tarjeta','En el local',4.20,'2024-09-15 19:49:14','2024-09-15 19:49:14','PEDIDOca49db',1,1),(2,NULL,53,1,NULL,4,'Pendiente','asd',NULL,'Tarjeta','En el local',3.00,'2024-09-15 19:50:02','2024-09-15 19:50:02','PEDIDOc1ef8d',2,1),(3,4,NULL,NULL,NULL,1,'Pendiente','asdasd','16:00:00','Efectivo','Para llevar',6.60,'2024-09-15 19:59:17','2024-09-15 19:59:17',NULL,NULL,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidodetalle`
--

LOCK TABLES `pedidodetalle` WRITE;
/*!40000 ALTER TABLE `pedidodetalle` DISABLE KEYS */;
INSERT INTO `pedidodetalle` VALUES (1,1,15,1,3.50),(2,2,16,1,2.50),(3,3,42,1,2.75),(4,3,40,1,2.75);
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
  `estadoActivacion` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idProducto`),
  KEY `idx_categoria` (`idCategoria`),
  CONSTRAINT `producto_fk_categoria` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (15,'/public/assets/img/productos/Cafés Especiales/espresso.jpg','Espresso',10,'Café espresso doble shot con un sabor intenso y una crema rica. Ideal para los amantes del café fuerte.',3.50,1,1),(16,'/public/assets/img/productos/Cafés Especiales/americano.jpg','Café Americano',18,'Café negro estilo americano, con un sabor suave y equilibrado. Perfecto para disfrutar en cualquier momento del día.',2.50,1,1),(18,'/public/assets/img/productos/Cafés Especiales/latte.jpg','Café Latte',35,'Café con leche vaporizada, creando una bebida cremosa y suave. Ideal para aquellos que prefieren un café menos fuerte.',3.75,1,1),(19,'/public/assets/img/productos/Cafés Especiales/capuccino.jpg','Cappuccino',25,'Café con leche y espuma, ofreciendo una textura ligera y una mezcla perfecta de café y leche.',3.80,1,1),(20,'/public/assets/img/productos/Cafés Especiales/flat-white.jpg','Flat White',20,'Café con leche microespumada, proporcionando una bebida cremosa con una capa suave de espuma. Un clásico australiano.',3.90,1,1),(21,'/public/assets/img/productos/Cafés con Leche/latte-vainilla.jpg','Latte Vainilla',30,'Café con leche y jarabe de vainilla, creando una mezcla dulce y aromática. Perfecto para los amantes de sabores suaves.',4.20,2,1),(22,'/public/assets/img/productos/Cafés con Leche/latte-caramelo.jpg','Latte Caramelo',25,'Café con leche y jarabe de caramelo, ofreciendo una combinación rica y dulce de café y caramelo.',4.30,2,1),(23,'/public/assets/img/productos/Cafés con Leche/latte-avellana.jpg','Latte Avellana',20,'Café con leche y jarabe de avellana, proporcionando un sabor delicado y a nuez que complementa el café.',4.40,2,1),(24,'/public/assets/img/productos/Cafés con Leche/vienés.jpg','Café Vienés',15,'Café con crema batida, creando una bebida lujosa y suave con una capa generosa de crema.',4.50,2,1),(25,'/public/assets/img/productos/Cafés con Leche/cortado.jpg','Café Cortado',10,'Café con un toque de leche, ofreciendo una bebida más equilibrada entre el café y la leche. Ideal para una pausa rápida.',4.60,2,1),(26,'/public/assets/img/productos/Cafés con Leche/macchiato.jpg','Café Macchiato',5,'Café con una pequeña cantidad de leche, para aquellos que prefieren un café fuerte con solo un toque de leche.',4.70,2,1),(27,'/public/assets/img/productos/Cafés Fríos/cafe-con-hielo.jpg','Café con Hielo',50,'Café frío con hielo, una opción refrescante para los días calurosos. Mantiene todo el sabor del café en una bebida fría.',3.00,3,1),(28,'/public/assets/img/productos/Cafés Fríos/latte-con-hielo.jpg','Latte con Hielo',40,'Café con leche fría, ideal para disfrutar de un latte refrescante con hielo en los días de calor.',3.50,3,1),(29,'/public/assets/img/productos/Cafés Fríos/mocha-con-hielo.jpg','Mocha con Hielo',30,'Café con chocolate y leche frío, combinado con hielo para una bebida dulce y refrescante.',4.00,3,1),(30,'/public/assets/img/productos/Cafés Fríos/coldbrew.jpg','Cold Brew',20,'Café frío de extracción lenta, ofreciendo un sabor suave y menos ácido. Ideal para los que prefieren un café más suave.',3.75,3,1),(31,'/public/assets/img/productos/Cafés Fríos/frappuccino.jpg','Frappuccino',25,'Café con hielo y crema batida, creando una bebida cremosa y dulce, perfecta para un capricho refrescante.',4.50,3,1),(32,'/public/assets/img/productos/Cafés Fríos/affogato.jpg','Affogato',15,'Café con helado de vainilla, combinando el sabor intenso del café con la dulzura del helado.',4.75,3,1),(33,'/public/assets/img/productos/Pasteles y Postres/cheesecake.jpg','Cheesecake',10,'Pastel de queso con base de galleta, ofreciendo una textura cremosa y un sabor dulce. Perfecto para los amantes de los postres.',5.00,4,1),(34,'/public/assets/img/productos/Pasteles y Postres/brownie.jpg','Brownie',15,'Pastel de chocolate denso, con un sabor rico y una textura húmeda. Ideal para quienes buscan un capricho de chocolate.',3.50,4,1),(35,'/public/assets/img/productos/Pasteles y Postres/tarta-de-manzana.jpg','Tarta de Manzana',20,'Pastel de manzana con canela, ofreciendo una mezcla cálida y especiada de manzana y canela. Un clásico reconfortante.',4.00,4,1),(36,'/public/assets/img/productos/Pasteles y Postres/muffin-de-arandano.jpg','Muffin de Arándanos',25,'Muffin con arándanos frescos, combinando el sabor ácido de los arándanos con una textura suave y esponjosa.',2.75,4,1),(37,'/public/assets/img/productos/Pasteles y Postres/croissant.jpg','Croissant',30,'Croissant de mantequilla, con una textura ligera y crujiente por fuera y suave por dentro. Perfecto para el desayuno.',2.50,4,1),(38,'/public/assets/img/productos/Pasteles y Postres/macarons.jpg','Macarons',35,'Galletas francesas de almendra, con una textura crujiente por fuera y suave por dentro, disponibles en varios sabores.',3.00,4,1),(39,'/public/assets/img/productos/te/te-verde.jpg','Té Verde',40,'Té verde orgánico, con un sabor suave y ligeramente herbáceo. Ideal para una opción de té ligera y saludable.',2.50,5,1),(40,'/public/assets/img/productos/te/te-negro.jpg','Té Negro',35,'Té negro clásico, con un sabor robusto y ligeramente astringente. Perfecto para comenzar el día con energía.',2.75,5,1),(41,'/public/assets/img/productos/te/te-de-manzanilla.jpg','Té de Manzanilla',30,'Té de manzanilla relajante, con un sabor suave y floral. Ideal para relajarse y promover el bienestar.',2.50,5,1),(42,'/public/assets/img/productos/te/te-de-menta.jpg','Té de Menta',25,'Té de menta refrescante, ofreciendo un sabor fresco y vigorizante. Perfecto para revitalizarse durante el día.',2.75,5,1),(43,'/public/assets/img/productos/te/te-chai.jpg','Té Chai',20,'Té chai con especias, combinando té negro con una mezcla aromática de especias. Ideal para quienes disfrutan de sabores intensos.',3.00,5,1),(44,'/public/assets/img/productos/te/te-oolong.jpg','Té Oolong',15,'Té oolong semifermentado, con un sabor único que mezcla notas de té verde y té negro. Perfecto para una experiencia de té equilibrada.',3.25,5,1),(45,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-de-pollo.jpg','Sandwich de Pollo',20,'Sandwich de pollo con mayonesa, ofreciendo un sabor jugoso y cremoso con pollo fresco. Ideal para una comida rápida y satisfactoria.',4.50,6,1),(46,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-de-jamon-y-queso.jpg','Sandwich de Jamón y Queso',25,'Sandwich clásico de jamón y queso, con una combinación simple pero deliciosa de jamón y queso en pan fresco.',4.00,6,1),(47,'/public/assets/img/productos/Sandwich y Bocadillos/bocadillo-de-atun.jpg','Bocadillo de Atún',30,'Bocadillo de atún con lechuga, ofreciendo una mezcla fresca y ligera de atún con lechuga crujiente en pan.',4.25,6,1),(48,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-club.jpg','Sandwich Club',35,'Sándwich con pavo, tocino, lechuga y tomate, creando una combinación sabrosa y abundante con capas de ingredientes frescos.',4.75,6,1),(49,'/public/assets/img/productos/Sandwich y Bocadillos/bagel-con-salmon.jpg','Bagel con Salmón',15,'Bagel con salmón ahumado y queso crema, ofreciendo una combinación lujosa de salmón y queso en un pan bagel recién horneado.',5.00,6,1),(50,'/public/assets/img/productos/Sandwich y Bocadillos/wrap-de-pavo.jpg','Wrap de Pavo',10,'Wrap de pavo con aguacate, proporcionando una opción ligera y saludable con pavo tierno y aguacate cremoso envuelto en una tortilla.',4.50,6,1),(69,'/public/assets/img/productos/Sandwich y Bocadillos/wrap-de-pavo.jpg','Wrap de Pavo',10,'Wrap de pavo con aguacate, proporcionando una opción ligera y saludable con pavo tierno y aguacate cremoso envuelto en una tortilla.',4.50,6,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserva`
--

LOCK TABLES `reserva` WRITE;
/*!40000 ALTER TABLE `reserva` DISABLE KEYS */;
INSERT INTO `reserva` VALUES (34,43,2,NULL,'2024-09-04 18:18:00','reservado',2,'MESA00cae3'),(38,46,3,NULL,'2024-09-07 12:13:00','reservado',2,'MESAaa76e7'),(48,35,1,NULL,'2024-09-12 17:01:00','ocupado',1,'MESAfa1afc'),(52,40,7,NULL,'2024-09-11 18:49:00','reservado',1,'MESAa6a675');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retroalimentacion`
--

LOCK TABLES `retroalimentacion` WRITE;
/*!40000 ALTER TABLE `retroalimentacion` DISABLE KEYS */;
INSERT INTO `retroalimentacion` VALUES (2,41,'Muy alto','tremenda obra maestra 20/10 y god'),(3,40,'Muy alto','Muy buena pagina! '),(9,40,'Muy alto','Epico');
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
  PRIMARY KEY (`idSucursal`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sucursal`
--

LOCK TABLES `sucursal` WRITE;
/*!40000 ALTER TABLE `sucursal` DISABLE KEYS */;
INSERT INTO `sucursal` VALUES (1,'Café Sabrosos Lisboa','Rua de São Bento 123','Portugal','Lisboa','+351 213 456 789'),(2,'Café Sabrosos Madrid','Calle Gran Vía 45','España','Madrid','+34 912 345 678'),(3,'Café Sabrosos Berlín','Kurfürstendamm 100','Alemania','Berlín','+49 30 12345678'),(4,'Café Sabrosos París','Boulevard Saint-Germain 56','Francia','París','+33 1 2345 6789');
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

-- Dump completed on 2024-09-18 21:51:38
