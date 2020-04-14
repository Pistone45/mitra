-- MariaDB dump 10.17  Distrib 10.4.10-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: mitra
-- ------------------------------------------------------
-- Server version	10.4.10-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'IBM'),(2,'VM Ware');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news` text NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `users_username` varchar(255) NOT NULL,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_news_users_idx` (`users_username`),
  CONSTRAINT `fk_news_users` FOREIGN KEY (`users_username`) REFERENCES `users` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd','this is a title','../news/blog_2.jpg','admin','2020-04-04 06:55:00'),(2,'dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd2222','this is a title','../news/blog_3.jpg','admin','2020-04-04 09:51:00'),(3,'dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd','titiel','../news/blog_3.jpg','admin','2020-04-04 06:55:00'),(4,'dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd','ddddddd','../news/blog_3.jpg','admin','2020-04-04 06:55:00'),(5,'dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd','ddddd','../news/blog_3.jpg','admin','2020-04-04 06:55:00');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service` varchar(45) DEFAULT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_services_category1_idx` (`category_id`),
  CONSTRAINT `fk_services_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'IBM AIX','The Unix flagship Operating System offered by IBM on itï¿½s Power Servers. Enterprises need infrastructure that is secure, highly available and adaptable to meet changing business demands. AIX operating system delivers these capabilities and more, with the performance, reliability and security your mission-critical data requires.',1,'../img/logo.png'),(2,'IBM QRADAR','an enterprise security information and event management (SIEM) product. It collects log data from an enterprise, its network devices, host assets and operating systems, applications, vulnerabilities, and user activities and behaviours',1,'../img/test.jpg'),(3,'IBM SPECTRUM PROTECT','Data protection platform that gives enterprises a single point of control and administration for backup and recovery. It is the flagship product in the IBM Spectrum Protect family. It enables backups and recovery for virtual, physical and cloud environments of all sizes.',1,'../img/test.jpg'),(4,'SAP HANA','An in-memory, column-oriented, relational database management system developed and marketed by SAP SE. Its primary function as a database server is to store and retrieve data as requested by the applications',1,'../img/test.jpg'),(5,'IBM HACMP','Solution for high-availability clusters on the AIX Unix and Linux for IBM System p platforms, stands for High Availability Cluster Multiprocessing. High availability solution ensure that the failure of any component of the solution does not cause the application and its data to be unavailable to the user community. This is achieved through the elimination, or masking, of both planned and unplanned downtime by eliminating single points of failure.',1,'../img/test.jpg'),(6,'IBM XGS','We provide next-generation intrusion prevention functions for 10Gb and 1Gb Ethernet networks. ... Help protect networks, servers, desktops, and business critical applications from malicious threats. Conserve network bandwidth and provide insight into what users are doing on the corporate network.',1,'../img/test.jpg'),(7,'IBM DB2','Relational database that delivers advanced data management and analytic capabilities for your transactional and warehousing workloads. This operational database is designed to deliver high performance, actionable insights, data availability and reliability, and it is supported across Linux, Unix and Windows operating systems.',1,'../img/test.jpg'),(8,'IBM WAS','WebSphere Application Server is a software product that performs the role of a web application server. More specifically, it is a software framework and middleware that hosts Java-based web applications. It is the flagship product within IBM\'s WebSphere software suite.',1,'../img/test.jpg'),(9,'Veeam Backup & Replication','Powerful, easy-to-use, and affordable backup and availability solution that supports VMware vSphere virtual environments. It provides fast, flexible, and reliable recovery of virtualized applications and data, bringing VM backup and replication together in a single software solution.',2,'../img/test.jpg'),(10,'Dell, HP and Fujitsu','Mitra Systems provides hardware support on all Intel based servers, covering but not limited to Lenovo, Dell, HP and Fujitsu.',2,'../img/test.jpg'),(11,'Lenovo','Mitra Systems is a registered Reseller of Lenovo servers, storage, laptops and personal computers.',2,'../img/13.jpg');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `username` varchar(255) NOT NULL,
  `firstname` varchar(45) DEFAULT NULL,
  `middlename` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('admin','admin','admin','admin','08888888','admin','$2y$10$ZOi0vNoQv8QnVVlXmPLC5O2IqBkT2euMe74MDiGaMh6d4.lW.1cua\n'),('pistonsanjama45@gmail.com','Pistone','Junior','Sanjama','0882550227','pistonsanjama45@gmail.com','$2y$10$975U6L2SGapFYUlMLPiLweRNgX7ZenqEhvX/R3FYTOmN0ikcgTjPe\n'),('test@gmail.com','test','test','test','9999','test@gmail.com','$2y$10$9MF3lRWPmdnmkYglikDLn.Rna1jXmKHm4vNau2CX0NjXFWqvsx.Ha\n');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-14 10:10:03
