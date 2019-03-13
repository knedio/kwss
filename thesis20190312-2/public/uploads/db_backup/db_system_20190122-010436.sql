-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: db_system
-- ------------------------------------------------------
-- Server version 	5.5.5-10.1.30-MariaDB
-- Date: Tue, 22 Jan 2019 01:04:36 +0800

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
-- Table structure for table `tbl_account`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_account` (
  `username` int(10) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `account_type` char(25) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_account`
--

LOCK TABLES `tbl_account` WRITE;
/*!40000 ALTER TABLE `tbl_account` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_account` VALUES (35,'123','Admin'),(36,'123','Customer'),(37,'123','Customer');
/*!40000 ALTER TABLE `tbl_account` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_account` with 3 row(s)
--

--
-- Table structure for table `tbl_customer`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_customer` (
  `cus_id` int(10) NOT NULL AUTO_INCREMENT,
  `username` int(10) NOT NULL,
  `cus_firstname` char(20) NOT NULL,
  `cus_lastname` char(20) NOT NULL,
  `cus_mobile_number` varchar(15) NOT NULL,
  `cus_address` varchar(30) NOT NULL,
  `cus_zone` varchar(255) DEFAULT NULL,
  `cus_total_water_consumed` int(11) DEFAULT '0',
  PRIMARY KEY (`cus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_customer`
--

LOCK TABLES `tbl_customer` WRITE;
/*!40000 ALTER TABLE `tbl_customer` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_customer` VALUES (1,36,'Test','Customer','9652354567','Tests','Zone Test',0),(2,37,'Test','Test','9652354567','CDO',NULL,34);
/*!40000 ALTER TABLE `tbl_customer` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_customer` with 2 row(s)
--

--
-- Table structure for table `tbl_customer_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_customer_type` (
  `custype_id` int(10) NOT NULL AUTO_INCREMENT,
  `custype_type` char(15) NOT NULL,
  `custype_cubic_meter_rate` int(11) DEFAULT NULL,
  `custype_min_cubic_meter` int(11) DEFAULT NULL,
  `custype_min_peso_rate` int(11) DEFAULT NULL,
  `custype_due_date_duration` varchar(255) DEFAULT 'Monthly',
  `custype_due_date_penalty` float DEFAULT '0',
  PRIMARY KEY (`custype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_customer_type`
--

LOCK TABLES `tbl_customer_type` WRITE;
/*!40000 ALTER TABLE `tbl_customer_type` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_customer_type` VALUES (4,'Residential',14,8,70,'Monthly',0.05),(5,'Commercial',14,8,80,'Monthly',0.1);
/*!40000 ALTER TABLE `tbl_customer_type` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_customer_type` with 2 row(s)
--

--
-- Table structure for table `tbl_employee`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_employee` (
  `emp_id` int(10) NOT NULL AUTO_INCREMENT,
  `username` int(10) NOT NULL,
  `emp_firstname` char(20) NOT NULL,
  `emp_lastname` char(20) NOT NULL,
  `emp_mobile_number` varchar(15) NOT NULL,
  `emp_address` varchar(30) NOT NULL,
  PRIMARY KEY (`emp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_employee`
--

LOCK TABLES `tbl_employee` WRITE;
/*!40000 ALTER TABLE `tbl_employee` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_employee` VALUES (14,35,'Admintesttest','Admin','9652354567','CDO');
/*!40000 ALTER TABLE `tbl_employee` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_employee` with 1 row(s)
--

--
-- Table structure for table `tbl_meter`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_meter` (
  `meter_id` int(10) NOT NULL AUTO_INCREMENT,
  `cus_id` int(10) NOT NULL,
  `custype_id` int(10) NOT NULL,
  `meter_serial_no` varchar(255) DEFAULT NULL,
  `meter_model` varchar(15) NOT NULL,
  `meter_duedate` datetime NOT NULL,
  `meter_address` varchar(30) NOT NULL,
  PRIMARY KEY (`meter_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_meter`
--

LOCK TABLES `tbl_meter` WRITE;
/*!40000 ALTER TABLE `tbl_meter` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_meter` VALUES (1,1,4,'C2-3','CA-12','2019-05-23 00:00:00','Near Gate'),(2,2,4,'C-1','M-1','2019-01-19 00:00:00','Near Gate'),(3,2,5,'C-2','M-2','2019-01-19 00:00:00','Near Gate2'),(4,2,4,'M-3','M-3','2019-01-19 00:00:00','Near Gate');
/*!40000 ALTER TABLE `tbl_meter` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_meter` with 4 row(s)
--

--
-- Table structure for table `tbl_meter_reading`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_meter_reading` (
  `reading_id` int(10) NOT NULL AUTO_INCREMENT,
  `reader_id` int(10) NOT NULL,
  `meter_id` int(10) DEFAULT NULL,
  `reading_date` datetime DEFAULT NULL,
  `reading_prev_waterconsumed` int(11) DEFAULT NULL,
  `reading_waterconsumed` varchar(5) DEFAULT NULL,
  `reading_amount` double(10,3) DEFAULT NULL,
  `reading_other_payment` decimal(10,3) DEFAULT '0.000',
  `reading_status` varchar(20) DEFAULT NULL,
  `reading_remarks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`reading_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_meter_reading`
--

LOCK TABLES `tbl_meter_reading` WRITE;
/*!40000 ALTER TABLE `tbl_meter_reading` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_meter_reading` VALUES (1,1,2,'2018-12-05 00:00:00',0,'11',112.000,100.000,'Read',NULL),(2,1,4,'2018-12-05 00:00:00',11,'23',126.000,200.000,'Read',NULL),(3,1,3,'2018-12-05 00:00:00',34,'11',290.000,100.000,'Read',NULL);
/*!40000 ALTER TABLE `tbl_meter_reading` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_meter_reading` with 3 row(s)
--

--
-- Table structure for table `tbl_payment`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_payment` (
  `pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) NOT NULL,
  `cus_id` int(11) NOT NULL,
  `pay_status` char(7) NOT NULL,
  PRIMARY KEY (`pay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_payment`
--

LOCK TABLES `tbl_payment` WRITE;
/*!40000 ALTER TABLE `tbl_payment` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_payment` VALUES (1,14,2,'Full'),(2,14,2,'Partial');
/*!40000 ALTER TABLE `tbl_payment` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_payment` with 2 row(s)
--

--
-- Table structure for table `tbl_payment_details`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_payment_details` (
  `trans_id` int(10) NOT NULL AUTO_INCREMENT,
  `pay_id` int(10) DEFAULT NULL,
  `reading_id` int(10) NOT NULL,
  `trans_payment` float NOT NULL,
  `trans_date` datetime DEFAULT NULL,
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_payment_details`
--

LOCK TABLES `tbl_payment_details` WRITE;
/*!40000 ALTER TABLE `tbl_payment_details` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_payment_details` VALUES (1,1,1,10,'2019-01-20 00:11:23'),(2,1,1,99,'2019-01-20 00:11:39'),(3,1,1,106,'2019-01-20 19:45:04'),(4,2,2,12,'2019-01-21 01:16:45'),(5,1,1,123,'2019-01-21 01:18:09'),(6,2,2,1,'2019-01-21 01:18:54'),(7,2,2,1,'2019-01-21 01:19:02'),(8,2,2,1,'2019-01-21 01:19:32'),(9,2,2,1,'2019-01-21 01:19:38'),(10,2,2,1,'2019-01-21 01:22:44');
/*!40000 ALTER TABLE `tbl_payment_details` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_payment_details` with 10 row(s)
--

--
-- Table structure for table `tbl_request`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_request` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `cus_id` int(11) DEFAULT NULL,
  `request_prev_data_serialized` varchar(255) DEFAULT NULL,
  `request_data_serialized` varchar(255) DEFAULT NULL,
  `request_type` enum('Name','Address') DEFAULT NULL,
  `request_status` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `request_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_request`
--

LOCK TABLES `tbl_request` WRITE;
/*!40000 ALTER TABLE `tbl_request` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_request` VALUES (2,1,'a:2:{s:10:\"first name\";s:8:\"Customer\";s:9:\"last name\";s:9:\"Dela Cruz\";}','a:2:{s:10:\"first name\";s:4:\"Test\";s:9:\"last name\";s:8:\"Customer\";}','Name','Declined','2019-01-19 09:17:10'),(3,1,'a:2:{s:7:\"address\";s:3:\"CDO\";s:4:\"zone\";N;}','a:2:{s:7:\"address\";s:5:\"Tests\";s:4:\"zone\";s:9:\"Zone Test\";}','Address','Approved','2019-01-19 09:20:04'),(4,2,'a:2:{s:10:\"first name\";s:4:\"Test\";s:9:\"last name\";s:4:\"test\";}','a:2:{s:10:\"first name\";N;s:9:\"last name\";N;}','Name','Pending','2019-01-20 14:13:35'),(5,2,'a:2:{s:7:\"address\";s:3:\"CDO\";s:4:\"zone\";N;}','a:2:{s:7:\"address\";s:4:\"Test\";s:4:\"zone\";s:4:\"test\";}','Address','Pending','2019-01-20 14:19:27');
/*!40000 ALTER TABLE `tbl_request` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_request` with 4 row(s)
--

--
-- Table structure for table `tbl_water_reader`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_water_reader` (
  `reader_id` int(10) NOT NULL AUTO_INCREMENT,
  `reader_firstname` char(20) NOT NULL,
  `reader_lastname` char(20) NOT NULL,
  `reader_address` varchar(30) NOT NULL,
  `reader_mobile_number` varchar(13) NOT NULL,
  PRIMARY KEY (`reader_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_water_reader`
--

LOCK TABLES `tbl_water_reader` WRITE;
/*!40000 ALTER TABLE `tbl_water_reader` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_water_reader` VALUES (1,'Reader','Dela Cruz','CDO','9652354567');
/*!40000 ALTER TABLE `tbl_water_reader` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_water_reader` with 1 row(s)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Tue, 22 Jan 2019 01:04:37 +0800
