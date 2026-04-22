-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: patent_management_db
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
-- Table structure for table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alerts` (
  `AlertID` int NOT NULL AUTO_INCREMENT,
  `AppID` int DEFAULT NULL,
  `ActionID` int DEFAULT NULL,
  `SentAlertTo` int DEFAULT NULL,
  `patentfeeID` int DEFAULT NULL,
  `AlertType` varchar(50) DEFAULT NULL,
  `Reason` text,
  PRIMARY KEY (`AlertID`),
  KEY `AppID` (`AppID`),
  KEY `ActionID` (`ActionID`),
  KEY `SentAlertTo` (`SentAlertTo`),
  KEY `patentfeeID` (`patentfeeID`),
  CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`AppID`) REFERENCES `patentapplication` (`AppID`) ON DELETE CASCADE,
  CONSTRAINT `alerts_ibfk_2` FOREIGN KEY (`ActionID`) REFERENCES `officeaction` (`ActionID`) ON DELETE SET NULL,
  CONSTRAINT `alerts_ibfk_3` FOREIGN KEY (`SentAlertTo`) REFERENCES `user` (`usr_ID`) ON DELETE CASCADE,
  CONSTRAINT `alerts_ibfk_4` FOREIGN KEY (`patentfeeID`) REFERENCES `patentfee` (`patentfeeID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `claim`
--

DROP TABLE IF EXISTS `claim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `claim` (
  `Claim_ID` int NOT NULL AUTO_INCREMENT,
  `Patent_ID` int DEFAULT NULL,
  `Text` text NOT NULL,
  `Claim_Type` varchar(50) DEFAULT NULL,
  `DependentClaimID` int DEFAULT NULL,
  PRIMARY KEY (`Claim_ID`),
  KEY `Patent_ID` (`Patent_ID`),
  KEY `DependentClaimID` (`DependentClaimID`),
  CONSTRAINT `claim_ibfk_1` FOREIGN KEY (`Patent_ID`) REFERENCES `patent` (`Patent_ID`) ON DELETE CASCADE,
  CONSTRAINT `claim_ibfk_2` FOREIGN KEY (`DependentClaimID`) REFERENCES `claim` (`Claim_ID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `coverdterritory`
--

DROP TABLE IF EXISTS `coverdterritory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coverdterritory` (
  `PatentAppID` int NOT NULL,
  `JurisdictionalID` int NOT NULL,
  PRIMARY KEY (`PatentAppID`,`JurisdictionalID`),
  KEY `JurisdictionalID` (`JurisdictionalID`),
  CONSTRAINT `coverdterritory_ibfk_1` FOREIGN KEY (`PatentAppID`) REFERENCES `patentapplication` (`AppID`) ON DELETE CASCADE,
  CONSTRAINT `coverdterritory_ibfk_2` FOREIGN KEY (`JurisdictionalID`) REFERENCES `jursidiction` (`JurisdictionalID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `disclosure`
--

DROP TABLE IF EXISTS `disclosure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disclosure` (
  `disc_ID` int NOT NULL AUTO_INCREMENT,
  `Classification_ID` int DEFAULT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text,
  `Unique_fgrPrint` varchar(255) DEFAULT NULL,
  `FilingDate` date DEFAULT NULL,
  PRIMARY KEY (`disc_ID`),
  KEY `Classification_ID` (`Classification_ID`),
  CONSTRAINT `disclosure_ibfk_1` FOREIGN KEY (`Classification_ID`) REFERENCES `ipc_classification` (`Classification_ID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document` (
  `document_ID` int NOT NULL AUTO_INCREMENT,
  `evidence_ID` int DEFAULT NULL,
  `filePath` varchar(255) NOT NULL,
  `Version` varchar(20) DEFAULT NULL,
  `docType` varchar(50) DEFAULT NULL,
  `UploadDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`document_ID`),
  KEY `evidence_ID` (`evidence_ID`),
  CONSTRAINT `document_ibfk_1` FOREIGN KEY (`evidence_ID`) REFERENCES `evidence_vault` (`evidence_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `evidence_vault`
--

DROP TABLE IF EXISTS `evidence_vault`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evidence_vault` (
  `evidence_ID` int NOT NULL AUTO_INCREMENT,
  `disc_ID` int DEFAULT NULL,
  `evidence_type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`evidence_ID`),
  KEY `disc_ID` (`disc_ID`),
  CONSTRAINT `evidence_vault_ibfk_1` FOREIGN KEY (`disc_ID`) REFERENCES `disclosure` (`disc_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feeschedule`
--

DROP TABLE IF EXISTS `feeschedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feeschedule` (
  `feeScheduleID` int NOT NULL AUTO_INCREMENT,
  `currencyCode` varchar(3) NOT NULL,
  `baseAmount` decimal(10,2) NOT NULL,
  `dueDate` date DEFAULT NULL,
  PRIMARY KEY (`feeScheduleID`)
) ENGINE=InnoDB AUTO_INCREMENT=3000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `generatedportfolio`
--

DROP TABLE IF EXISTS `generatedportfolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `generatedportfolio` (
  `portfolio_ID` int NOT NULL,
  `Patent_ID` int NOT NULL,
  PRIMARY KEY (`portfolio_ID`,`Patent_ID`),
  KEY `Patent_ID` (`Patent_ID`),
  CONSTRAINT `generatedportfolio_ibfk_1` FOREIGN KEY (`portfolio_ID`) REFERENCES `portfolio` (`portfolio_ID`) ON DELETE CASCADE,
  CONSTRAINT `generatedportfolio_ibfk_2` FOREIGN KEY (`Patent_ID`) REFERENCES `patent` (`Patent_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grantedpatents`
--

DROP TABLE IF EXISTS `grantedpatents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grantedpatents` (
  `Patent_ID` int NOT NULL,
  `AppID` int NOT NULL,
  PRIMARY KEY (`Patent_ID`,`AppID`),
  KEY `AppID` (`AppID`),
  CONSTRAINT `grantedpatents_ibfk_1` FOREIGN KEY (`Patent_ID`) REFERENCES `patent` (`Patent_ID`) ON DELETE CASCADE,
  CONSTRAINT `grantedpatents_ibfk_2` FOREIGN KEY (`AppID`) REFERENCES `patentapplication` (`AppID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ipc_classification`
--

DROP TABLE IF EXISTS `ipc_classification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ipc_classification` (
  `Classification_ID` int NOT NULL AUTO_INCREMENT,
  `Section` varchar(50) DEFAULT NULL,
  `Class` varchar(50) DEFAULT NULL,
  `Group` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Classification_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jursidiction`
--

DROP TABLE IF EXISTS `jursidiction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jursidiction` (
  `JurisdictionalID` int NOT NULL AUTO_INCREMENT,
  `JurisdictionalType` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `countryCode` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`JurisdictionalID`)
) ENGINE=InnoDB AUTO_INCREMENT=4000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `licenseagreement`
--

DROP TABLE IF EXISTS `licenseagreement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `licenseagreement` (
  `LicenseID` int NOT NULL AUTO_INCREMENT,
  `Patent_ID` int DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `Territory` varchar(100) DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `IsExclusive` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`LicenseID`),
  KEY `Patent_ID` (`Patent_ID`),
  CONSTRAINT `licenseagreement_ibfk_1` FOREIGN KEY (`Patent_ID`) REFERENCES `patent` (`Patent_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `LogID` int NOT NULL AUTO_INCREMENT,
  `usr_ID` int DEFAULT NULL,
  `Action` varchar(255) DEFAULT NULL,
  `Timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(50) DEFAULT NULL,
  `Description` text,
  PRIMARY KEY (`LogID`),
  KEY `usr_ID` (`usr_ID`),
  CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`usr_ID`) REFERENCES `user` (`usr_ID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `officeaction`
--

DROP TABLE IF EXISTS `officeaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `officeaction` (
  `ActionID` int NOT NULL AUTO_INCREMENT,
  `AppID` int DEFAULT NULL,
  `DateReceived` date DEFAULT NULL,
  `Deadline` date DEFAULT NULL,
  `Type` varchar(100) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ActionID`),
  KEY `AppID` (`AppID`),
  CONSTRAINT `officeaction_ibfk_1` FOREIGN KEY (`AppID`) REFERENCES `patentapplication` (`AppID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ownershipofinvention`
--

DROP TABLE IF EXISTS `ownershipofinvention`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ownershipofinvention` (
  `disc_ID` int NOT NULL,
  `usr_ID` int NOT NULL,
  `ContributionPercentage` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`disc_ID`,`usr_ID`),
  KEY `usr_ID` (`usr_ID`),
  CONSTRAINT `ownershipofinvention_ibfk_1` FOREIGN KEY (`disc_ID`) REFERENCES `disclosure` (`disc_ID`) ON DELETE CASCADE,
  CONSTRAINT `ownershipofinvention_ibfk_2` FOREIGN KEY (`usr_ID`) REFERENCES `user` (`usr_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patent`
--

DROP TABLE IF EXISTS `patent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patent` (
  `Patent_ID` int NOT NULL AUTO_INCREMENT,
  `Number` varchar(100) NOT NULL,
  `GrantDate` date DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `Expiration` date DEFAULT NULL,
  PRIMARY KEY (`Patent_ID`),
  UNIQUE KEY `Number` (`Number`)
) ENGINE=InnoDB AUTO_INCREMENT=6000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patentapplication`
--

DROP TABLE IF EXISTS `patentapplication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patentapplication` (
  `AppID` int NOT NULL AUTO_INCREMENT,
  `disc_ID` int DEFAULT NULL,
  `appNum` varchar(100) DEFAULT NULL,
  `FilingDate` date DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`AppID`),
  UNIQUE KEY `appNum` (`appNum`),
  KEY `disc_ID` (`disc_ID`),
  CONSTRAINT `patentapplication_ibfk_1` FOREIGN KEY (`disc_ID`) REFERENCES `disclosure` (`disc_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patentfee`
--

DROP TABLE IF EXISTS `patentfee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patentfee` (
  `patentfeeID` int NOT NULL AUTO_INCREMENT,
  `feeScheduleID` int DEFAULT NULL,
  `Patent_ID` int DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `feeType` varchar(50) DEFAULT NULL,
  `calculatedAmount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`patentfeeID`),
  KEY `feeScheduleID` (`feeScheduleID`),
  KEY `Patent_ID` (`Patent_ID`),
  CONSTRAINT `patentfee_ibfk_1` FOREIGN KEY (`feeScheduleID`) REFERENCES `feeschedule` (`feeScheduleID`) ON DELETE CASCADE,
  CONSTRAINT `patentfee_ibfk_2` FOREIGN KEY (`Patent_ID`) REFERENCES `patent` (`Patent_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portfolio`
--

DROP TABLE IF EXISTS `portfolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolio` (
  `portfolio_ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` text,
  `CreatedDate` date DEFAULT NULL,
  `Score` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`portfolio_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prior_art`
--

DROP TABLE IF EXISTS `prior_art`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prior_art` (
  `Prior_ID` int NOT NULL AUTO_INCREMENT,
  `disc_ID` int DEFAULT NULL,
  `Description` text,
  `Link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Prior_ID`),
  KEY `disc_ID` (`disc_ID`),
  CONSTRAINT `prior_art_ibfk_1` FOREIGN KEY (`disc_ID`) REFERENCES `disclosure` (`disc_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `royaltypayment`
--

DROP TABLE IF EXISTS `royaltypayment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `royaltypayment` (
  `royaltyID` int NOT NULL AUTO_INCREMENT,
  `LicenseID` int DEFAULT NULL,
  `Amount` decimal(12,2) NOT NULL,
  `Currency` varchar(3) DEFAULT NULL,
  `calculationMethod` varchar(100) DEFAULT NULL,
  `DistributionStatus` varchar(50) DEFAULT NULL,
  `PmtDate` date DEFAULT NULL,
  PRIMARY KEY (`royaltyID`),
  KEY `LicenseID` (`LicenseID`),
  CONSTRAINT `royaltypayment_ibfk_1` FOREIGN KEY (`LicenseID`) REFERENCES `licenseagreement` (`LicenseID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `usr_ID` int NOT NULL AUTO_INCREMENT,
  `Email` varchar(255) NOT NULL,
  `Role` varchar(50) NOT NULL,
  `pwd_hash` varchar(255) NOT NULL,
  `2FA_Enabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`usr_ID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `workflow`
--

DROP TABLE IF EXISTS `workflow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workflow` (
  `workflow_ID` int NOT NULL AUTO_INCREMENT,
  `disc_ID` int DEFAULT NULL,
  `Description` text,
  `Stepname` varchar(100) DEFAULT NULL,
  `Order` int DEFAULT NULL,
  `isFinalStep` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`workflow_ID`),
  KEY `disc_ID` (`disc_ID`),
  CONSTRAINT `workflow_ibfk_1` FOREIGN KEY (`disc_ID`) REFERENCES `disclosure` (`disc_ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-22 19:47:07
