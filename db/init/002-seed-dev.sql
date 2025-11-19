-- MySQL dump 10.13  Distrib 8.0.44, for macos15 (arm64)
--
-- Host: 127.0.0.1    Database: mydb
-- ------------------------------------------------------
-- Server version	8.0.44

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
-- Dumping data for table `BookingHolding`
--

LOCK TABLES `BookingHolding` WRITE;
/*!40000 ALTER TABLE `BookingHolding` DISABLE KEYS */;
INSERT INTO `BookingHolding` VALUES (1,'2025-12-31','20:00:00','sess_sample_id_123','2025-11-16 23:59:59',2);
/*!40000 ALTER TABLE `BookingHolding` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Bookings`
--

LOCK TABLES `Bookings` WRITE;
/*!40000 ALTER TABLE `Bookings` DISABLE KEYS */;
INSERT INTO `Bookings` VALUES (1,'2025-12-25','18:00:00',50.00,'Confirmed','2025-11-16 12:00:00',1,1);
/*!40000 ALTER TABLE `Bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Reviews`
--

LOCK TABLES `Reviews` WRITE;
/*!40000 ALTER TABLE `Reviews` DISABLE KEYS */;
INSERT INTO `Reviews` VALUES (1,5,'Love this! Me and my wife had a great time!','2025-11-16 16:56:51',1,1);
/*!40000 ALTER TABLE `Reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Rooms`
--

LOCK TABLES `Rooms` WRITE;
/*!40000 ALTER TABLE `Rooms` DISABLE KEYS */;
INSERT INTO `Rooms` 
(
    roomID, roomName, roomDescription, roomMax, roomMin, roomDuration, 
    roomDifficulty, roomLocation, roomFearLevel, roomExperienceType, 
    roomGenre, roomPricePeak, roomPriceOffpeak, imagePath
)
VALUES 
(1,'The Cursed Cabin','A cabin in the woods. Nothing can go wrong.',6,2,60,'Hard','Main Street','Very Scary','Live Actor','Horror',50.00,40.00,'images/test.png'),
(2,'Asylum','A creepy asylum.',8,4,90,'Medium','Uptown','Scary','No Live Actor','Thriller',60.00,50.00,'images/test.png');
/*!40000 ALTER TABLE `Rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,'victim_1','test@example.com','some_hashed_password','2025-11-12 16:28:58',0),(4,'admin_user','admin@myhorror.com','your_secure_hashed_password','2025-11-12 16:56:51',1),(5,'tester','test@yourhorror.com','another_hashed_password','2025-11-12 17:03:17',0),(6,'test_user_cas','cascade@example.com','some_hash','2025-11-12 17:04:24',0);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-13  1:14:46
