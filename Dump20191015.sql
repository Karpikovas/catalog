-- MySQL dump 10.13  Distrib 8.0.17, for Win64 (x86_64)
--
-- Host: localhost    Database: catalog
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Employee`
--

DROP TABLE IF EXISTS `Employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surname` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `patronymic` varchar(45) DEFAULT NULL,
  `birthday` datetime DEFAULT NULL,
  `photo` varchar(45) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Employee`
--

LOCK TABLES `Employee` WRITE;
/*!40000 ALTER TABLE `Employee` DISABLE KEYS */;
INSERT INTO `Employee` VALUES (2,'Петров','Петр','Петрович','1978-11-11 00:00:00','5da4851d2290ephpjkJZ3G.jpg',78000,1.00),(3,'Сидоров','Сидр','Сидорович','1990-09-09 00:00:00','5da068d5df950phpj7HB74.jpg',45000,1.00),(4,'Макееваf','Дарья','Сергеевна','1999-10-03 00:00:00',NULL,34000,0.50),(6,'Кожухова','Ольга','Андреевна','2000-10-10 00:00:00','5da48930e2f93phpWzucJ9.jpg',34000,0.10),(7,'Авдеева','Мария','Александровна','1977-10-10 00:00:00','5da064b8d21a8phpLClVSt.jpg',56000,1.00),(8,'Романов','Роман','Романович','1997-11-11 00:00:00',NULL,34000,1.00),(9,'Лукьянов','Максим','Максимович','1990-02-01 00:00:00','5da4864a25a51phpqLzOnH.jpg',23000,0.50),(10,'Аршинова','Анна','Ивановна','1999-06-02 00:00:00','5da4895d64294phpvmmp7O.jpg',34000,0.75);
/*!40000 ALTER TABLE `Employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EmployeeHasPost`
--

DROP TABLE IF EXISTS `EmployeeHasPost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `EmployeeHasPost` (
  `id_employee` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  PRIMARY KEY (`id_employee`,`id_post`),
  KEY `id_post_idx` (`id_post`),
  CONSTRAINT `id` FOREIGN KEY (`id_employee`) REFERENCES `Employee` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_post` FOREIGN KEY (`id_post`) REFERENCES `Post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EmployeeHasPost`
--

LOCK TABLES `EmployeeHasPost` WRITE;
/*!40000 ALTER TABLE `EmployeeHasPost` DISABLE KEYS */;
INSERT INTO `EmployeeHasPost` VALUES (2,1),(6,1),(3,3),(7,3),(4,4),(8,4),(9,5),(10,5);
/*!40000 ALTER TABLE `EmployeeHasPost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EmployeeInSubdivision`
--

DROP TABLE IF EXISTS `EmployeeInSubdivision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `EmployeeInSubdivision` (
  `id_employee` int(11) NOT NULL,
  `id_subdivison` int(11) NOT NULL,
  PRIMARY KEY (`id_employee`,`id_subdivison`),
  KEY `id_subdivision_idx` (`id_subdivison`),
  CONSTRAINT `id_employe` FOREIGN KEY (`id_employee`) REFERENCES `Employee` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_subdivision` FOREIGN KEY (`id_subdivison`) REFERENCES `Subdivision` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EmployeeInSubdivision`
--

LOCK TABLES `EmployeeInSubdivision` WRITE;
/*!40000 ALTER TABLE `EmployeeInSubdivision` DISABLE KEYS */;
INSERT INTO `EmployeeInSubdivision` VALUES (2,1),(6,1),(3,3),(7,3),(9,3),(4,4),(8,4),(10,4);
/*!40000 ALTER TABLE `EmployeeInSubdivision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Post`
--

DROP TABLE IF EXISTS `Post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Post`
--

LOCK TABLES `Post` WRITE;
/*!40000 ALTER TABLE `Post` DISABLE KEYS */;
INSERT INTO `Post` VALUES (1,'Директор'),(2,'Руководитель проекта'),(3,'Старший специалист'),(4,'Специалист'),(5,'Оператор');
/*!40000 ALTER TABLE `Post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Subdivision`
--

DROP TABLE IF EXISTS `Subdivision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Subdivision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Subdivision`
--

LOCK TABLES `Subdivision` WRITE;
/*!40000 ALTER TABLE `Subdivision` DISABLE KEYS */;
INSERT INTO `Subdivision` VALUES (1,'Центральное','Главное подразделение'),(2,'Северное','Тут очень холодно'),(3,'Южное','Тут очень жарко'),(4,'Западное','Тут всё плохо'),(5,'Восточное','Тут еще хуже');
/*!40000 ALTER TABLE `Subdivision` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-15 13:59:27
