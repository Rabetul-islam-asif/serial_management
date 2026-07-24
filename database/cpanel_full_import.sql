-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: doctor_serial
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
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `chamber_id` bigint(20) unsigned NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_type` enum('walkin','appointment','emergency','vip','followup') NOT NULL DEFAULT 'appointment',
  `status` enum('booked','confirmed','cancelled','completed','no_show') NOT NULL DEFAULT 'booked',
  `notes` text DEFAULT NULL,
  `booked_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `chamber_id` (`chamber_id`),
  KEY `booked_by` (`booked_by`),
  KEY `idx_app_date_chamber` (`appointment_date`,`chamber_id`),
  CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`booked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (11,12,1,'2026-07-18','walkin','booked','Live Mock Appointment',1,'2026-07-18 16:50:10','2026-07-18 16:50:10'),(12,13,1,'2026-07-18','followup','booked','Live Mock Appointment',1,'2026-07-18 16:50:10','2026-07-18 16:50:10'),(13,14,1,'2026-07-18','vip','booked','Live Mock Appointment',1,'2026-07-18 16:50:10','2026-07-18 16:50:10'),(14,15,1,'2026-07-18','emergency','booked','Live Mock Appointment',1,'2026-07-18 16:50:10','2026-07-18 16:50:10'),(15,16,1,'2026-07-18','walkin','booked','Live Mock Appointment',1,'2026-07-18 16:50:10','2026-07-18 16:50:10'),(16,12,1,'2026-07-22','walkin','booked','Live Mock Appointment',1,'2026-07-22 21:44:56','2026-07-22 21:44:56'),(17,13,1,'2026-07-22','followup','booked','Live Mock Appointment',1,'2026-07-22 21:44:56','2026-07-22 21:44:56'),(18,14,1,'2026-07-22','vip','booked','Live Mock Appointment',1,'2026-07-22 21:44:56','2026-07-22 21:44:56'),(19,15,1,'2026-07-22','emergency','booked','Live Mock Appointment',1,'2026-07-22 21:44:56','2026-07-22 21:44:56'),(20,16,1,'2026-07-22','walkin','booked','Live Mock Appointment',1,'2026-07-22 21:44:56','2026-07-22 21:44:56'),(21,12,1,'2026-07-23','walkin','booked','Live Mock Appointment',1,'2026-07-23 20:48:10','2026-07-23 20:48:10'),(22,13,1,'2026-07-23','followup','booked','Live Mock Appointment',1,'2026-07-23 20:48:10','2026-07-23 20:48:10'),(23,14,1,'2026-07-23','vip','booked','Live Mock Appointment',1,'2026-07-23 20:48:10','2026-07-23 20:48:10'),(24,15,1,'2026-07-23','emergency','booked','Live Mock Appointment',1,'2026-07-23 20:48:10','2026-07-23 20:48:10'),(25,16,1,'2026-07-23','walkin','booked','Live Mock Appointment',1,'2026-07-23 20:48:10','2026-07-23 20:48:10');
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `entity_type` varchar(100) NOT NULL,
  `entity_id` bigint(20) unsigned DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_audit_logs_created` (`created_at`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'User Login','auth',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64)','2026-07-18 14:36:08'),(2,1,'Chamber Status Update','chambers',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64)','2026-07-18 14:36:08'),(3,2,'Create Serial #01','serials',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64)','2026-07-18 14:36:08'),(4,2,'Create Serial #02','serials',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64)','2026-07-18 14:36:08'),(5,2,'Create Serial #03','serials',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64)','2026-07-18 14:36:08'),(6,1,'Completed Consultation #01','visits',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64)','2026-07-18 14:36:08'),(7,1,'Generated Prescription RX-2026-0001','prescriptions',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64)','2026-07-18 14:36:08'),(8,1,'Completed Consultation #02','visits',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64)','2026-07-18 14:36:08'),(9,1,'Completed Consultation #03','visits',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64)','2026-07-18 14:36:08');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chamber_schedules`
--

DROP TABLE IF EXISTS `chamber_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chamber_schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chamber_id` bigint(20) unsigned NOT NULL,
  `day_of_week` tinyint(4) NOT NULL COMMENT '1=Sunday, 2=Monday, ..., 7=Saturday',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `max_patients` int(11) NOT NULL DEFAULT 30,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_chamber_day_time` (`chamber_id`,`day_of_week`,`start_time`),
  CONSTRAINT `chamber_schedules_ibfk_1` FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chamber_schedules`
--

LOCK TABLES `chamber_schedules` WRITE;
/*!40000 ALTER TABLE `chamber_schedules` DISABLE KEYS */;
INSERT INTO `chamber_schedules` VALUES (1,1,1,'17:00:00','21:00:00',30,1,'2026-07-17 00:43:20','2026-07-23 21:58:57'),(2,1,2,'17:00:00','21:00:00',30,1,'2026-07-17 00:43:20','2026-07-23 21:58:57'),(3,1,3,'17:00:00','21:00:00',30,1,'2026-07-17 00:43:20','2026-07-23 21:58:57'),(4,1,4,'17:00:00','21:00:00',30,1,'2026-07-17 00:43:20','2026-07-23 21:58:57'),(5,1,5,'17:00:00','21:00:00',30,1,'2026-07-17 00:43:20','2026-07-23 21:58:57'),(11,1,6,'17:00:00','21:00:00',30,1,'2026-07-23 21:58:30','2026-07-23 21:58:57'),(12,1,7,'17:00:00','21:00:00',30,1,'2026-07-23 21:58:30','2026-07-23 21:58:57');
/*!40000 ALTER TABLE `chamber_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chambers`
--

DROP TABLE IF EXISTS `chambers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chambers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `google_map_url` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `doctor_id` (`doctor_id`),
  CONSTRAINT `chambers_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chambers`
--

LOCK TABLES `chambers` WRITE;
/*!40000 ALTER TABLE `chambers` DISABLE KEYS */;
INSERT INTO `chambers` VALUES (1,1,'Metro Heart Chamber','House-42, Road-11, Dhanmondi, Dhaka - 1209','01912345678','https://maps.google.com/?q=Dhanmondi+Dhaka',1,1,'2026-07-17 00:43:20','2026-07-17 00:43:20');
/*!40000 ALTER TABLE `chambers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_awards`
--

DROP TABLE IF EXISTS `doctor_awards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor_awards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `doctor_id` (`doctor_id`),
  CONSTRAINT `doctor_awards_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_awards`
--

LOCK TABLES `doctor_awards` WRITE;
/*!40000 ALTER TABLE `doctor_awards` DISABLE KEYS */;
INSERT INTO `doctor_awards` VALUES (1,1,'Best Young Cardiologist Award',2020,'Awarded by the Bangladesh Cardiac Society for outstanding clinical contribution.',1),(2,1,'Gold Medal in FCPS Examination',2015,'Highest marks in the FCPS Medicine final examination.',2),(3,1,'Research Excellence Award',2022,'For published research in interventional cardiology in international journals.',3);
/*!40000 ALTER TABLE `doctor_awards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_education`
--

DROP TABLE IF EXISTS `doctor_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor_education` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `degree` varchar(255) NOT NULL,
  `institution` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `doctor_id` (`doctor_id`),
  CONSTRAINT `doctor_education_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_education`
--

LOCK TABLES `doctor_education` WRITE;
/*!40000 ALTER TABLE `doctor_education` DISABLE KEYS */;
INSERT INTO `doctor_education` VALUES (1,1,'MBBS','Dhaka Medical College',2010,1),(2,1,'FCPS (Medicine)','Bangladesh College of Physicians & Surgeons',2015,2),(3,1,'MD (Cardiology)','National Heart Foundation & Research Institute',2018,3);
/*!40000 ALTER TABLE `doctor_education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_gallery`
--

DROP TABLE IF EXISTS `doctor_gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor_gallery` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `doctor_id` (`doctor_id`),
  CONSTRAINT `doctor_gallery_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_gallery`
--

LOCK TABLES `doctor_gallery` WRITE;
/*!40000 ALTER TABLE `doctor_gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctor_gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_profile`
--

DROP TABLE IF EXISTS `doctor_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor_profile` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `degree` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `bmdc_number` varchar(50) NOT NULL,
  `hospital` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `experience_years` int(11) NOT NULL DEFAULT 0,
  `consultation_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `languages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`languages`)),
  `photo` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `doctor_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_profile`
--

LOCK TABLES `doctor_profile` WRITE;
/*!40000 ALTER TABLE `doctor_profile` DISABLE KEYS */;
INSERT INTO `doctor_profile` VALUES (1,1,'Dr. Sarah Rahman','MBBS, FCPS (Medicine), MD (Cardiology)','Cardiology & Internal Medicine Specialist','A-45892','National Heart Foundation & Research Institute','Dr. Sarah Rahman is a highly experienced Cardiologist with a demonstrated history of working in top-tier healthcare institutions. She specializes in interventional cardiology, heart failure management, and preventive medicine.',12,1000.00,'[\"Bengali\", \"English\"]','doctor_portrait.jpg','sarah-cover.jpg','2026-07-17 00:43:20','2026-07-17 00:43:20');
/*!40000 ALTER TABLE `doctor_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_services`
--

DROP TABLE IF EXISTS `doctor_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor_services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT 'activity',
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `doctor_id` (`doctor_id`),
  CONSTRAINT `doctor_services_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_services`
--

LOCK TABLES `doctor_services` WRITE;
/*!40000 ALTER TABLE `doctor_services` DISABLE KEYS */;
INSERT INTO `doctor_services` VALUES (1,1,'Cardiac Consultation','Comprehensive heart health assessment and treatment planning','heart',1),(2,1,'ECG & Stress Test','Electrocardiogram and exercise stress testing','activity',2),(3,1,'Echocardiography','Ultrasound imaging of the heart structure and function','monitor',3),(4,1,'Hypertension Management','Blood pressure monitoring and long-term management plans','trending-up',4),(5,1,'Diabetes Care','Comprehensive diabetic care and metabolic assessments','thermometer',5),(6,1,'Preventive Checkup','Full-body health screening and preventive medicine','shield',6);
/*!40000 ALTER TABLE `doctor_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` bigint(20) unsigned DEFAULT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('pending','paid','partial','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `visit_id` (`visit_id`),
  KEY `idx_invoice_patient` (`patient_id`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,1,1,'INV-2026-0001',1000.00,0.00,1000.00,'paid','Cash',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08'),(2,2,2,'INV-2026-0002',1000.00,0.00,1000.00,'paid','bKash',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08'),(3,3,3,'INV-2026-0003',1000.00,0.00,1000.00,'paid','Nagad',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lab_requests`
--

DROP TABLE IF EXISTS `lab_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lab_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` bigint(20) unsigned NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `test_category` varchar(100) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `status` enum('requested','completed','reviewed') NOT NULL DEFAULT 'requested',
  `result_notes` text DEFAULT NULL,
  `result_file` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `visit_id` (`visit_id`),
  CONSTRAINT `lab_requests_ibfk_1` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lab_requests`
--

LOCK TABLES `lab_requests` WRITE;
/*!40000 ALTER TABLE `lab_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicines`
--

DROP TABLE IF EXISTS `medicines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medicines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `generic_name` varchar(255) DEFAULT NULL,
  `type` enum('tablet','capsule','syrup','injection','cream','drops','inhaler','other') NOT NULL DEFAULT 'tablet',
  `strength` varchar(50) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `is_favorite` tinyint(1) DEFAULT 0,
  `usage_count` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_medicine_name` (`name`),
  KEY `idx_medicine_fav` (`is_favorite`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicines`
--

LOCK TABLES `medicines` WRITE;
/*!40000 ALTER TABLE `medicines` DISABLE KEYS */;
INSERT INTO `medicines` VALUES (1,'Napa Extend','Paracetamol','tablet','665mg','Beximco Pharmaceuticals Ltd.',1,24,'2026-07-17 00:43:20','2026-07-17 00:43:20'),(2,'Ace Plus','Paracetamol + Caffeine','tablet','500mg+65mg','Square Pharmaceuticals PLC',1,18,'2026-07-17 00:43:20','2026-07-17 00:43:20'),(3,'Sergel','Esomeprazole','capsule','20mg','Healthcare Pharmaceuticals Ltd.',1,45,'2026-07-17 00:43:20','2026-07-17 00:43:20'),(4,'Seclo','Omeprazole','capsule','20mg','Square Pharmaceuticals PLC',0,12,'2026-07-17 00:43:20','2026-07-17 00:43:20'),(5,'Alatrol','Cetirizine Hydrochloride','tablet','10mg','Square Pharmaceuticals PLC',1,15,'2026-07-17 00:43:20','2026-07-17 00:43:20'),(6,'Fexo','Fexofenadine','tablet','120mg','Square Pharmaceuticals PLC',0,8,'2026-07-17 00:43:20','2026-07-17 00:43:20'),(7,'Azithrocin','Azithromycin','tablet','500mg','Beximco Pharmaceuticals Ltd.',1,30,'2026-07-17 00:43:20','2026-07-17 00:43:20'),(8,'Monas 10','Montelukast','tablet','10mg','Acme Laboratories Ltd.',1,22,'2026-07-17 00:43:20','2026-07-17 00:43:20'),(9,'Xorel','Rivaroxaban','tablet','10mg','Incepta Pharmaceuticals Ltd.',0,3,'2026-07-17 00:43:20','2026-07-17 00:43:20'),(10,'Concor 5','Bisoprolol Fumarate','tablet','5mg','Merck / Square',1,14,'2026-07-17 00:43:20','2026-07-17 00:43:20');
/*!40000 ALTER TABLE `medicines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_notif_user_read` (`user_id`,`is_read`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otp_codes`
--

DROP TABLE IF EXISTS `otp_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `otp_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) NOT NULL,
  `code_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `attempts` int(11) DEFAULT 0,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_otp_phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otp_codes`
--

LOCK TABLES `otp_codes` WRITE;
/*!40000 ALTER TABLE `otp_codes` DISABLE KEYS */;
INSERT INTO `otp_codes` VALUES (1,'01712345678','$2y$10$Hk0FobKpwJXwImI2TLnGzeTDmG3YctuypothXGSS1kxqJrJ2cg9.y','2026-07-18 04:24:55',0,0,'2026-07-18 04:19:55'),(2,'01712345678','$2y$10$DuXBu8Cno7ls2WD.wMTpRORuX5J1yt08WuAAgGEPpq5Qqrj/jV2WK','2026-07-18 04:25:28',0,0,'2026-07-18 04:20:28'),(3,'01712345678','$2y$10$qYniJYjc2Zn7SoSY4yK2KewH3i1Kw8W6fZVigTNyBZ/GdzWnlIwSm','2026-07-18 04:28:02',1,1,'2026-07-18 04:23:02'),(4,'01712345678','$2y$10$4VyYd2YDygUS7q.upSKXtOItqiT.0SQzZLqbpxp9W3ujzh.ElSjh.','2026-07-18 04:30:41',1,1,'2026-07-18 04:25:41'),(5,'01712345678','$2y$10$qyDPN24xIS0ZZsLdmnqgC.7oPI3OoJydRA50I37KwyXaHaBuC466W','2026-07-18 04:49:02',1,1,'2026-07-18 04:44:02'),(6,'01712345678','$2y$10$gzErrvMQoovGdOFlKtcHVuMlOB0SD6I7MFVVEXfDPxLduGmgMAqju','2026-07-18 14:42:44',1,1,'2026-07-18 14:37:44'),(7,'01712345678','$2y$10$pj3iR6Y6T6YScZzOwaDRmeor6gmlbM.UkWUEXH9pfEmaRhEOWaxji','2026-07-18 15:05:38',1,1,'2026-07-18 15:00:38'),(8,'01712345678','$2y$10$CbLdMFaulMQS3nQJfyh1FO73qoJYHSN4YTAmCfcVTOmwD2CtU1a9u','2026-07-18 15:14:38',1,1,'2026-07-18 15:09:38'),(9,'01712345678','$2y$10$Xbwbe6BdXfAf6R00wzYRweTqP7rP37Sc2/E0Rd4.IXj4eDttV.dxa','2026-07-18 15:15:14',1,1,'2026-07-18 15:10:14'),(10,'01712345678','$2y$10$wnQNQlFnOljtp99jm8B6sOeyShiPo9KBdbt0f/VdbXBUOIJAseTRa','2026-07-22 21:11:53',1,1,'2026-07-22 21:06:53'),(11,'01712345678','$2y$10$kGiiZc6TLq5lhAVffP/7FeCt0YTqHIBRKH5jRPvdZcQHlc8P3NdwS','2026-07-22 21:12:48',1,1,'2026-07-22 21:07:48'),(12,'01712345678','$2y$10$Njwmj3tJw3A6AqRK.MYK.OshaS.UIW059a0hb6qs71ZR1.P2hLQZG','2026-07-23 22:40:44',0,0,'2026-07-23 22:35:44'),(13,'01712345678','$2y$10$A.NzHDFAnKGCnVt38YMmC.f9zm24yNygzQMd/Lw/kvfGVHQp4FDBW','2026-07-24 00:58:28',0,0,'2026-07-24 00:53:28');
/*!40000 ALTER TABLE `otp_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `medical_notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`),
  KEY `idx_patients_phone` (`phone`),
  KEY `idx_patients_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES (1,'Abdur Rahman','01711112222','abdur.rahman@example.com',45,'male','A+','Mirpur-12, Dhaka',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08',NULL),(2,'Fatema Begum','01822223333','fatema.begum@example.com',32,'female','O+','Dhanmondi, Dhaka',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08',NULL),(3,'Kamil Ahmed','01933334444','kamil.ahmed@example.com',60,'male','B+','Uttara Sector-4, Dhaka',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08',NULL),(4,'Nusrat Jahan','01544445555','nusrat.jahan@example.com',28,'female','AB+','Mohammadpur, Dhaka',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08',NULL),(5,'Zahid Hasan','01655556666','zahid.hasan@example.com',52,'male','O-','Banani, Dhaka',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08',NULL),(6,'Sultana Razia','01766667777','sultana.razia@example.com',67,'female','B-','Tejgaon, Dhaka',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08',NULL),(7,'Tariqul Islam','01877778888','tariqul.islam@example.com',38,'male','A-','Gulsan-2, Dhaka',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08',NULL),(8,'Jahanara Alam','01988889999','jahanara.alam@example.com',25,'female','O+','Lalbagh, Dhaka',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08',NULL),(9,'Rashedul Bari','01599990000','rashedul.bari@example.com',41,'male','AB-','Badda, Dhaka',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08',NULL),(10,'Meherun Nesa','01700001111','meherun.nesa@example.com',59,'female','B+','Khilgaon, Dhaka',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08',NULL),(12,'Abul Kalam','01711111111',NULL,52,'male','O+','Mirpur, Dhaka',NULL,'2026-07-18 12:50:10','2026-07-18 12:50:10',NULL),(13,'Rahima Begum','01722222222',NULL,45,'female','A+','Dhanmondi, Dhaka',NULL,'2026-07-18 12:50:10','2026-07-18 12:50:10',NULL),(14,'Kamal Hossain','01733333333',NULL,29,'male','B+','Uttara, Dhaka',NULL,'2026-07-18 12:50:10','2026-07-18 12:50:10',NULL),(15,'Salma Akter','01744444444',NULL,34,'female','AB+','Gulshan, Dhaka',NULL,'2026-07-18 12:50:10','2026-07-18 12:50:10',NULL),(16,'Karim Box','01755555555',NULL,62,'male','O-','Badda, Dhaka',NULL,'2026-07-18 12:50:10','2026-07-18 12:50:10',NULL),(17,'Demo Patient','01712345678',NULL,30,'male','B+','Dhaka, Bangladesh',NULL,'2026-07-18 12:50:10','2026-07-18 12:50:10',NULL);
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_ref` varchar(100) DEFAULT NULL,
  `paid_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,1000.00,'Cash','CASH-9821','2026-07-18 14:36:08','2026-07-18 14:36:08'),(2,2,1000.00,'bKash','BKASH-TRX5512','2026-07-18 14:36:08','2026-07-18 14:36:08'),(3,3,1000.00,'Nagad','NAGAD-TXN3040','2026-07-18 14:36:08','2026-07-18 14:36:08');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prescription_items`
--

DROP TABLE IF EXISTS `prescription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prescription_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prescription_id` bigint(20) unsigned NOT NULL,
  `medicine_id` bigint(20) unsigned NOT NULL,
  `dosage` varchar(100) NOT NULL COMMENT 'e.g. 1+0+1',
  `frequency` varchar(100) NOT NULL COMMENT 'e.g. After meal',
  `duration` varchar(100) NOT NULL COMMENT 'e.g. 7 days',
  `instructions` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `prescription_id` (`prescription_id`),
  KEY `medicine_id` (`medicine_id`),
  CONSTRAINT `prescription_items_ibfk_1` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prescription_items_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescription_items`
--

LOCK TABLES `prescription_items` WRITE;
/*!40000 ALTER TABLE `prescription_items` DISABLE KEYS */;
INSERT INTO `prescription_items` VALUES (1,1,10,'1+0+0','After meal','30 days','In morning',0),(2,2,5,'0+0+1','After meal','10 days','At night before bedtime',0),(3,3,10,'1+0+0','After meal','30 days','Same time daily',0);
/*!40000 ALTER TABLE `prescription_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prescriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `prescription_number` varchar(50) NOT NULL,
  `rx_date` date NOT NULL,
  `special_instructions` text DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prescription_number` (`prescription_number`),
  KEY `visit_id` (`visit_id`),
  KEY `idx_presc_patient` (`patient_id`),
  CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescriptions`
--

LOCK TABLES `prescriptions` WRITE;
/*!40000 ALTER TABLE `prescriptions` DISABLE KEYS */;
INSERT INTO `prescriptions` VALUES (1,1,1,'RX-2026-0001','2026-07-18','Take medicines regularly after meals. Call emergency in case of acute pain.',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08'),(2,2,2,'RX-2026-0002','2026-07-18','Avoid drinking tea/coffee immediately after iron-rich foods.',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08'),(3,3,3,'RX-2026-0003','2026-07-18','Compliance with dual antiplatelet therapy is crucial. Never miss doses.',NULL,'2026-07-18 14:36:08','2026-07-18 14:36:08');
/*!40000 ALTER TABLE `prescriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue_rules`
--

DROP TABLE IF EXISTS `queue_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queue_rules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chamber_id` bigint(20) unsigned NOT NULL,
  `rule_name` varchar(100) NOT NULL,
  `rule_order` int(11) NOT NULL,
  `patient_type` enum('normal','report','vip','emergency','followup','senior','pregnant','custom') NOT NULL,
  `batch_size` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `chamber_id` (`chamber_id`),
  CONSTRAINT `queue_rules_ibfk_1` FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue_rules`
--

LOCK TABLES `queue_rules` WRITE;
/*!40000 ALTER TABLE `queue_rules` DISABLE KEYS */;
INSERT INTO `queue_rules` VALUES (1,1,'Normal Batch',1,'normal',3,1,'2026-07-17 00:43:20','2026-07-17 00:43:20'),(2,1,'Report Batch',2,'report',2,1,'2026-07-17 00:43:20','2026-07-17 00:43:20'),(3,1,'VIP Batch',3,'vip',1,1,'2026-07-17 00:43:20','2026-07-17 00:43:20');
/*!40000 ALTER TABLE `queue_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue_settings`
--

DROP TABLE IF EXISTS `queue_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queue_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chamber_id` bigint(20) unsigned NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`setting_value`)),
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_chamber_key` (`chamber_id`,`setting_key`),
  CONSTRAINT `queue_settings_ibfk_1` FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue_settings`
--

LOCK TABLES `queue_settings` WRITE;
/*!40000 ALTER TABLE `queue_settings` DISABLE KEYS */;
INSERT INTO `queue_settings` VALUES (1,1,'ratio_rules','{\"normal\": 3, \"report\": 2, \"vip\": 1}','Ratio mapping of normal vs report vs vip patients','2026-07-17 00:43:20','2026-07-17 00:43:20'),(2,1,'rejoin_gap','3','Number of patients to bypass before a missed patient rejoins the queue','2026-07-17 00:43:20','2026-07-17 00:43:20'),(3,1,'avg_consultation_time','10','Estimated consultation duration in minutes per patient','2026-07-17 00:43:20','2026-07-17 00:43:20'),(4,1,'max_online_appointments','20','Maximum acceptable online appointments limit','2026-07-18 04:51:09','2026-07-18 04:51:09');
/*!40000 ALTER TABLE `queue_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rate_limits`
--

DROP TABLE IF EXISTS `rate_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rate_limits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `endpoint` varchar(100) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 1,
  `window_start` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rate_limit` (`ip_address`,`endpoint`,`window_start`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rate_limits`
--

LOCK TABLES `rate_limits` WRITE;
/*!40000 ALTER TABLE `rate_limits` DISABLE KEYS */;
/*!40000 ALTER TABLE `rate_limits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `serials`
--

DROP TABLE IF EXISTS `serials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `serials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint(20) unsigned DEFAULT NULL,
  `chamber_id` bigint(20) unsigned NOT NULL,
  `serial_date` date NOT NULL,
  `serial_number` int(11) NOT NULL,
  `queue_position` int(11) NOT NULL,
  `patient_type` enum('normal','report','vip','emergency','followup','senior','pregnant','custom') NOT NULL DEFAULT 'normal',
  `priority_level` int(11) DEFAULT 0,
  `status` enum('waiting','called','in_consultation','hold','skipped','missed','completed','cancelled','no_show') NOT NULL DEFAULT 'waiting',
  `called_at` datetime DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `hold_reason` varchar(255) DEFAULT NULL,
  `missed_rejoin_after` int(11) DEFAULT NULL,
  `original_position` int(11) DEFAULT NULL,
  `is_rejoined` tinyint(1) DEFAULT 0,
  `token_number` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `appointment_id` (`appointment_id`),
  KEY `chamber_id` (`chamber_id`),
  KEY `idx_serial_date_pos` (`serial_date`,`chamber_id`,`queue_position`),
  KEY `idx_serial_status` (`status`),
  CONSTRAINT `serials_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `serials_ibfk_2` FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serials`
--

LOCK TABLES `serials` WRITE;
/*!40000 ALTER TABLE `serials` DISABLE KEYS */;
INSERT INTO `serials` VALUES (11,11,1,'2026-07-24',1,2,'normal',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260718001','Seeded live queue token','2026-07-18 12:50:10','2026-07-18 12:50:10'),(12,12,1,'2026-07-24',2,4,'report',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260718002','Seeded live queue token','2026-07-18 12:50:10','2026-07-18 12:50:10'),(13,13,1,'2026-07-24',3,5,'vip',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260718003','Seeded live queue token','2026-07-18 12:50:10','2026-07-18 12:50:10'),(14,14,1,'2026-07-24',4,1,'emergency',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260718004','Seeded live queue token','2026-07-18 12:50:10','2026-07-18 12:50:10'),(15,15,1,'2026-07-24',5,3,'normal',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260718005','Seeded live queue token','2026-07-18 12:50:10','2026-07-18 12:50:10'),(16,16,1,'2026-07-24',1,2,'normal',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260722001','Seeded live queue token','2026-07-22 17:44:56','2026-07-22 17:44:56'),(17,17,1,'2026-07-24',2,4,'report',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260722002','Seeded live queue token','2026-07-22 17:44:56','2026-07-22 17:44:56'),(18,18,1,'2026-07-24',3,5,'vip',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260722003','Seeded live queue token','2026-07-22 17:44:56','2026-07-22 17:44:56'),(19,19,1,'2026-07-24',4,1,'emergency',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260722004','Seeded live queue token','2026-07-22 17:44:56','2026-07-22 17:44:56'),(20,20,1,'2026-07-24',5,3,'normal',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260722005','Seeded live queue token','2026-07-22 17:44:56','2026-07-22 17:44:56'),(21,21,1,'2026-07-24',1,1,'normal',0,'called','2026-07-23 21:01:40',NULL,NULL,NULL,NULL,NULL,0,'TK-260723001','Seeded live queue token','2026-07-23 16:48:10','2026-07-23 21:01:40'),(22,22,1,'2026-07-24',2,4,'report',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260723002','Seeded live queue token','2026-07-23 16:48:10','2026-07-23 16:48:10'),(23,23,1,'2026-07-24',3,5,'vip',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260723003','Seeded live queue token','2026-07-23 16:48:10','2026-07-23 16:48:10'),(24,24,1,'2026-07-24',4,2,'emergency',0,'waiting','2026-07-23 21:01:37',NULL,NULL,NULL,NULL,NULL,0,'TK-260723004','Seeded live queue token','2026-07-23 16:48:10','2026-07-23 21:01:37'),(25,25,1,'2026-07-24',5,3,'normal',0,'waiting',NULL,NULL,NULL,NULL,NULL,NULL,0,'TK-260723005','Seeded live queue token','2026-07-23 16:48:10','2026-07-23 16:48:10');
/*!40000 ALTER TABLE `serials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `group_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'clinic_name','Metro Healthcare Clinic','general','2026-07-17 00:43:20','2026-07-17 00:43:20'),(2,'sms_provider','sandbox','sms','2026-07-17 00:43:20','2026-07-17 00:43:20'),(3,'currency','BDT','general','2026-07-17 00:43:20','2026-07-17 00:43:20');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','receptionist','patient') NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_email` (`email`),
  KEY `idx_users_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Dr. Sarah Rahman','admin@doctorserial.cloud','01712345678','$2y$10$upVnHoqNVMXpOjJZSMpFBOtCAPRQuZ69FGdvdeKV/H6IjQyzq4aae','admin','sarah-avatar.png',1,NULL,'2026-07-23 21:43:05','2026-07-17 00:43:20','2026-07-23 21:43:05',NULL),(2,'Rahim Uddin','receptionist@doctorserial.cloud','01812345678','$2y$10$upVnHoqNVMXpOjJZSMpFBOtCAPRQuZ69FGdvdeKV/H6IjQyzq4aae','receptionist','rahim-avatar.png',1,NULL,'2026-07-23 21:00:19','2026-07-17 00:43:20','2026-07-23 21:00:19',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visits`
--

DROP TABLE IF EXISTS `visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `serial_id` bigint(20) unsigned DEFAULT NULL,
  `chamber_id` bigint(20) unsigned NOT NULL,
  `visit_date` date NOT NULL,
  `chief_complaint` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `doctor_notes` text DEFAULT NULL,
  `next_visit_date` date DEFAULT NULL,
  `status` enum('in_progress','completed') NOT NULL DEFAULT 'in_progress',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `serial_id` (`serial_id`),
  KEY `chamber_id` (`chamber_id`),
  KEY `idx_visit_date` (`visit_date`),
  CONSTRAINT `visits_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `visits_ibfk_2` FOREIGN KEY (`serial_id`) REFERENCES `serials` (`id`) ON DELETE SET NULL,
  CONSTRAINT `visits_ibfk_3` FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visits`
--

LOCK TABLES `visits` WRITE;
/*!40000 ALTER TABLE `visits` DISABLE KEYS */;
INSERT INTO `visits` VALUES (1,1,NULL,1,'2026-07-18','Chest pain on moderate exertion for 2 weeks, radiating to left arm. Mild shortness of breath.','Stable Angina Pectoris, Essential Hypertension','Advised cardiac rest. Restrict dietary sodium and high cholesterol foods. Review in 1 week with fasting blood sugar and ECG reports.',NULL,'completed','2026-07-18 14:36:08','2026-07-18 14:36:08'),(2,2,NULL,1,'2026-07-18','Generalized weakness, fatigue, palpitations. Cold hands and feet.','Iron Deficiency Anemia, Sinus Tachycardia','Encouraged red meat, spinach, leafy greens diet. Check CBC and Serum Ferritin profile in 1 month.',NULL,'completed','2026-07-18 14:36:08','2026-07-18 14:36:08'),(3,3,NULL,1,'2026-07-18','Routine post-PCI (angioplasty) checkup. Asymptomatic.','Ischemic Heart Disease (Post-PCI), Controlled Hypertension','Patient is doing excellent. Continue daily walking for 30 minutes. Keep track of BP chart weekly.',NULL,'completed','2026-07-18 14:36:08','2026-07-18 14:36:08'),(4,4,NULL,1,'2026-07-18','High fever, sore throat, difficulty swallowing for 3 days.',NULL,'Logged initial vitals: BP 115/75 mmHg, Weight 58kg, Temp 102.4 F, Pulse 88 bpm.',NULL,'in_progress','2026-07-18 14:36:08','2026-07-18 14:36:08');
/*!40000 ALTER TABLE `visits` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-24 12:30:12
