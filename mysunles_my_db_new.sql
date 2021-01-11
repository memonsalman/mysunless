-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `ActiveUser`;
CREATE TABLE `ActiveUser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` varchar(255) DEFAULT NULL,
  `LoginTime` varchar(255) DEFAULT NULL,
  `LogoutTime` varchar(255) DEFAULT NULL,
  `TotalHours` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Activities`;
CREATE TABLE `Activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) DEFAULT NULL,
  `Titile` varchar(255) DEFAULT NULL,
  `createdtime` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `borser` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `attechment`;
CREATE TABLE `attechment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) DEFAULT NULL,
  `fileName` varchar(255) DEFAULT NULL,
  `document` varchar(2550) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `Riminederdate` varchar(10) DEFAULT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `BackupReminder`;
CREATE TABLE `BackupReminder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createdfk` varchar(50) DEFAULT NULL,
  `crateddate` varchar(255) DEFAULT NULL,
  `duration` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ButtonSetting`;
CREATE TABLE `ButtonSetting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `button_name` varchar(266) DEFAULT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `button_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Campaigns`;
CREATE TABLE `Campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Campaigns` varchar(255) DEFAULT NULL,
  `CampaignsCategory` varchar(255) DEFAULT NULL,
  `CampaignsFromName` varchar(255) DEFAULT NULL,
  `CampaignsFrom` varchar(255) DEFAULT NULL,
  `CampaignsTo` varchar(255) DEFAULT NULL,
  `Campaignscc` varchar(255) DEFAULT NULL,
  `Campaignsbcc` varchar(255) DEFAULT NULL,
  `CampaignsSubject` varchar(255) DEFAULT NULL,
  `CampaignsMessage` varchar(255) DEFAULT NULL,
  `TrackOpens` varchar(255) DEFAULT NULL,
  `TrackClicks` varchar(255) DEFAULT NULL,
  `SendCampaignsTimezone` varchar(255) DEFAULT NULL,
  `OnDay` varchar(25) DEFAULT NULL,
  `AtTime` varchar(25) DEFAULT NULL,
  `flowchartdata` varchar(50000) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `Cid` varchar(255) DEFAULT NULL,
  `ctag` varchar(255) DEFAULT NULL,
  `finalstatus` varchar(12) DEFAULT NULL,
  `ctasktitle` varchar(255) DEFAULT NULL,
  `ctaskdec` varchar(5000) DEFAULT NULL,
  `ctaskduedate` varchar(255) DEFAULT NULL,
  `camnotetitle` varchar(255) DEFAULT NULL,
  `camnotedec` varchar(2550) DEFAULT NULL,
  `Camnoteid` varchar(25) DEFAULT NULL,
  `Camtaskid` varchar(25) DEFAULT NULL,
  `WDuration` varchar(255) DEFAULT NULL,
  `DurationType` varchar(255) DEFAULT NULL,
  `OpenDurationType` varchar(255) DEFAULT NULL,
  `Maxwaittimeopen` varchar(255) DEFAULT NULL,
  `clickDurationType` varchar(255) DEFAULT NULL,
  `Maxwaittimeclick` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `CampaignsCategory`;
CREATE TABLE `CampaignsCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CampaignsCategory` varchar(255) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Campaigns_Result`;
CREATE TABLE `Campaigns_Result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` varchar(200) DEFAULT NULL,
  `camid` varchar(200) DEFAULT NULL,
  `tyoe` varchar(255) DEFAULT NULL,
  `datatime` varchar(255) DEFAULT NULL,
  `Operation` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `campaigns_status`;
CREATE TABLE `campaigns_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) DEFAULT NULL,
  `camid` varchar(25) DEFAULT NULL,
  `cameventname` varchar(225) DEFAULT NULL,
  `camevenstatus` varchar(255) DEFAULT NULL,
  `depandid` int(11) DEFAULT NULL,
  `depstatus` varchar(11) DEFAULT NULL,
  `eventid` varchar(100) DEFAULT NULL,
  `eventnumber` varchar(25) DEFAULT NULL,
  `datatime` varchar(255) DEFAULT NULL,
  `createdfk` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Campaigns_Temp`;
CREATE TABLE `Campaigns_Temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `TempName` varchar(255) DEFAULT NULL,
  `flowchartdata` mediumtext,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Category`;
CREATE TABLE `Category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Category` varchar(50) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `CheckTag_Campaigns`;
CREATE TABLE `CheckTag_Campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `ctag` varchar(255) DEFAULT NULL,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `CheckTag_Campaigns_Tem`;
CREATE TABLE `CheckTag_Campaigns_Tem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `ctag` varchar(255) DEFAULT NULL,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Click_Campaigns`;
CREATE TABLE `Click_Campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `clickDurationType` varchar(255) DEFAULT NULL,
  `Maxwaittimeclick` varchar(255) DEFAULT NULL,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Click_Campaigns_Tem`;
CREATE TABLE `Click_Campaigns_Tem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `clickDurationType` varchar(255) DEFAULT NULL,
  `Maxwaittimeclick` varchar(255) DEFAULT NULL,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ClientImg` varchar(255) DEFAULT NULL,
  `ProfileImg` varchar(255) DEFAULT NULL,
  `Solution` text,
  `PrivateNotes` text,
  `Address` text,
  `Zip` varchar(100) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `State` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `fileName` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Client_Campaigns`;
CREATE TABLE `Client_Campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `cid` varchar(255) DEFAULT NULL,
  `cam_status` varchar(255) DEFAULT NULL,
  `createdate` varchar(255) DEFAULT NULL,
  `cratedid` varchar(14) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Client_package_details`;
CREATE TABLE `Client_package_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` varchar(154) DEFAULT NULL,
  `packageid` varchar(255) DEFAULT NULL,
  `createdby` varchar(255) DEFAULT NULL,
  `OrderTime` varchar(255) DEFAULT NULL,
  `Noofvisit` varchar(255) DEFAULT NULL,
  `OrderId` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Commission`;
CREATE TABLE `Commission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SeriveId` int(11) DEFAULT NULL,
  `ProdcutId` int(11) DEFAULT NULL,
  `MembershipId` int(11) DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `serCommissionAmount` varchar(255) DEFAULT NULL,
  `proCommissionAmount` varchar(255) DEFAULT NULL,
  `memCommissionAmount` varchar(255) DEFAULT NULL,
  `Cid` int(11) DEFAULT NULL,
  `InvoiceNumber` varchar(144) DEFAULT NULL,
  `OrderTime` varchar(11) DEFAULT NULL,
  `OrderId` varchar(144) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `CompanyInformation`;
CREATE TABLE `CompanyInformation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CompanyName` varchar(100) DEFAULT NULL,
  `Phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `compimg` varchar(5550) DEFAULT NULL,
  `Address` text,
  `Zip` varchar(50) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `State` varchar(50) DEFAULT NULL,
  `Country` varchar(50) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `ctheme` varchar(255) DEFAULT NULL,
  `customwidget` varchar(2525) DEFAULT NULL,
  `compimg2` varchar(2550) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `CountActivites`;
CREATE TABLE `CountActivites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ClientCreate` varchar(255) DEFAULT NULL,
  `UserCreate` varchar(255) DEFAULT NULL,
  `EmployeeCreate` varchar(255) DEFAULT NULL,
  `AppointmentCreate` varchar(255) DEFAULT NULL,
  `EmailCreate` varchar(255) DEFAULT NULL,
  `SmsCreate` varchar(255) DEFAULT NULL,
  `OredrCreate` varchar(255) DEFAULT NULL,
  `Createid` varchar(255) DEFAULT NULL,
  `CreatedTime` varchar(255) DEFAULT NULL,
  `TodoCreate` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `countries_id` int(11) NOT NULL AUTO_INCREMENT,
  `countries_name` varchar(44) DEFAULT NULL,
  `countries_isd_code` varchar(7) DEFAULT NULL,
  `cid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`countries_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `country_code` char(2) DEFAULT NULL,
  `country_name` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `emailsend`;
CREATE TABLE `emailsend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FromE` varchar(255) DEFAULT NULL,
  `ToE` varchar(255) DEFAULT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  `MessageE` varchar(2550) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `status` enum('1','0') DEFAULT NULL,
  `ccid` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `EmailSetting`;
CREATE TABLE `EmailSetting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fmail` varchar(50) DEFAULT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `smtphost` varchar(50) DEFAULT NULL,
  `toe` varchar(50) DEFAULT NULL,
  `smtpport` varchar(50) DEFAULT NULL,
  `sa` varchar(255) DEFAULT NULL,
  `smtpusername` varchar(50) DEFAULT NULL,
  `smtppassword` varchar(50) DEFAULT NULL,
  `UserID` int(50) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `EmailTempleate`;
CREATE TABLE `EmailTempleate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  `TextMassage` varchar(25500) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `email_data`;
CREATE TABLE `email_data` (
  `email_id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` varchar(255) DEFAULT NULL,
  `camid` varchar(255) DEFAULT NULL,
  `email_open_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`email_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `EventDate` varchar(50) DEFAULT NULL,
  `EventTime` varchar(50) DEFAULT NULL,
  `EventTime2` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Zip` varchar(50) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `State` varchar(50) DEFAULT NULL,
  `CostOfService` varchar(50) DEFAULT NULL,
  `EmailInstruction` varchar(500) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `start_date` varchar(255) DEFAULT NULL,
  `end_date` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `EmailReminder` varchar(2550) DEFAULT NULL,
  `Riminederdate` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `event_defult`;
CREATE TABLE `event_defult` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) DEFAULT NULL,
  `CostOfService` varchar(255) DEFAULT NULL,
  `EmailInstruction` varchar(2550) DEFAULT NULL,
  `EmailReminder` varchar(2550) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `Riminederdate` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faqTitle` varchar(255) DEFAULT NULL,
  `faqDesc` text,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `FullCom`;
CREATE TABLE `FullCom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `message` varchar(2550) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `cid` varchar(25) DEFAULT NULL,
  `Createid` varchar(25) DEFAULT NULL,
  `comtime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `gmail`;
CREATE TABLE `gmail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `gpassword` varchar(255) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `linkdata`;
CREATE TABLE `linkdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_click_datetime` varchar(255) DEFAULT NULL,
  `camid` varchar(22) DEFAULT NULL,
  `cid` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `listofavtar`;
CREATE TABLE `listofavtar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Mail_Campaigns`;
CREATE TABLE `Mail_Campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `CampaignsFrom` varchar(255) DEFAULT NULL,
  `CampaignsFromName` varchar(255) DEFAULT NULL,
  `CampaignsTo` varchar(255) DEFAULT NULL,
  `Campaignscc` varchar(255) DEFAULT NULL,
  `Campaignsbcc` varchar(255) DEFAULT NULL,
  `CampaignsSubject` varchar(255) DEFAULT NULL,
  `CampaignsMessage` varchar(25500) DEFAULT NULL,
  `TrackOpens` varchar(255) DEFAULT NULL,
  `TrackClicks` varchar(255) DEFAULT NULL,
  `SendCampaignsTimezone` varchar(255) DEFAULT NULL,
  `OnDay` varchar(255) DEFAULT NULL,
  `AtTime` varchar(255) DEFAULT NULL,
  `mykey` varchar(10) DEFAULT NULL,
  `mytype` varchar(100) DEFAULT NULL,
  `companywebsite` varchar(2550) DEFAULT NULL,
  `createdfk` varchar(255) DEFAULT NULL,
  `currentdate` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Mail_Campaigns_Tem`;
CREATE TABLE `Mail_Campaigns_Tem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `CampaignsFrom` varchar(255) DEFAULT NULL,
  `CampaignsFromName` varchar(255) DEFAULT NULL,
  `CampaignsTo` varchar(255) DEFAULT NULL,
  `Campaignscc` varchar(255) DEFAULT NULL,
  `Campaignsbcc` varchar(255) DEFAULT NULL,
  `CampaignsSubject` varchar(2550) DEFAULT NULL,
  `CampaignsMessage` varchar(25500) DEFAULT NULL,
  `TrackOpens` varchar(255) DEFAULT NULL,
  `TrackClicks` varchar(255) DEFAULT NULL,
  `SendCampaignsTimezone` varchar(255) DEFAULT NULL,
  `OnDay` varchar(255) DEFAULT NULL,
  `AtTime` varchar(255) DEFAULT NULL,
  `mykey` varchar(10) DEFAULT NULL,
  `mytype` varchar(100) DEFAULT NULL,
  `companywebsite` varchar(2550) DEFAULT NULL,
  `createdfk` varchar(255) DEFAULT NULL,
  `currentdate` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `MemberPackage`;
CREATE TABLE `MemberPackage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) DEFAULT NULL,
  `Price` varchar(10) DEFAULT NULL,
  `Tracking` varchar(50) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `CommissionAmount` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `NotAvailable`;
CREATE TABLE `NotAvailable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `OnDate` varchar(255) DEFAULT NULL,
  `createdfk` varchar(144) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `note`;
CREATE TABLE `note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `noteTitle` varchar(255) DEFAULT NULL,
  `noteDetail` varchar(16000) DEFAULT NULL,
  `noteRelated` varchar(1000) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` enum('0','1') DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `noteandclient`;
CREATE TABLE `noteandclient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `noteid` int(11) DEFAULT NULL,
  `clientid` int(11) DEFAULT NULL,
  `cratedata` datetime DEFAULT NULL,
  `active` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Note_Campaigns`;
CREATE TABLE `Note_Campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(25) DEFAULT NULL,
  `camnotetitle` varchar(225) DEFAULT NULL,
  `camnotedec` mediumtext,
  `mykey` varchar(25) DEFAULT NULL,
  `mytype` varchar(141) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Note_Campaigns_Tem`;
CREATE TABLE `Note_Campaigns_Tem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `camnotetitle` varchar(255) DEFAULT NULL,
  `camnotedec` mediumtext,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Open_Campaigns`;
CREATE TABLE `Open_Campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(110) DEFAULT NULL,
  `OpenDurationType` varchar(111) DEFAULT NULL,
  `Maxwaittimeopen` varchar(112) DEFAULT NULL,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Open_Campaigns_Tem`;
CREATE TABLE `Open_Campaigns_Tem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(200) DEFAULT NULL,
  `OpenDurationType` varchar(200) DEFAULT NULL,
  `Maxwaittimeopen` varchar(255) DEFAULT NULL,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Ordergift`;
CREATE TABLE `Ordergift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `OrderId` int(11) DEFAULT NULL,
  `InvoiceNumber` varchar(255) DEFAULT NULL,
  `Cid` int(11) DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `gSeriveId` int(11) DEFAULT NULL,
  `gServicePrice` varchar(255) DEFAULT NULL,
  `gServiceDiscount` varchar(255) DEFAULT NULL,
  `gServiceDiscoutInParentage` varchar(255) DEFAULT NULL,
  `gServiceFianlPrice` varchar(255) DEFAULT NULL,
  `TotalgiftAmount` varchar(255) DEFAULT NULL,
  `OrderTime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `OrderMaster`;
CREATE TABLE `OrderMaster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceNumber` varchar(255) DEFAULT NULL,
  `ServiceName` varchar(255) DEFAULT NULL,
  `ServicProvider` varchar(255) DEFAULT NULL,
  `ServiceStartTime` varchar(255) DEFAULT NULL,
  `ServicePrice` varchar(255) DEFAULT NULL,
  `ServiceDiscount` varchar(255) DEFAULT NULL,
  `ServiceDiscoutInParentage` varchar(255) DEFAULT NULL,
  `ServiceFianlPrice` varchar(255) DEFAULT NULL,
  `ProdcutName` varchar(255) DEFAULT NULL,
  `ProdcutQuality` varchar(255) DEFAULT NULL,
  `ProductPrice` varchar(255) DEFAULT NULL,
  `ProductDiscount` varchar(255) DEFAULT NULL,
  `ProductDiscountInParentage` varchar(255) DEFAULT NULL,
  `ProductFianlPrice` varchar(255) DEFAULT NULL,
  `MembershipName` varchar(255) DEFAULT NULL,
  `MembershipPrice` varchar(255) DEFAULT NULL,
  `MembershipDiscount` varchar(255) DEFAULT NULL,
  `MemberDiscoutInParentage` varchar(255) DEFAULT NULL,
  `MembershipFianlPrice` varchar(255) DEFAULT NULL,
  `TotalOrderAmount` varchar(255) DEFAULT NULL,
  `TotalseriveAmount` varchar(255) DEFAULT NULL,
  `TotalProductAmount` varchar(255) DEFAULT NULL,
  `TotalMembershipAmount` varchar(255) DEFAULT NULL,
  `GetTotalPoint` int(11) DEFAULT NULL,
  `UsePoint` int(11) DEFAULT NULL,
  `Remainepoints` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `OrderMembership`;
CREATE TABLE `OrderMembership` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `OrderId` int(11) DEFAULT NULL,
  `InvoiceNumber` varchar(255) DEFAULT NULL,
  `Cid` int(11) DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `MembershipId` int(11) DEFAULT NULL,
  `MembershipPrice` varchar(355) DEFAULT NULL,
  `MembershipDiscount` varchar(366) DEFAULT NULL,
  `MemberDiscoutInParentage` varchar(255) DEFAULT NULL,
  `MembershipFianlPrice` varchar(255) DEFAULT NULL,
  `OrderTime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `OrderPackage`;
CREATE TABLE `OrderPackage` (
  `id` int(11) DEFAULT NULL,
  `OrderId` int(11) DEFAULT NULL,
  `InvoiceNumber` varchar(255) DEFAULT NULL,
  `Cid` int(11) DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `OrderTime` varchar(255) DEFAULT NULL,
  `PackageName` varchar(255) DEFAULT NULL,
  `PckageAmount` varchar(255) DEFAULT NULL,
  `Noofvisit` varchar(255) DEFAULT NULL,
  `Package_Autonew` varchar(255) DEFAULT NULL,
  `Package_renwal` varchar(255) DEFAULT NULL,
  `Pckage_carryford` varchar(255) DEFAULT NULL,
  `package_expire_date` varchar(255) DEFAULT NULL,
  `Package_Price` varchar(255) DEFAULT NULL,
  `PackageDiscount` varchar(255) DEFAULT NULL,
  `PacakageDiscounPersentage` varchar(255) DEFAULT NULL,
  `PackageFinalPrice` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `OrderPayment`;
CREATE TABLE `OrderPayment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `OrderId` varchar(25) DEFAULT NULL,
  `Orderdate` varchar(50) DEFAULT NULL,
  `Cid` varchar(25) DEFAULT NULL,
  `Cratedfk` varchar(25) DEFAULT NULL,
  `PaymentType` varchar(200) DEFAULT NULL,
  `Transactionid` varchar(255) DEFAULT NULL,
  `payment_status` varchar(200) DEFAULT NULL,
  `ChequeNumber` varchar(255) DEFAULT NULL,
  `NameOfBank` varchar(255) DEFAULT NULL,
  `submitdate` varchar(255) DEFAULT NULL,
  `tender_id` varchar(2558) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `OrderProduct`;
CREATE TABLE `OrderProduct` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `OrderId` int(11) DEFAULT NULL,
  `InvoiceNumber` varchar(255) DEFAULT NULL,
  `Cid` int(11) DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `ProdcutId` int(11) DEFAULT NULL,
  `ProdcutQuality` varchar(25) DEFAULT NULL,
  `ProductPrice` varchar(255) DEFAULT NULL,
  `ProductDiscount` varchar(255) DEFAULT NULL,
  `ProductDiscountInParentage` varchar(255) DEFAULT NULL,
  `ProductFianlPrice` varchar(255) DEFAULT NULL,
  `OrderTime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `OrderServic`;
CREATE TABLE `OrderServic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `OrderId` int(11) DEFAULT NULL,
  `InvoiceNumber` varchar(255) DEFAULT NULL,
  `Cid` int(11) DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `SeriveId` int(11) DEFAULT NULL,
  `ServicProvider` varchar(255) DEFAULT NULL,
  `ServiceStartTime` varchar(255) DEFAULT NULL,
  `ServicePrice` varchar(255) DEFAULT NULL,
  `ServiceDiscount` varchar(255) DEFAULT NULL,
  `ServiceDiscoutInParentage` varchar(255) DEFAULT NULL,
  `ServiceFianlPrice` varchar(255) DEFAULT NULL,
  `OrderTime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `package`;
CREATE TABLE `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `PackageName` varchar(255) DEFAULT NULL,
  `Price` float DEFAULT NULL,
  `UsersLimit` int(10) DEFAULT NULL,
  `ClientsLimit` int(11) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `PageTitle`;
CREATE TABLE `PageTitle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `TitleName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `PackageType` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `userid` varchar(255) DEFAULT NULL,
  `paytime` varchar(255) DEFAULT NULL,
  `packend` varchar(255) DEFAULT NULL,
  `subscriptionId` varchar(255) DEFAULT NULL,
  `status` enum('Active','Canceled','InActive') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `paymentsetup`;
CREATE TABLE `paymentsetup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) DEFAULT NULL,
  `UserID` varchar(50) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Post`;
CREATE TABLE `Post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `PostTitle` varchar(255) DEFAULT NULL,
  `PostDec` varchar(25500) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Product`;
CREATE TABLE `Product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barcode` varchar(100) DEFAULT NULL,
  `ProductTitle` varchar(50) DEFAULT NULL,
  `ProductDescription` varchar(150) DEFAULT NULL,
  `CompanyCost` float DEFAULT NULL,
  `SellingPrice` float DEFAULT NULL,
  `ProductCategory` varchar(200) DEFAULT NULL,
  `ProductBrand` varchar(200) DEFAULT NULL,
  `ProductImage` varchar(50) DEFAULT NULL,
  `NoofPorduct` int(11) DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `discountinparst` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ProductBrand`;
CREATE TABLE `ProductBrand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Brand` varchar(50) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ProductCategory`;
CREATE TABLE `ProductCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Category` varchar(50) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `provinces`;
CREATE TABLE `provinces` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `country_id` char(2) DEFAULT NULL,
  `name` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Service`;
CREATE TABLE `Service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ServiceName` varchar(255) DEFAULT NULL,
  `Price` varchar(25) DEFAULT NULL,
  `Duration` varchar(255) DEFAULT NULL,
  `Category` varchar(255) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `Users` varchar(255) DEFAULT NULL,
  `Type` varchar(255) DEFAULT NULL,
  `Info` varchar(255) DEFAULT NULL,
  `starttime` varchar(255) DEFAULT NULL,
  `endtime` varchar(255) DEFAULT NULL,
  `cusmerlimt` int(11) DEFAULT NULL,
  `asper` varchar(50) DEFAULT NULL,
  `CommissionAmount` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `smsdata`;
CREATE TABLE `smsdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ssid` varchar(100) DEFAULT NULL,
  `twillo_from` varchar(255) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `sms` varchar(500) DEFAULT NULL,
  `finalphone` varchar(50) DEFAULT NULL,
  `createddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `SmsSend`;
CREATE TABLE `SmsSend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FromS` varchar(255) DEFAULT NULL,
  `ToS` varchar(255) DEFAULT NULL,
  `Message` varchar(255) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `ccid` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `smssetting`;
CREATE TABLE `smssetting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `Twillo_from` varchar(255) DEFAULT NULL,
  `UserID` varchar(255) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Sms_Campaigns`;
CREATE TABLE `Sms_Campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CampaignssmsMessage` varchar(2555) DEFAULT NULL,
  `CampaignssmsTo` varchar(255) DEFAULT NULL,
  `CampaignssmsFrom` varchar(255) DEFAULT NULL,
  `smsTrackClicks` varchar(255) DEFAULT NULL,
  `mykey` varchar(12) DEFAULT NULL,
  `mytype` varchar(122) DEFAULT NULL,
  `cam_id` varchar(58) DEFAULT NULL,
  `sid` varchar(2222) DEFAULT NULL,
  `token` varchar(2554) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Sms_Campaigns_Tem`;
CREATE TABLE `Sms_Campaigns_Tem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CampaignssmsMessage` varchar(2555) DEFAULT NULL,
  `CampaignssmsTo` varchar(255) DEFAULT NULL,
  `CampaignssmsFrom` varchar(255) DEFAULT NULL,
  `smsTrackClicks` varchar(255) DEFAULT NULL,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(255) DEFAULT NULL,
  `cam_id` varchar(255) DEFAULT NULL,
  `sid` varchar(2222) DEFAULT NULL,
  `token` varchar(2554) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `subscriber`;
CREATE TABLE `subscriber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sname` varchar(255) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) DEFAULT NULL,
  `tagRelated` varchar(1000) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tagandclient`;
CREATE TABLE `tagandclient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tagid` int(11) DEFAULT NULL,
  `clientid` int(11) DEFAULT NULL,
  `createdate` datetime DEFAULT NULL,
  `active` enum('1','0') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Tag_Campaigns`;
CREATE TABLE `Tag_Campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(14) DEFAULT NULL,
  `ctag` varchar(255) DEFAULT NULL,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(100) DEFAULT NULL,
  `tagaction` varchar(225) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Tag_Campaigns_Tem`;
CREATE TABLE `Tag_Campaigns_Tem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `ctag` varchar(255) DEFAULT NULL,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(255) DEFAULT NULL,
  `tagaction` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Task_Campaigns`;
CREATE TABLE `Task_Campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `ctasktitle` varchar(255) DEFAULT NULL,
  `ctaskdec` varchar(2550) DEFAULT NULL,
  `ctaskduedate` varchar(255) DEFAULT NULL,
  `mykey` varchar(10) DEFAULT NULL,
  `mytype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Task_Campaigns_Tem`;
CREATE TABLE `Task_Campaigns_Tem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `ctasktitle` varchar(255) DEFAULT NULL,
  `ctaskdec` varchar(2550) DEFAULT NULL,
  `ctaskduedate` varchar(366) DEFAULT NULL,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `timezone`;
CREATE TABLE `timezone` (
  `zone_id` int(10) NOT NULL AUTO_INCREMENT,
  `TimeZoneName` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `todo`;
CREATE TABLE `todo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `todoTitle` varchar(255) DEFAULT NULL,
  `todoDesc` varchar(21844) DEFAULT NULL,
  `dueDate` varchar(50) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `updatedfk` int(11) DEFAULT NULL,
  `isactive` int(11) DEFAULT NULL,
  `datelastupdated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `todocat`;
CREATE TABLE `todocat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createdfk` int(11) DEFAULT NULL,
  `status` enum('0','1') DEFAULT NULL,
  `closedby` int(11) DEFAULT NULL,
  `closeddate` datetime DEFAULT NULL,
  `catname` varchar(255) DEFAULT NULL,
  `createddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `todocomment`;
CREATE TABLE `todocomment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` varchar(6000) DEFAULT NULL,
  `todoid` int(11) DEFAULT NULL,
  `createddate` datetime DEFAULT NULL,
  `createdfk` int(11) DEFAULT NULL,
  `status` enum('0','1') DEFAULT NULL,
  `closedby` int(11) DEFAULT NULL,
  `closeddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `todohistory`;
CREATE TABLE `todohistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `closedby` int(11) DEFAULT NULL,
  `type` varchar(500) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `closeddate` datetime DEFAULT NULL,
  `taskid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `totalgiftdata`;
CREATE TABLE `totalgiftdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  `totalgiftbal` varchar(110) DEFAULT NULL,
  `usedbal` varchar(110) DEFAULT NULL,
  `createddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Tutorial`;
CREATE TABLE `Tutorial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `TutorialMsg` varchar(2550) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adminid` int(10) DEFAULT NULL,
  `username` varchar(25) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `phonenumber` varchar(15) DEFAULT NULL,
  `companyname` varchar(50) DEFAULT NULL,
  `companytype` varchar(50) DEFAULT NULL,
  `companywebsite` varchar(100) DEFAULT NULL,
  `primaryaddress` text,
  `secondaryaddress` text,
  `zipcode` int(11) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `state` varchar(25) DEFAULT NULL,
  `usertype` int(11) DEFAULT NULL,
  `userimg` varchar(255) DEFAULT NULL,
  `status` enum('Active','InActive') DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `emailstatus` enum('0','1') DEFAULT NULL,
  `emaisendtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Wait_Campaigns`;
CREATE TABLE `Wait_Campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(110) DEFAULT NULL,
  `WDuration` varchar(252) DEFAULT NULL,
  `DurationType` varchar(255) DEFAULT NULL,
  `waitCampaignsTimezone` varchar(255) DEFAULT NULL,
  `wAtTime` varchar(255) DEFAULT NULL,
  `mykey` varchar(14) DEFAULT NULL,
  `mytype` varchar(110) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Wait_Campaigns_Tem`;
CREATE TABLE `Wait_Campaigns_Tem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cam_id` varchar(255) DEFAULT NULL,
  `WDuration` varchar(255) DEFAULT NULL,
  `DurationType` varchar(255) DEFAULT NULL,
  `waitCampaignsTimezone` varchar(255) DEFAULT NULL,
  `wAtTime` varchar(255) DEFAULT NULL,
  `mykey` varchar(255) DEFAULT NULL,
  `mytype` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- 2021-01-11 07:49:38
