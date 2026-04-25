-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2026 at 01:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `patent_tracking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `AlertID` int(11) NOT NULL,
  `AppID` int(11) DEFAULT NULL,
  `ActionID` int(11) DEFAULT NULL,
  `SentAlertTo` int(11) DEFAULT NULL,
  `patentfeeID` int(11) DEFAULT NULL,
  `AlertType` varchar(50) DEFAULT NULL,
  `Reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `claim`
--

CREATE TABLE `claim` (
  `Claim_ID` int(11) NOT NULL,
  `Patent_ID` int(11) DEFAULT NULL,
  `Text` text NOT NULL,
  `Claim_Type` varchar(50) DEFAULT NULL,
  `DependentClaimID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coverdterritory`
--

CREATE TABLE `coverdterritory` (
  `PatentAppID` int(11) NOT NULL,
  `JurisdictionalID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disclosure`
--

CREATE TABLE `disclosure` (
  `disc_ID` int(11) NOT NULL,
  `Classification_ID` int(11) DEFAULT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Unique_fgrPrint` varchar(255) DEFAULT NULL,
  `FilingDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `document_ID` int(11) NOT NULL,
  `evidence_ID` int(11) DEFAULT NULL,
  `filePath` varchar(255) NOT NULL,
  `Version` varchar(20) DEFAULT NULL,
  `docType` varchar(50) DEFAULT NULL,
  `UploadDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evidence_vault`
--

CREATE TABLE `evidence_vault` (
  `evidence_ID` int(11) NOT NULL,
  `disc_ID` int(11) DEFAULT NULL,
  `evidence_type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feeschedule`
--

CREATE TABLE `feeschedule` (
  `feeScheduleID` int(11) NOT NULL,
  `currencyCode` varchar(3) NOT NULL,
  `baseAmount` decimal(10,2) NOT NULL,
  `dueDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `generatedportfolio`
--

CREATE TABLE `generatedportfolio` (
  `portfolio_ID` int(11) NOT NULL,
  `Patent_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grantedpatents`
--

CREATE TABLE `grantedpatents` (
  `Patent_ID` int(11) NOT NULL,
  `AppID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipc_classification`
--

CREATE TABLE `ipc_classification` (
  `Classification_ID` int(11) NOT NULL,
  `Section` varchar(50) DEFAULT NULL,
  `Class` varchar(50) DEFAULT NULL,
  `Group` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jursidiction`
--

CREATE TABLE `jursidiction` (
  `JurisdictionalID` int(11) NOT NULL,
  `JurisdictionalType` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `countryCode` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `licenseagreement`
--

CREATE TABLE `licenseagreement` (
  `LicenseID` int(11) NOT NULL,
  `Patent_ID` int(11) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `Territory` varchar(100) DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `IsExclusive` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `LogID` int(11) NOT NULL,
  `usr_ID` int(11) DEFAULT NULL,
  `Action` varchar(255) DEFAULT NULL,
  `Timestamp` datetime DEFAULT current_timestamp(),
  `type` varchar(50) DEFAULT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officeaction`
--

CREATE TABLE `officeaction` (
  `ActionID` int(11) NOT NULL,
  `AppID` int(11) DEFAULT NULL,
  `DateReceived` date DEFAULT NULL,
  `Deadline` date DEFAULT NULL,
  `Type` varchar(100) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ownershipofinvention`
--

CREATE TABLE `ownershipofinvention` (
  `disc_ID` int(11) NOT NULL,
  `usr_ID` int(11) NOT NULL,
  `ContributionPercentage` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patent`
--

CREATE TABLE `patent` (
  `Patent_ID` int(11) NOT NULL,
  `Number` varchar(100) NOT NULL,
  `GrantDate` date DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `Expiration` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patentapplication`
--

CREATE TABLE `patentapplication` (
  `AppID` int(11) NOT NULL,
  `disc_ID` int(11) DEFAULT NULL,
  `appNum` varchar(100) DEFAULT NULL,
  `FilingDate` date DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patentfee`
--

CREATE TABLE `patentfee` (
  `patentfeeID` int(11) NOT NULL,
  `feeScheduleID` int(11) DEFAULT NULL,
  `Patent_ID` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `feeType` varchar(50) DEFAULT NULL,
  `calculatedAmount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE `portfolio` (
  `portfolio_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `CreatedDate` date DEFAULT NULL,
  `Score` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prior_art`
--

CREATE TABLE `prior_art` (
  `Prior_ID` int(11) NOT NULL,
  `disc_ID` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `royaltypayment`
--

CREATE TABLE `royaltypayment` (
  `royaltyID` int(11) NOT NULL,
  `LicenseID` int(11) DEFAULT NULL,
  `Amount` decimal(12,2) NOT NULL,
  `Currency` varchar(3) DEFAULT NULL,
  `calculationMethod` varchar(100) DEFAULT NULL,
  `DistributionStatus` varchar(50) DEFAULT NULL,
  `PmtDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `usr_ID` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `Email` varchar(255) NOT NULL,
  `Role` varchar(50) NOT NULL,
  `pwd_hash` varchar(255) NOT NULL,
  `2FA_Enabled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workflow`
--

CREATE TABLE `workflow` (
  `workflow_ID` int(11) NOT NULL,
  `disc_ID` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Stepname` varchar(100) DEFAULT NULL,
  `Order` int(11) DEFAULT NULL,
  `isFinalStep` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`AlertID`),
  ADD KEY `AppID` (`AppID`),
  ADD KEY `ActionID` (`ActionID`),
  ADD KEY `SentAlertTo` (`SentAlertTo`),
  ADD KEY `patentfeeID` (`patentfeeID`);

--
-- Indexes for table `claim`
--
ALTER TABLE `claim`
  ADD PRIMARY KEY (`Claim_ID`),
  ADD KEY `Patent_ID` (`Patent_ID`),
  ADD KEY `DependentClaimID` (`DependentClaimID`);

--
-- Indexes for table `coverdterritory`
--
ALTER TABLE `coverdterritory`
  ADD PRIMARY KEY (`PatentAppID`,`JurisdictionalID`),
  ADD KEY `JurisdictionalID` (`JurisdictionalID`);

--
-- Indexes for table `disclosure`
--
ALTER TABLE `disclosure`
  ADD PRIMARY KEY (`disc_ID`),
  ADD KEY `Classification_ID` (`Classification_ID`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`document_ID`),
  ADD KEY `evidence_ID` (`evidence_ID`);

--
-- Indexes for table `evidence_vault`
--
ALTER TABLE `evidence_vault`
  ADD PRIMARY KEY (`evidence_ID`),
  ADD KEY `disc_ID` (`disc_ID`);

--
-- Indexes for table `feeschedule`
--
ALTER TABLE `feeschedule`
  ADD PRIMARY KEY (`feeScheduleID`);

--
-- Indexes for table `generatedportfolio`
--
ALTER TABLE `generatedportfolio`
  ADD PRIMARY KEY (`portfolio_ID`,`Patent_ID`),
  ADD KEY `Patent_ID` (`Patent_ID`);

--
-- Indexes for table `grantedpatents`
--
ALTER TABLE `grantedpatents`
  ADD PRIMARY KEY (`Patent_ID`,`AppID`),
  ADD KEY `AppID` (`AppID`);

--
-- Indexes for table `ipc_classification`
--
ALTER TABLE `ipc_classification`
  ADD PRIMARY KEY (`Classification_ID`);

--
-- Indexes for table `jursidiction`
--
ALTER TABLE `jursidiction`
  ADD PRIMARY KEY (`JurisdictionalID`);

--
-- Indexes for table `licenseagreement`
--
ALTER TABLE `licenseagreement`
  ADD PRIMARY KEY (`LicenseID`),
  ADD KEY `Patent_ID` (`Patent_ID`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `usr_ID` (`usr_ID`);

--
-- Indexes for table `officeaction`
--
ALTER TABLE `officeaction`
  ADD PRIMARY KEY (`ActionID`),
  ADD KEY `AppID` (`AppID`);

--
-- Indexes for table `ownershipofinvention`
--
ALTER TABLE `ownershipofinvention`
  ADD PRIMARY KEY (`disc_ID`,`usr_ID`),
  ADD KEY `usr_ID` (`usr_ID`);

--
-- Indexes for table `patent`
--
ALTER TABLE `patent`
  ADD PRIMARY KEY (`Patent_ID`),
  ADD UNIQUE KEY `Number` (`Number`);

--
-- Indexes for table `patentapplication`
--
ALTER TABLE `patentapplication`
  ADD PRIMARY KEY (`AppID`),
  ADD UNIQUE KEY `appNum` (`appNum`),
  ADD KEY `disc_ID` (`disc_ID`);

--
-- Indexes for table `patentfee`
--
ALTER TABLE `patentfee`
  ADD PRIMARY KEY (`patentfeeID`),
  ADD KEY `feeScheduleID` (`feeScheduleID`),
  ADD KEY `Patent_ID` (`Patent_ID`);

--
-- Indexes for table `portfolio`
--
ALTER TABLE `portfolio`
  ADD PRIMARY KEY (`portfolio_ID`);

--
-- Indexes for table `prior_art`
--
ALTER TABLE `prior_art`
  ADD PRIMARY KEY (`Prior_ID`),
  ADD KEY `disc_ID` (`disc_ID`);

--
-- Indexes for table `royaltypayment`
--
ALTER TABLE `royaltypayment`
  ADD PRIMARY KEY (`royaltyID`),
  ADD KEY `LicenseID` (`LicenseID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`usr_ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `workflow`
--
ALTER TABLE `workflow`
  ADD PRIMARY KEY (`workflow_ID`),
  ADD KEY `disc_ID` (`disc_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `AlertID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `claim`
--
ALTER TABLE `claim`
  MODIFY `Claim_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disclosure`
--
ALTER TABLE `disclosure`
  MODIFY `disc_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `document_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evidence_vault`
--
ALTER TABLE `evidence_vault`
  MODIFY `evidence_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feeschedule`
--
ALTER TABLE `feeschedule`
  MODIFY `feeScheduleID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipc_classification`
--
ALTER TABLE `ipc_classification`
  MODIFY `Classification_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jursidiction`
--
ALTER TABLE `jursidiction`
  MODIFY `JurisdictionalID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `licenseagreement`
--
ALTER TABLE `licenseagreement`
  MODIFY `LicenseID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officeaction`
--
ALTER TABLE `officeaction`
  MODIFY `ActionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patent`
--
ALTER TABLE `patent`
  MODIFY `Patent_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patentapplication`
--
ALTER TABLE `patentapplication`
  MODIFY `AppID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patentfee`
--
ALTER TABLE `patentfee`
  MODIFY `patentfeeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portfolio`
--
ALTER TABLE `portfolio`
  MODIFY `portfolio_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prior_art`
--
ALTER TABLE `prior_art`
  MODIFY `Prior_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `royaltypayment`
--
ALTER TABLE `royaltypayment`
  MODIFY `royaltyID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `usr_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workflow`
--
ALTER TABLE `workflow`
  MODIFY `workflow_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`AppID`) REFERENCES `patentapplication` (`AppID`) ON DELETE CASCADE,
  ADD CONSTRAINT `alerts_ibfk_2` FOREIGN KEY (`ActionID`) REFERENCES `officeaction` (`ActionID`) ON DELETE SET NULL,
  ADD CONSTRAINT `alerts_ibfk_3` FOREIGN KEY (`SentAlertTo`) REFERENCES `user` (`usr_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `alerts_ibfk_4` FOREIGN KEY (`patentfeeID`) REFERENCES `patentfee` (`patentfeeID`) ON DELETE SET NULL;

--
-- Constraints for table `claim`
--
ALTER TABLE `claim`
  ADD CONSTRAINT `claim_ibfk_1` FOREIGN KEY (`Patent_ID`) REFERENCES `patent` (`Patent_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `claim_ibfk_2` FOREIGN KEY (`DependentClaimID`) REFERENCES `claim` (`Claim_ID`) ON DELETE SET NULL;

--
-- Constraints for table `coverdterritory`
--
ALTER TABLE `coverdterritory`
  ADD CONSTRAINT `coverdterritory_ibfk_1` FOREIGN KEY (`PatentAppID`) REFERENCES `patentapplication` (`AppID`) ON DELETE CASCADE,
  ADD CONSTRAINT `coverdterritory_ibfk_2` FOREIGN KEY (`JurisdictionalID`) REFERENCES `jursidiction` (`JurisdictionalID`) ON DELETE CASCADE;

--
-- Constraints for table `disclosure`
--
ALTER TABLE `disclosure`
  ADD CONSTRAINT `disclosure_ibfk_1` FOREIGN KEY (`Classification_ID`) REFERENCES `ipc_classification` (`Classification_ID`) ON DELETE SET NULL;

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `document_ibfk_1` FOREIGN KEY (`evidence_ID`) REFERENCES `evidence_vault` (`evidence_ID`) ON DELETE CASCADE;

--
-- Constraints for table `evidence_vault`
--
ALTER TABLE `evidence_vault`
  ADD CONSTRAINT `evidence_vault_ibfk_1` FOREIGN KEY (`disc_ID`) REFERENCES `disclosure` (`disc_ID`) ON DELETE CASCADE;

--
-- Constraints for table `generatedportfolio`
--
ALTER TABLE `generatedportfolio`
  ADD CONSTRAINT `generatedportfolio_ibfk_1` FOREIGN KEY (`portfolio_ID`) REFERENCES `portfolio` (`portfolio_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `generatedportfolio_ibfk_2` FOREIGN KEY (`Patent_ID`) REFERENCES `patent` (`Patent_ID`) ON DELETE CASCADE;

--
-- Constraints for table `grantedpatents`
--
ALTER TABLE `grantedpatents`
  ADD CONSTRAINT `grantedpatents_ibfk_1` FOREIGN KEY (`Patent_ID`) REFERENCES `patent` (`Patent_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `grantedpatents_ibfk_2` FOREIGN KEY (`AppID`) REFERENCES `patentapplication` (`AppID`) ON DELETE CASCADE;

--
-- Constraints for table `licenseagreement`
--
ALTER TABLE `licenseagreement`
  ADD CONSTRAINT `licenseagreement_ibfk_1` FOREIGN KEY (`Patent_ID`) REFERENCES `patent` (`Patent_ID`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`usr_ID`) REFERENCES `user` (`usr_ID`) ON DELETE SET NULL;

--
-- Constraints for table `officeaction`
--
ALTER TABLE `officeaction`
  ADD CONSTRAINT `officeaction_ibfk_1` FOREIGN KEY (`AppID`) REFERENCES `patentapplication` (`AppID`) ON DELETE CASCADE;

--
-- Constraints for table `ownershipofinvention`
--
ALTER TABLE `ownershipofinvention`
  ADD CONSTRAINT `ownershipofinvention_ibfk_1` FOREIGN KEY (`disc_ID`) REFERENCES `disclosure` (`disc_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ownershipofinvention_ibfk_2` FOREIGN KEY (`usr_ID`) REFERENCES `user` (`usr_ID`) ON DELETE CASCADE;

--
-- Constraints for table `patentapplication`
--
ALTER TABLE `patentapplication`
  ADD CONSTRAINT `patentapplication_ibfk_1` FOREIGN KEY (`disc_ID`) REFERENCES `disclosure` (`disc_ID`) ON DELETE CASCADE;

--
-- Constraints for table `patentfee`
--
ALTER TABLE `patentfee`
  ADD CONSTRAINT `patentfee_ibfk_1` FOREIGN KEY (`feeScheduleID`) REFERENCES `feeschedule` (`feeScheduleID`) ON DELETE CASCADE,
  ADD CONSTRAINT `patentfee_ibfk_2` FOREIGN KEY (`Patent_ID`) REFERENCES `patent` (`Patent_ID`) ON DELETE CASCADE;

--
-- Constraints for table `prior_art`
--
ALTER TABLE `prior_art`
  ADD CONSTRAINT `prior_art_ibfk_1` FOREIGN KEY (`disc_ID`) REFERENCES `disclosure` (`disc_ID`) ON DELETE CASCADE;

--
-- Constraints for table `royaltypayment`
--
ALTER TABLE `royaltypayment`
  ADD CONSTRAINT `royaltypayment_ibfk_1` FOREIGN KEY (`LicenseID`) REFERENCES `licenseagreement` (`LicenseID`) ON DELETE CASCADE;

--
-- Constraints for table `workflow`
--
ALTER TABLE `workflow`
  ADD CONSTRAINT `workflow_ibfk_1` FOREIGN KEY (`disc_ID`) REFERENCES `disclosure` (`disc_ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
