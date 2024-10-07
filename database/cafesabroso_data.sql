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
-- Dumping data for table `carrito`
--

LOCK TABLES `carrito` WRITE;
/*!40000 ALTER TABLE `carrito` DISABLE KEYS */;
INSERT INTO `carrito` VALUES (4,35,'2024-09-03 18:46:41'),(5,40,'2024-09-05 03:53:47'),(18,46,'2024-09-07 15:00:11'),(19,43,'2024-09-07 16:41:45'),(27,43,'2024-09-08 03:30:21'),(44,41,'2024-09-08 22:06:07'),(56,41,'2024-09-09 17:35:35'),(59,33,'2024-09-09 22:17:48'),(60,33,'2024-09-09 22:30:28');
/*!40000 ALTER TABLE `carrito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `carritodetalle`
--

LOCK TABLES `carritodetalle` WRITE;
/*!40000 ALTER TABLE `carritodetalle` DISABLE KEYS */;
INSERT INTO `carritodetalle` VALUES (110,56,25,1,4.60),(112,60,22,4,4.30);
/*!40000 ALTER TABLE `carritodetalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Cafés Especiales'),(2,'Cafés con Leche'),(3,'Cafés Fríos'),(4,'Pasteles y Postres'),(5,'Té'),(6,'Sandwiches y Bocadillos');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (33,'root@gmail.com','$2y$10$CTxn2iXZHJml.KGcKu.XGuhfGQtyprz2p8C9ULLdMQZdZOKwyAYGi','root','root','','2024-08-16 01:14:59','2024-08-21 20:38:18',1,NULL,'2024-08-14','33_avatar.jpg'),(35,'lucianobritos154@gmail.com','$2y$10$M/gazcvT7gcnS73BvzRTb.SbLCCnCjLJreFXdo2r.VTVLb3bg7MDi',NULL,NULL,NULL,'2024-08-30 17:31:22','2024-08-30 20:12:18',1,NULL,NULL,'35_avatar.jpg'),(40,'vaexco@gmail.com','$2y$10$ec7oT3531SJnWfkDXaSSR.EMr99AMDdUCy5AUG2AObuIN35eiOPcy','Alexis','Bentancor','','2024-09-01 19:07:18','2024-09-09 21:23:34',1,NULL,'2024-09-18','40_avatar.jpg'),(41,'andresdelgado050406@gmail.com','$2y$10$X3Y8InM2XTUnDWhjTWqhRe0iJ.W2wOnQM71LPHhzRYcFcSki8jEdC','Eduardo','Delgado','','2024-09-01 19:42:10','2024-09-02 19:28:32',1,NULL,'2006-04-05','41_avatar.jpg'),(43,'josesitovcf3@gmail.com','$2y$10$yrKOUzNMHNb0MLVbQa/2Iuf0m5W5WTdY0kRaO6OQ2l4mEhKOvRAMG',NULL,NULL,NULL,'2024-09-03 20:15:20','2024-09-09 16:25:51',1,NULL,NULL,'43_avatar.jpg'),(46,'Fabriciodeleon007@gmail.com','$2y$10$9kQE7g6Q1toPA8yrI5nq4.sa8xnOf/rWcijatd3VrBQMe9B.10PqK',NULL,NULL,NULL,'2024-09-07 14:55:11','2024-09-08 07:25:00',1,NULL,NULL,'46_avatar.jpg');
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `empleado`
--

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
INSERT INTO `empleado` VALUES (1,1,2,'admin@example.com','admin12367','José','Sanchez','12345678901','2024-01-01','+351 213 456 790','1980-05-15'),(2,2,1,'carlos.rios@example.com','gerente12367','Carlos','Ríos','23456789012','2024-01-01','+351 213 456 791','1985-07-22'),(3,3,1,'ana.lopez@example.com','chef12367','Ana','López','34567890123','2024-01-01','+351 213 456 792','1990-10-10'),(4,4,1,'sofia.martin@example.com','$2a$12$E7aCBVsEB0lhnPqDeTpzee7O8kxr7zKf5MDZTwvRs8EBu08lGfBkO','Sofía','Martín','45678901234','2024-01-01','+351 213 456 793','1995-03-20'),(5,2,2,'andres.fernandez@example.com','gerente12367','Andrés','Fernández','67890123456','2024-01-01','+34 912 345 681','1985-07-22'),(6,3,2,'eva.rodriguez@example.com','chef12367','Eva','Rodríguez','78901234567','2024-01-01','+34 912 345 682','1990-10-10'),(7,4,2,'isabel.garcia@example.com','$2a$12$E7aCBVsEB0lhnPqDeTpzee7O8kxr7zKf5MDZTwvRs8EBu08lGfBkO','Isabel','García','89012345678','2024-01-01','+34 912 345 683','1995-03-20'),(8,2,3,'martin.mueller@example.com','gerente12367','Martin','Müller','01234567890','2024-01-01','+49 30 12345681','1985-07-22'),(9,3,3,'clara.bauer@example.com','chef12367','Clara','Bauer','12345678901','2024-01-01','+49 30 12345682','1990-10-10'),(10,4,3,'laura.weber@example.com','mozo12367','Laura','Weber','23456789012','2024-01-01','+49 30 12345683','1995-03-20'),(11,2,4,'jean.dupont@example.com','gerente12367','Jean','Dupont','45678901234','2024-01-01','+33 1 2345 6781','1985-07-22'),(12,3,4,'marie.lefevre@example.com','chef12367','Marie','Lefevre','56789012345','2024-01-01','+33 1 2345 6782','1990-10-10'),(13,4,4,'lucie.bernard@example.com','mozo12367','Lucie','Bernard','67890123456','2024-01-01','+33 1 2345 6783','1995-03-20');
/*!40000 ALTER TABLE `empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `mesa`
--

LOCK TABLES `mesa` WRITE;
/*!40000 ALTER TABLE `mesa` DISABLE KEYS */;
INSERT INTO `mesa` VALUES (1,1,4,1),(2,2,2,1),(3,3,6,1),(4,4,4,1),(5,5,2,1),(6,1,4,2),(7,2,2,2),(8,3,6,2),(9,4,4,2),(10,5,2,2),(11,1,4,3),(12,2,2,3),(13,3,6,3),(14,4,4,3),(15,5,2,3),(16,1,4,4),(17,2,2,4),(18,3,6,4),(19,4,4,4),(20,5,2,4);
/*!40000 ALTER TABLE `mesa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (2,NULL,43,19,1,1,'En Preparación','',NULL,'Tarjeta','En el local',31.00,'2024-09-08 03:03:14','2024-09-09 20:55:22',NULL,1),(3,NULL,43,27,NULL,2,'Pendiente','','00:00:14','Tarjeta','Para llevar',8.94,'2024-09-08 03:31:14','2024-09-08 03:31:14','PEDIDO9220d9',2),(22,NULL,40,5,NULL,1,'Cancelado','l','16:00:00','Tarjeta','Para llevar',56.76,'2024-09-08 16:32:34','2024-09-09 17:11:20','PEDIDO62aead',1),(25,NULL,41,44,NULL,2,'Pendiente','','16:30:00','Tarjeta','Para llevar',4.50,'2024-09-08 23:32:31','2024-09-09 21:21:41','PEDIDOc8b00c',1),(26,NULL,35,4,NULL,4,'Pendiente','',NULL,'Tarjeta','En el local',8.34,'2024-09-09 00:11:27','2024-09-09 00:11:27','PEDIDO4ef87b',1),(27,4,NULL,NULL,2,1,'Cancelado','asd',NULL,'Efectivo','En el local',12.00,'2024-09-09 03:13:47','2024-09-09 17:11:26',NULL,NULL),(28,4,NULL,NULL,4,1,'Pendiente','',NULL,'Efectivo','En el local',40.50,'2024-09-09 04:35:32','2024-09-09 04:35:32',NULL,NULL),(29,4,NULL,NULL,3,1,'Pendiente','',NULL,'Efectivo','En el local',6.00,'2024-09-09 05:08:12','2024-09-09 05:08:12',NULL,NULL),(32,4,NULL,NULL,4,1,'Pendiente','',NULL,'Efectivo','En el local',16.92,'2024-09-09 17:18:24','2024-09-09 17:18:24',NULL,NULL),(33,4,NULL,NULL,4,1,'Pendiente','',NULL,'Efectivo','En el local',99.84,'2024-09-09 17:19:26','2024-09-09 17:19:26',NULL,NULL),(34,NULL,40,5,NULL,2,'Pendiente','2',NULL,'Tarjeta','En el local',25.80,'2024-09-09 17:45:52','2024-09-09 17:45:52','PEDIDO454334',2),(38,NULL,33,59,NULL,3,'Pendiente','',NULL,'Tarjeta','En el local',16.80,'2024-09-09 22:18:00','2024-09-09 22:18:00','PEDIDOa2b425',1);
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `pedidodetalle`
--

LOCK TABLES `pedidodetalle` WRITE;
/*!40000 ALTER TABLE `pedidodetalle` DISABLE KEYS */;
INSERT INTO `pedidodetalle` VALUES (4,2,22,5,4.30),(5,2,23,1,4.40),(7,3,21,1,4.20),(8,3,44,1,3.25),(25,22,22,11,4.30),(30,25,18,1,3.75),(31,26,21,1,4.20),(32,26,40,1,2.75),(34,27,40,2,2.75),(35,27,44,2,3.25),(36,28,45,6,4.50),(37,28,50,3,4.50),(38,29,49,1,5.00),(47,32,26,3,4.70),(48,33,26,5,4.70),(49,33,24,2,4.50),(50,33,17,2,4.00),(51,33,19,4,3.80),(52,33,42,1,2.75),(53,33,40,3,2.75),(54,33,44,2,3.25),(55,33,39,4,2.50),(56,34,22,5,4.30),(62,38,28,4,3.50);
/*!40000 ALTER TABLE `pedidodetalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (15,'/public/assets/img/productos/Cafés Especiales/espresso.jpg','Espresso',50,'Café espresso doble shot con un sabor intenso y una crema rica. Ideal para los amantes del café fuerte.',3.50,1),(16,'/public/assets/img/productos/Cafés Especiales/americano.jpg','Café Americano',40,'Café negro estilo americano, con un sabor suave y equilibrado. Perfecto para disfrutar en cualquier momento del día.',2.50,1),(17,'/public/assets/img/productos/Cafés Especiales/mocha.jpg','Café Mocha',30,'Café con chocolate y leche, combinando el sabor intenso del café con la dulzura del chocolate. Un placer para el paladar.',4.00,1),(18,'/public/assets/img/productos/Cafés Especiales/latte.jpg','Café Latte',35,'Café con leche vaporizada, creando una bebida cremosa y suave. Ideal para aquellos que prefieren un café menos fuerte.',3.75,1),(19,'/public/assets/img/productos/Cafés Especiales/capuccino.jpg','Cappuccino',25,'Café con leche y espuma, ofreciendo una textura ligera y una mezcla perfecta de café y leche.',3.80,1),(20,'/public/assets/img/productos/Cafés Especiales/flat-white.jpg','Flat White',20,'Café con leche microespumada, proporcionando una bebida cremosa con una capa suave de espuma. Un clásico australiano.',3.90,1),(21,'/public/assets/img/productos/Cafés con Leche/latte-vainilla.jpg','Latte Vainilla',30,'Café con leche y jarabe de vainilla, creando una mezcla dulce y aromática. Perfecto para los amantes de sabores suaves.',4.20,2),(22,'/public/assets/img/productos/Cafés con Leche/latte-caramelo.jpg','Latte Caramelo',25,'Café con leche y jarabe de caramelo, ofreciendo una combinación rica y dulce de café y caramelo.',4.30,2),(23,'/public/assets/img/productos/Cafés con Leche/latte-avellana.jpg','Latte Avellana',20,'Café con leche y jarabe de avellana, proporcionando un sabor delicado y a nuez que complementa el café.',4.40,2),(24,'/public/assets/img/productos/Cafés con Leche/vienés.jpg','Café Vienés',15,'Café con crema batida, creando una bebida lujosa y suave con una capa generosa de crema.',4.50,2),(25,'/public/assets/img/productos/Cafés con Leche/cortado.jpg','Café Cortado',10,'Café con un toque de leche, ofreciendo una bebida más equilibrada entre el café y la leche. Ideal para una pausa rápida.',4.60,2),(26,'/public/assets/img/productos/Cafés con Leche/macchiato.jpg','Café Macchiato',5,'Café con una pequeña cantidad de leche, para aquellos que prefieren un café fuerte con solo un toque de leche.',4.70,2),(27,'/public/assets/img/productos/Cafés Fríos/cafe-con-hielo.jpg','Café con Hielo',50,'Café frío con hielo, una opción refrescante para los días calurosos. Mantiene todo el sabor del café en una bebida fría.',3.00,3),(28,'/public/assets/img/productos/Cafés Fríos/latte-con-hielo.jpg','Latte con Hielo',40,'Café con leche fría, ideal para disfrutar de un latte refrescante con hielo en los días de calor.',3.50,3),(29,'/public/assets/img/productos/Cafés Fríos/mocha-con-hielo.jpg','Mocha con Hielo',30,'Café con chocolate y leche frío, combinado con hielo para una bebida dulce y refrescante.',4.00,3),(30,'/public/assets/img/productos/Cafés Fríos/coldbrew.jpg','Cold Brew',20,'Café frío de extracción lenta, ofreciendo un sabor suave y menos ácido. Ideal para los que prefieren un café más suave.',3.75,3),(31,'/public/assets/img/productos/Cafés Fríos/frappuccino.jpg','Frappuccino',25,'Café con hielo y crema batida, creando una bebida cremosa y dulce, perfecta para un capricho refrescante.',4.50,3),(32,'/public/assets/img/productos/Cafés Fríos/affogato.jpg','Affogato',15,'Café con helado de vainilla, combinando el sabor intenso del café con la dulzura del helado.',4.75,3),(33,'/public/assets/img/productos/Pasteles y Postres/cheesecake.jpg','Cheesecake',10,'Pastel de queso con base de galleta, ofreciendo una textura cremosa y un sabor dulce. Perfecto para los amantes de los postres.',5.00,4),(34,'/public/assets/img/productos/Pasteles y Postres/brownie.jpg','Brownie',15,'Pastel de chocolate denso, con un sabor rico y una textura húmeda. Ideal para quienes buscan un capricho de chocolate.',3.50,4),(35,'/public/assets/img/productos/Pasteles y Postres/tarta-de-manzana.jpg','Tarta de Manzana',20,'Pastel de manzana con canela, ofreciendo una mezcla cálida y especiada de manzana y canela. Un clásico reconfortante.',4.00,4),(36,'/public/assets/img/productos/Pasteles y Postres/muffin-de-arandano.jpg','Muffin de Arándanos',25,'Muffin con arándanos frescos, combinando el sabor ácido de los arándanos con una textura suave y esponjosa.',2.75,4),(37,'/public/assets/img/productos/Pasteles y Postres/croissant.jpg','Croissant',30,'Croissant de mantequilla, con una textura ligera y crujiente por fuera y suave por dentro. Perfecto para el desayuno.',2.50,4),(38,'/public/assets/img/productos/Pasteles y Postres/macarons.jpg','Macarons',35,'Galletas francesas de almendra, con una textura crujiente por fuera y suave por dentro, disponibles en varios sabores.',3.00,4),(39,'/public/assets/img/productos/te/te-verde.jpg','Té Verde',40,'Té verde orgánico, con un sabor suave y ligeramente herbáceo. Ideal para una opción de té ligera y saludable.',2.50,5),(40,'/public/assets/img/productos/te/te-negro.jpg','Té Negro',35,'Té negro clásico, con un sabor robusto y ligeramente astringente. Perfecto para comenzar el día con energía.',2.75,5),(41,'/public/assets/img/productos/te/te-de-manzanilla.jpg','Té de Manzanilla',30,'Té de manzanilla relajante, con un sabor suave y floral. Ideal para relajarse y promover el bienestar.',2.50,5),(42,'/public/assets/img/productos/te/te-de-menta.jpg','Té de Menta',25,'Té de menta refrescante, ofreciendo un sabor fresco y vigorizante. Perfecto para revitalizarse durante el día.',2.75,5),(43,'/public/assets/img/productos/te/te-chai.jpg','Té Chai',20,'Té chai con especias, combinando té negro con una mezcla aromática de especias. Ideal para quienes disfrutan de sabores intensos.',3.00,5),(44,'/public/assets/img/productos/te/te-oolong.jpg','Té Oolong',15,'Té oolong semifermentado, con un sabor único que mezcla notas de té verde y té negro. Perfecto para una experiencia de té equilibrada.',3.25,5),(45,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-de-pollo.jpg','Sandwich de Pollo',20,'Sandwich de pollo con mayonesa, ofreciendo un sabor jugoso y cremoso con pollo fresco. Ideal para una comida rápida y satisfactoria.',4.50,6),(46,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-de-jamon-y-queso.jpg','Sandwich de Jamón y Queso',25,'Sandwich clásico de jamón y queso, con una combinación simple pero deliciosa de jamón y queso en pan fresco.',4.00,6),(47,'/public/assets/img/productos/Sandwich y Bocadillos/bocadillo-de-atun.jpg','Bocadillo de Atún',30,'Bocadillo de atún con lechuga, ofreciendo una mezcla fresca y ligera de atún con lechuga crujiente en pan.',4.25,6),(48,'/public/assets/img/productos/Sandwich y Bocadillos/sandwich-club.jpg','Sandwich Club',35,'Sándwich con pavo, tocino, lechuga y tomate, creando una combinación sabrosa y abundante con capas de ingredientes frescos.',4.75,6),(49,'/public/assets/img/productos/Sandwich y Bocadillos/bagel-con-salmon.jpg','Bagel con Salmón',15,'Bagel con salmón ahumado y queso crema, ofreciendo una combinación lujosa de salmón y queso en un pan bagel recién horneado.',5.00,6),(50,'/public/assets/img/productos/Sandwich y Bocadillos/wrap-de-pavo.jpg','Wrap de Pavo',10,'Wrap de pavo con aguacate, proporcionando una opción ligera y saludable con pavo tierno y aguacate cremoso envuelto en una tortilla.',4.50,6);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `puesto`
--

LOCK TABLES `puesto` WRITE;
/*!40000 ALTER TABLE `puesto` DISABLE KEYS */;
INSERT INTO `puesto` VALUES (1,'Admin',5000.00),(2,'Gerente',4000.00),(3,'Chef',3000.00),(4,'Mozo',2000.00);
/*!40000 ALTER TABLE `puesto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `reserva`
--

LOCK TABLES `reserva` WRITE;
/*!40000 ALTER TABLE `reserva` DISABLE KEYS */;
INSERT INTO `reserva` VALUES (34,43,2,NULL,'2024-09-04 18:18:00','reservado',2,'MESA00cae3'),(38,46,3,NULL,'2024-09-07 12:13:00','reservado',2,'MESAaa76e7'),(48,35,1,NULL,'2024-09-12 17:01:00','ocupado',1,'MESAfa1afc'),(52,40,7,NULL,'2024-09-11 18:49:00','reservado',1,'MESAa6a675');
/*!40000 ALTER TABLE `reserva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `retroalimentacion`
--

LOCK TABLES `retroalimentacion` WRITE;
/*!40000 ALTER TABLE `retroalimentacion` DISABLE KEYS */;
INSERT INTO `retroalimentacion` VALUES (2,41,'Muy alto','tremenda obra maestra 20/10 y god'),(3,40,'Muy alto','Muy buena pagina! ');
/*!40000 ALTER TABLE `retroalimentacion` ENABLE KEYS */;
UNLOCK TABLES;

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

-- Dump completed on 2024-09-09 22:32:33
