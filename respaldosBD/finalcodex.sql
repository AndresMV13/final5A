-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: finalcodex
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `asistencia`
--

DROP TABLE IF EXISTS `asistencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asistencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `entrada` datetime NOT NULL,
  `salida` datetime DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencia`
--

LOCK TABLES `asistencia` WRITE;
/*!40000 ALTER TABLE `asistencia` DISABLE KEYS */;
INSERT INTO `asistencia` VALUES (1,3,'2025-02-19 07:53:11','2025-02-19 07:53:19','Marco','Sanchez','Lezama'),(2,3,'2025-02-26 08:08:27','2025-02-26 08:08:38','Marco','Sanchez','Lezama'),(3,3,'2025-02-27 23:20:52','2025-02-27 23:57:00','Marco','Sanchez','Lezama'),(4,3,'2025-02-28 00:00:16','2025-02-28 00:00:22','Marco','Sanchez','Lezama'),(5,3,'2025-03-17 17:14:04','2025-03-18 09:25:51','Marco','Sanchez','Lezama'),(6,3,'2025-03-18 09:27:42','2025-03-18 09:27:46','Marco','Sanchez','Lezama');
/*!40000 ALTER TABLE `asistencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calificacion`
--

DROP TABLE IF EXISTS `calificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calificacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_servicio` int(11) DEFAULT NULL,
  `calificacion` enum('1','2','3','4','5') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_servicio` (`id_servicio`),
  CONSTRAINT `calificacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  CONSTRAINT `calificacion_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calificacion`
--

LOCK TABLES `calificacion` WRITE;
/*!40000 ALTER TABLE `calificacion` DISABLE KEYS */;
INSERT INTO `calificacion` VALUES (1,2,1,'4');
/*!40000 ALTER TABLE `calificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calificacion_soporte`
--

DROP TABLE IF EXISTS `calificacion_soporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calificacion_soporte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p1` int(11) NOT NULL,
  `p2` int(11) NOT NULL,
  `p3` int(11) NOT NULL,
  `p4` int(11) NOT NULL,
  `p5` int(11) NOT NULL,
  `id_operador` int(11) NOT NULL,
  `numero_serie` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_operador` (`id_operador`),
  CONSTRAINT `calificacion_soporte_ibfk_1` FOREIGN KEY (`id_operador`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calificacion_soporte`
--

LOCK TABLES `calificacion_soporte` WRITE;
/*!40000 ALTER TABLE `calificacion_soporte` DISABLE KEYS */;
/*!40000 ALTER TABLE `calificacion_soporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ticket` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_ticket` (`id_ticket`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id`),
  CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensajes`
--

LOCK TABLES `mensajes` WRITE;
/*!40000 ALTER TABLE `mensajes` DISABLE KEYS */;
INSERT INTO `mensajes` VALUES (1,16,2,'No me llego el codigo','2025-03-12 13:39:10'),(2,16,3,'Wuola','2025-03-12 14:34:07'),(3,16,2,'ola kra debola','2025-03-14 01:54:02'),(4,16,2,'avr','2025-03-14 07:23:37'),(5,16,2,'Hola\n','2025-03-14 07:24:30'),(6,16,2,'Holiwis','2025-03-14 07:26:06'),(7,16,2,'HOla\n','2025-03-14 07:27:13'),(8,16,2,'Loa','2025-03-14 07:28:39'),(9,16,2,'sd','2025-03-14 07:28:59'),(10,16,2,'as','2025-03-14 07:34:50'),(11,16,2,'h','2025-03-14 07:37:53'),(12,16,2,'Esto deberia de funcionar','2025-03-14 07:41:35'),(13,16,2,'Que pasa','2025-03-14 14:30:07'),(14,16,2,'ayo\n','2025-03-16 23:18:03'),(15,16,2,'wuola','2025-03-16 23:29:49'),(16,16,2,'sd','2025-03-16 23:53:43'),(17,16,2,'dsd','2025-03-16 23:54:33'),(18,16,2,'Hola madafaker','2025-03-16 23:54:52'),(19,16,2,'sd','2025-03-16 23:55:28'),(20,16,2,'Hola madafakers','2025-03-16 23:56:34'),(21,16,2,'as','2025-03-16 23:57:37'),(22,16,2,'Hola wenas','2025-03-16 23:59:57'),(23,16,2,'Hola buenas','2025-03-17 00:02:13'),(24,16,2,'as','2025-03-17 00:06:09'),(25,16,2,'as','2025-03-17 00:06:47'),(26,16,2,'assasa','2025-03-17 00:10:00'),(27,16,2,'as','2025-03-17 00:10:21'),(28,16,2,'Hola cara de pelota','2025-03-17 00:11:30'),(29,16,2,'a','2025-03-17 00:11:42'),(30,16,2,'Funciona','2025-03-17 00:12:45'),(31,16,2,'Veamos si funciona todo el flujo','2025-03-17 00:13:25'),(32,16,2,'Ya funciona esta pendejada aaaaa','2025-03-17 00:13:58'),(33,16,2,'Hola','2025-03-17 00:27:30'),(34,16,2,'Veamos','2025-03-17 00:28:03'),(35,16,2,'hola','2025-03-17 00:30:54'),(36,16,2,'u','2025-03-17 00:32:29'),(37,16,2,'Holña','2025-03-17 00:33:20'),(38,16,2,'g','2025-03-17 00:35:37'),(39,16,2,'h','2025-03-17 00:39:58'),(40,16,2,'Holiwis','2025-03-17 00:41:57'),(41,16,2,'Jugo','2025-03-17 00:42:28'),(42,16,2,'as','2025-03-17 00:42:55'),(43,16,2,'as','2025-03-17 00:43:10'),(44,16,2,'Hola','2025-03-17 00:43:22'),(45,16,2,'h','2025-03-17 00:43:42'),(46,16,2,'si','2025-03-17 00:44:20'),(47,16,2,'Hola','2025-03-17 00:44:54'),(48,16,2,'Hola bro','2025-03-17 00:49:30'),(49,16,2,'Que pasa calabaza\n','2025-03-17 00:49:49'),(50,16,2,'Va lento no?','2025-03-17 00:50:01'),(51,16,2,'Ahora si','2025-03-17 00:50:30'),(52,16,2,'Tendriamos que mostar esto','2025-03-17 00:50:40'),(53,16,2,'Guita\n','2025-03-17 01:57:22'),(54,16,2,'Venni pibito','2025-03-17 01:57:39'),(55,16,2,'ojos','2025-03-17 01:57:44'),(56,16,2,'Loco tu forma de ser','2025-03-17 01:59:51'),(57,17,2,'No sirve w','2025-03-17 21:45:00'),(58,18,2,'Que rollo plebes','2025-03-17 21:47:53'),(59,16,2,'Veamos','2025-03-17 23:09:02'),(60,16,2,'Hola\n','2025-03-17 23:10:41'),(61,16,2,'Hey','2025-03-17 23:13:01'),(62,16,2,'Hola','2025-03-17 23:18:12'),(63,16,3,'Que tal','2025-03-17 23:18:18'),(64,16,2,'ayo','2025-03-18 00:32:27'),(65,16,2,'Apoco si?','2025-03-18 00:33:28'),(66,16,2,'Holaaa','2025-03-18 00:33:52'),(67,16,3,'Nembre ps no que no\n','2025-03-18 00:34:25'),(68,16,2,'apoco si','2025-03-18 00:35:35'),(69,16,2,'ey\n','2025-03-18 00:36:41'),(70,16,2,'sis','2025-03-18 00:39:59'),(71,16,2,'nemre','2025-03-18 00:40:35'),(72,16,2,'ds','2025-03-18 02:25:51'),(73,16,2,'Hola\n','2025-03-18 02:26:30'),(74,16,2,'aparece?','2025-03-18 02:28:34'),(75,16,2,'Si','2025-03-18 02:28:46'),(76,16,2,'veamos','2025-03-18 02:29:11'),(77,16,2,'Hola\n','2025-03-18 02:29:56'),(78,16,2,'Veamos','2025-03-18 02:30:01'),(79,16,2,'Hola','2025-03-18 02:34:22'),(80,16,2,'Apoco si?','2025-03-18 02:34:58'),(81,16,2,'sim','2025-03-18 02:35:29'),(82,16,2,'sis','2025-03-18 02:40:13'),(83,16,2,'ola','2025-03-18 02:43:02'),(84,16,3,'Mira que tal','2025-03-18 02:46:18'),(85,16,2,'asd\n','2025-03-18 02:46:34'),(86,16,2,'YA?','2025-03-18 02:50:41'),(87,16,2,'porfius','2025-03-18 02:52:05'),(88,16,2,'as','2025-03-18 02:53:42'),(89,16,2,'holaa','2025-03-18 02:54:27'),(90,16,3,'Hola','2025-03-18 05:41:42'),(91,16,2,'sda','2025-03-18 05:43:36'),(92,16,2,'Solo era esto?','2025-03-18 05:44:56'),(93,16,3,'Efectivamente\n','2025-03-18 05:45:12'),(94,16,2,'ya esta','2025-03-18 05:45:57'),(95,16,3,'apoco si?','2025-03-18 05:46:06'),(96,16,2,'Holiwis','2025-03-18 15:19:22'),(97,16,2,'Hola','2025-03-18 15:23:05'),(98,16,3,'Webito','2025-03-18 15:23:15'),(99,16,2,'Ya funciona','2025-03-18 15:23:23'),(100,16,3,'Como?','2025-03-18 15:23:32'),(101,16,2,'Hola','2025-03-18 15:24:27');
/*!40000 ALTER TABLE `mensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_rol` int(11) NOT NULL,
  `id_seccion` int(11) NOT NULL,
  `estatus` enum('inactivo','activo') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_seccion` (`id_seccion`),
  KEY `permisos_ibfk_1` (`id_rol`),
  CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`id_seccion`) REFERENCES `seccion` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (1,1,1,'activo'),(5,1,2,'activo'),(6,4,2,'activo'),(7,1,3,'activo'),(8,4,1,'inactivo'),(9,2,2,'activo'),(10,2,3,'activo'),(11,2,1,'inactivo'),(12,4,3,'activo'),(13,8,1,'activo'),(14,8,2,'activo'),(15,8,3,'activo');
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `respaldo`
--

DROP TABLE IF EXISTS `respaldo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `respaldo` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `respaldo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `respaldo`
--

LOCK TABLES `respaldo` WRITE;
/*!40000 ALTER TABLE `respaldo` DISABLE KEYS */;
/*!40000 ALTER TABLE `respaldo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Administrador'),(2,'Operador'),(3,'Cliente'),(4,'Supervisor'),(8,'Prueba');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seccion`
--

DROP TABLE IF EXISTS `seccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seccion`
--

LOCK TABLES `seccion` WRITE;
/*!40000 ALTER TABLE `seccion` DISABLE KEYS */;
INSERT INTO `seccion` VALUES (1,'roles'),(2,'asistencias'),(3,'servicio');
/*!40000 ALTER TABLE `seccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio`
--

DROP TABLE IF EXISTS `servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio`
--

LOCK TABLES `servicio` WRITE;
/*!40000 ALTER TABLE `servicio` DISABLE KEYS */;
INSERT INTO `servicio` VALUES (1,'Xbox Game Pass'),(2,'Playstation Plus'),(3,'Nintendo Online');
/*!40000 ALTER TABLE `servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_operador` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `n_serie` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('abierto','en progreso','cerrado') DEFAULT 'abierto',
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_operador` (`id_operador`),
  KEY `id_servicio` (`id_servicio`),
  CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `usuario` (`id`),
  CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`id_operador`) REFERENCES `usuario` (`id`),
  CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (16,2,3,2,'TKT20250312N1','No me llego el codigo','2025-03-12 13:39:10','abierto'),(17,2,24,1,'TKT20250317N17','No sirve w','2025-03-17 21:45:00','cerrado'),(18,2,3,2,'TKT20250317N18','Que rollo plebes','2025-03-17 21:47:53','abierto');
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(70) NOT NULL,
  `apellido_paterno` varchar(70) NOT NULL,
  `apellido_materno` varchar(70) NOT NULL,
  `correo` varchar(60) NOT NULL,
  `password` varchar(350) NOT NULL,
  `salt` varchar(50) NOT NULL,
  `status` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  KEY `id_rol` (`id_rol`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,1,'Andres','Mendez','Vicuña','firebreak21@gmail.com','5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc56f286538-eccc-11ef-8ee1-cc4740269fcc','6f286538-eccc-11ef-8ee1-cc4740269fcc','activo'),(2,3,'Diego','Juarez','Rivera','dieguito@gmail.com','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3e9a9ba5f-ed3d-11ef-8f32-cc4740269fcc','e9a9ba5f-ed3d-11ef-8f32-cc4740269fcc','activo'),(3,2,'Marco','Sanchez','Lezama','markitos@gmail.com','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3c437b98c-ee05-11ef-8685-cc4740269fcc','c437b98c-ee05-11ef-8685-cc4740269fcc','activo'),(21,3,'Yoset','Bello','Perez','yoshebello@gmail.com','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae31374e1ac-f966-11ef-ad89-cc4740269fcc','1374e1ac-f966-11ef-ad89-cc4740269fcc','activo'),(22,4,'Ana','Aguirre','Ruiz','anaa@gmail.com','5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5056d8d43-f980-11ef-ad89-cc4740269fcc','056d8d43-f980-11ef-ad89-cc4740269fcc','activo'),(23,3,'Fer','Nava','Reyes','fernr@gmail.com','5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5a57ef9ad-f9f3-11ef-ad89-cc4740269fcc','a57ef9ad-f9f3-11ef-ad89-cc4740269fcc','activo'),(24,2,'Humberto','Ramos','Cuate','humbertitobb@gmail.com','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3f17a6ac3-ff10-11ef-b055-cc4740269fcc','f17a6ac3-ff10-11ef-b055-cc4740269fcc','activo'),(25,3,'Pablo','Mendez','Hernandez','pablito@gmail.com','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3c95fa7c7-02d3-11f0-add0-cc4740269fcc','c95fa7c7-02d3-11f0-add0-cc4740269fcc','activo'),(26,8,'Manuel','Mendez','Montero ','manuelito@gmail.com','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae306c7a3bf-040e-11f0-add0-cc4740269fcc','06c7a3bf-040e-11f0-add0-cc4740269fcc','activo');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_servicio`
--

DROP TABLE IF EXISTS `usuario_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_servicio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_servicio` int(11) DEFAULT NULL,
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_servicio` (`id_servicio`),
  CONSTRAINT `usuario_servicio_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usuario_servicio_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_servicio`
--

LOCK TABLES `usuario_servicio` WRITE;
/*!40000 ALTER TABLE `usuario_servicio` DISABLE KEYS */;
INSERT INTO `usuario_servicio` VALUES (1,2,1,'activo'),(2,2,2,'activo'),(3,2,3,'activo');
/*!40000 ALTER TABLE `usuario_servicio` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-18 13:54:52
