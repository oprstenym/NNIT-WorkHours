/*
SQLyog Community v8.32 
MySQL - 5.1.49 : Database - workhours
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`workhours` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `workhours`;

/*Table structure for table `changes` */

DROP TABLE IF EXISTS `changes`;

CREATE TABLE `changes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `ChangeNumber` varchar(255) DEFAULT NULL,
  `ProjectID` int(11) DEFAULT NULL,
  `Description` text,
  `StatusID` int(11) DEFAULT NULL,
  `EstHours` varchar(10) DEFAULT NULL,
  `ReleaseID` int(11) DEFAULT NULL,
  `PhaseID` int(11) NOT NULL DEFAULT '28',
  `Cutover` enum('Y','N') NOT NULL DEFAULT 'N',
  `Functional` varchar(10) DEFAULT NULL,
  `System` int(11) DEFAULT NULL,
  `Notes` text,
  PRIMARY KEY (`ID`,`Cutover`),
  UNIQUE KEY `ID` (`ID`),
  UNIQUE KEY `Idx_ChangeNumber` (`ChangeNumber`,`ProjectID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `colors` */

DROP TABLE IF EXISTS `colors`;

CREATE TABLE `colors` (
  `AttributeID` int(11) NOT NULL,
  `Color` varchar(7) NOT NULL DEFAULT '#FFFFFF',
  PRIMARY KEY (`AttributeID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `documents` */

DROP TABLE IF EXISTS `documents`;

CREATE TABLE `documents` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ChangeID` int(11) DEFAULT NULL,
  `Number` varchar(15) DEFAULT NULL,
  `TDSVersion` varchar(5) DEFAULT '1.0',
  `TDSStatus` int(11) NOT NULL DEFAULT '1',
  `TDSApprover` varchar(15) NOT NULL,
  `TESTVersion` varchar(5) DEFAULT '1.0',
  `TESTStatus` int(11) NOT NULL DEFAULT '1',
  `TESTApprover` varchar(15) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `employee` */

DROP TABLE IF EXISTS `employee`;

CREATE TABLE `employee` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ManagerID` int(11) NOT NULL DEFAULT '0',
  `Name` varchar(50) DEFAULT NULL,
  `Surname` varchar(50) NOT NULL,
  `Initials` varchar(5) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(30) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `holidays` */

DROP TABLE IF EXISTS `holidays`;

CREATE TABLE `holidays` (
  `Date` date NOT NULL,
  `Description` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`Date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `projects` */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `ProjectNumber` varchar(255) DEFAULT NULL,
  `CRMName` varchar(255) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `IsBillable` enum('Y','N') NOT NULL DEFAULT 'N',
  `Order` int(11) NOT NULL DEFAULT '1000',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Idx_ProjectNumber` (`ProjectNumber`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `pwa` */

DROP TABLE IF EXISTS `pwa`;

CREATE TABLE `pwa` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `TimeStamp` timestamp NULL DEFAULT NULL,
  `Task` varchar(255) CHARACTER SET latin1 NOT NULL,
  `TimeSpent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Description` text CHARACTER SET latin1,
  `Charged` enum('Y','N') CHARACTER SET latin1 NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Attribute` varchar(70) DEFAULT NULL,
  `Value` varchar(70) DEFAULT NULL,
  `Order` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
