-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 27, 2017 at 12:23 AM
-- Server version: 5.7.19-0ubuntu0.16.04.1
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_purchasing`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE IF NOT EXISTS `attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_id` int(11) DEFAULT NULL,
  `file_name` varchar(250) DEFAULT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `full_path` varchar(255) DEFAULT NULL,
  `raw_name` varchar(200) DEFAULT NULL,
  `orig_name` varchar(200) DEFAULT NULL,
  `client_name` varchar(200) DEFAULT NULL,
  `file_ext` varchar(10) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `is_image` tinyint(1) DEFAULT '0',
  `image_width` int(11) DEFAULT NULL,
  `image_height` int(11) DEFAULT NULL,
  `image_type` varchar(10) DEFAULT NULL,
  `image_size_str` varchar(50) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `board_approval`
--

CREATE TABLE IF NOT EXISTS `board_approval` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` float(10,2) DEFAULT NULL,
  `requestor_note` text,
  `board_note` text,
  `status` tinyint(4) DEFAULT '0',
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE IF NOT EXISTS `branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `budget_approval`
--

CREATE TABLE IF NOT EXISTS `budget_approval` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` float(10,2) DEFAULT '0.00',
  `canvass_id` int(11) DEFAULT NULL,
  `requestor_note` text,
  `user_response` text,
  `user_from` int(11) DEFAULT NULL,
  `budget_note` text,
  `status` int(11) DEFAULT '0',
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `canvass`
--

CREATE TABLE IF NOT EXISTS `canvass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` float(10,2) DEFAULT NULL,
  `init_canvass_date` datetime DEFAULT NULL,
  `canvass_message` text,
  `user_response` text,
  `user_from` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `archive` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_id` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `canvassed_prices`
--

CREATE TABLE IF NOT EXISTS `canvassed_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) DEFAULT NULL,
  `canvass_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_description` varchar(255) DEFAULT NULL,
  `supplier` varchar(150) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT '0',
  `price` float(9,2) DEFAULT NULL,
  `total_amount` float(9,2) NOT NULL DEFAULT '0.00',
  `quantity_type` varchar(50) DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `archive` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `department_head` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_key` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `status` tinyint(4) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `emails`
--

INSERT INTO `emails` (`id`, `process_key`, `subject`, `message`, `status`, `date_created`, `date_updated`) VALUES
(1, 'new_request', 'New Request Created', '<p><span style="font-family:Verdana,Geneva,sans-serif"><span style="font-size:12px">We have received a Purchase Request for your Review.</span></span></p>\n\n<p>[name_from]</p>\n\n<p>[request_number]</p>\n\n<p>[request_name]</p>\n\n<p>[date_needed]</p>\n\n<p><span style="font-family:Verdana,Geneva,sans-serif"><span style="font-size:12px">This email is an automated notification, which is unable to receive replies. &nbsp;Please log in to your account to view details.</span></span></p>\n\n<p>&nbsp;</p>\n', NULL, '2017-03-15 20:57:49', NULL),
(3, 'request_filed', 'New Request has been filed', '<p><span style="font-family:Verdana,Geneva,sans-serif"><span style="font-size:12px">[name_to]</span></span></p>\n\n<p><span style="font-family:Verdana,Geneva,sans-serif"><span style="font-size:12px">[request_number] has been filed by [name_from]</span></span></p>\n\n<p><span style="font-family:Verdana,Geneva,sans-serif"><span style="font-size:12px">This has been added to your worklist for assignment.</span></span></p>\n\n<p><span style="font-family:Verdana,Geneva,sans-serif"><span style="font-size:12px">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-15 22:40:45', NULL),
(4, 'to_canvass', 'New Request to Canvass', '<p><span style="font-family:Verdana,Geneva,sans-serif"><span style="font-size:12px">[name_to]</span></span></p>\n\n<p><span style="font-family:Verdana,Geneva,sans-serif"><span style="font-size:12px">This [request_number] has been assigned to you by [name_from].</span></span></p>\n\n<p><span style="font-family:Verdana,Geneva,sans-serif"><span style="font-size:12px">This has been added to your worklist and requires 48-72 hours update in status.</span></span></p>\n\n<p><span style="font-family:Verdana,Geneva,sans-serif"><span style="font-size:12px">This email is an automated notification, which unable to receive replies. Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-15 23:07:27', NULL),
(5, 'to_budget', 'New Budget Approval', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">A request has been reviewed by [name_from] for your further approval. &nbsp;This request is under [request_number] for [request_name].</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Please login to your account should you need further validation.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies.</span></span></p>\n', NULL, '2017-03-16 00:25:40', NULL),
(6, 'budget_denied', 'Your request has been denied by budget department', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Unfortunately, after careful review of your request under [request_number], we are unable to proceed on processing this&nbsp;request. Kindly see validated reason below:</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Please check for some other options. &nbsp;This request will be cancelled. You have the option to re-open by responding to the comment box.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_from]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-17 05:02:25', NULL),
(7, 'response_to_budget', 'Return Message for Budget Denied', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Thank you for your effort to review the request submitted. It will be appreciated to revisit the said request with the reconsideration reasons below:</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_from]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-17 07:36:57', NULL),
(8, 'budget_approved', 'Budget Approved', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Your budget request for [request_name] under [request_number] has been approved. Kindly submit Purchase Order to Auditor for implementation of request.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Please note that requests for more than Php 250,000 will be subject for Board Approval and will take more time to confirm completion.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-17 08:16:35', NULL),
(9, 'board_approved', 'Request Approved by Board', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Your request for [request_name] under [request_number] has been approved by the Board of Directors. &nbsp;Kindly submit Purchase Order to Auditor for implementation of request.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Please note that all requests more than Php 1,000,000 needs Recommending Approval before final Board Approval.&nbsp;</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_from]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-17 08:17:15', NULL),
(10, 'item_denied', 'Item Denied', '<p><span style="font-family:Verdana,Geneva,sans-serif">[name_to],</span></p>\n\n<p><span style="font-family:Verdana,Geneva,sans-serif">Your item named [item_name] under [request_number] has been denied by [name_from]. Please see reason below:</span></p>\n\n<p><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></p>\n\n<p><span style="font-family:Verdana, Geneva, sans-serif">This email is an automated notification, which is unable to receive replies. &nbsp;Please login to your account to view details.</span></p>\n\n<p>&nbsp;</p>\n', NULL, '2017-03-17 10:40:46', NULL),
(11, 'for_audit', 'New Purchase Order for Audit', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">A request for Purchase Order has been submitted for your review and approval. This has reference to [request_number] for the [request_name]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_from]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n\n<p>&nbsp;</p>\n', NULL, '2017-03-17 13:06:32', NULL),
(12, 'return_audit', 'Your PO returned', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">The Purchase Order [po_num] has been returned for futher validation.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This request will be pended until further clarification of the message above.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_from]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n\n<p>&nbsp;</p>\n', NULL, '2017-03-17 20:40:35', NULL),
(13, 'reupdate_po', 'Purchase Order Updated', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">The Purchase Order ([po_num]) has been updated and needs your review.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_from]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Your immediate action is very much appreciated.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-17 20:56:48', NULL),
(14, 'po_audited', 'Purchase Order Approved', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">The Purchase Order [po_num] has been completed. &nbsp;Please reference to the Delivery Receipt if needed.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-17 21:00:40', NULL),
(15, 'request_done', 'Purchase Request Approved', '<p>[name_to]</p>\n\n<p>Good news!  Your purchase request has been approved under [request_number].  Please refer to Delivery Receipts of your request. </p>\n\n<p>This email is an automated notification, which unable to receive replies.  Please login to your account to view full details.</p>\n\n<p> </p>\n', NULL, '2017-03-17 21:06:14', NULL),
(16, 'board_approval', 'Board Approval', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">A purchase request for [request_name] under [po_number] has been filed and reviewed by [name_from]. &nbsp;Total amount of purchase reached more than Php 1,000,000.00 and needs your prior approval before release.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Please note that all requests more than Php 1,000,000 needs Recommending Approval before final Board Approval.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-17 21:18:09', NULL),
(17, 'response_to_head', 'User Responded to the Item denied', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_from] responded to the item you have&nbsp;denied under [request_number]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which is unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-22 18:40:50', NULL),
(18, 'to_admin_review', 'Request Review before budget approval', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[request_name] under [request_number] assigned to [name_from] requested you to review submitted&nbsp;canvass for budget approval.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This action must be taken within 24-48 hours.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-26 18:59:00', NULL),
(19, 'to_canvass_edit', 'Request returned by Admin', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">The request [request_name] under [request_number] that was assigned to you&nbsp;has been returned by [name_from] for clarification. &nbsp;See details below:</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Please respond within 24 hours. &nbsp;If no response received, request may be cancelled.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-26 20:24:17', NULL),
(20, 'to_admin_response_review', 'Request Return by canvasser to review', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_from] assigned for the&nbsp;request [request_name] under [request_number] has responded to you for another review.&nbsp;See details below:</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-26 20:52:17', NULL),
(21, 'canvasser_to_requisitioner', 'Message from the Canvasser', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">We have reviewed your request under [request_number] and currently working on&nbsp;it. &nbsp;In order to move forward, kindly clarify issues below:</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Please respond within 24-48 hours. &nbsp;If no response received, this request will be cancelled. Please create another ticket for any other requests.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_from]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-26 22:31:09', NULL),
(22, 'requisitioner_response_to_canvasser', 'Message to the Canvasser', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Thank you for reviewing my request under [request_number]. See clarification below:</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_from]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-26 22:38:09', NULL),
(23, 'admin_to_requisitioner', 'Message from Admin', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Your [request_number] for [request_name] needs further clarification and pending for review. &nbsp;Please validate the following below:</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This request will remain open once issues above are resolved within the timeframe allowed and will be cancelled if not. &nbsp;Always check status of your request by logging in to your account.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_from]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-26 22:43:17', NULL),
(24, 'requisitioner_response_to_admin', 'Message to Admin', '<p>[name_to]</p>\n\n<p>Thank you for reviewing my request under [request_number]. See clarification below:</p>\n\n<p>[message]</p>\n\n<p>[name_from]</p>\n\n<p>This email is an automated notification which unable to receive replies. &nbsp;Please login to your account to view full details.</p>\n', NULL, '2017-03-26 22:44:36', NULL),
(25, 'budget_return', 'Message from Budget to Admin', '<p>[name_to]</p>\n\n<p>Unfortunately, after careful review of your request under [request_number], we are unable to proceed on processing this&nbsp;request. Kindly see validated reason below:</p>\n\n<p>[message]</p>\n\n<p>Please check for some other options. &nbsp;This request will be cancelled. You have the option to re-open by responding to the comment box.</p>\n\n<p>[name_from]</p>\n\n<p>This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</p>\n\n<p>&nbsp;</p>\n', NULL, '2017-03-27 00:50:18', NULL),
(26, 'admin_to_budget', 'Message from Admin to Budget', '<p>[name_to]</p>\n\n<p>Thank you for your effort to review the request submitted. It will be appreciated to revisit the said request with the reconsideration reasons below:</p>\n\n<p>[message]</p>\n\n<p>[name_from]</p>\n\n<p>This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</p>\n', NULL, '2017-03-27 00:58:22', NULL),
(27, 'purchasing_commitee_approval', 'Purchasing Committee Approval', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">A purchase request for [request_name] under [po_number] has been filed and reviewed by [name_from]. &nbsp;Total amount of purchase reached more than Php 250,000 and needs your prior approval before release.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Please note that all requests more than Php 1,000,000 needs Recommending Approval before final Board Approval.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-28 21:11:35', NULL),
(28, 'remove_item', 'Your Purchase Request has been modified', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">Purchase Request [request_number] has been modified by [name_from]. [item_name] has been removed.</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[message]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-03-29 22:17:34', NULL),
(29, 'new_request_assigned', 'New Request Assigned', '<p>[name_to]</p>\n\n<p>New request [request_name] under request number [request_number] has been assigned to by [name_from]</p>\n\n<p>Please check</p>\n', NULL, '2017-03-31 00:14:56', NULL),
(30, 'delivery_to_property', 'Items on your way', '<p>Hi [name_to],</p>\n\n<p>There are items on delivery from the request named [request_name].</p>\n\n<p>Please login to see full details</p>\n', NULL, '2017-04-04 23:29:13', NULL),
(31, 'board_denied', 'Your request denied by board', '<p>Hi [name_to],</p>\n\n<p>Your request denied by board. for this reason</p>\n\n<p>[message]</p>\n', NULL, '2017-04-14 08:27:48', NULL),
(32, 'response_to_board', 'Board: User Response', '<p>Hi [name_to],</p>\n\n<p>[name_from] responded to request [request_name]</p>\n\n<p>Reason:</p>\n\n<p>[message]</p>\n', NULL, '2017-04-14 09:18:04', NULL),
(33, 'recommending_purchasing_commitee_approval', 'Recommending Purchasing Committee Approval', '<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">[name_to]</span></span></p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">A purchase request for [request_name] under [po_number] has been filed and reviewed by [name_from]. &nbsp;Total amount of purchase reached more than Php 1,000,000 and needs your prior approval before release.</span></span></p>\n\n<p>&nbsp;</p>\n\n<p><span style="font-size:12px"><span style="font-family:Verdana,Geneva,sans-serif">This email is an automated notification, which unable to receive replies. &nbsp;Please login to your account to view full details.</span></span></p>\n', NULL, '2017-06-08 22:11:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(3, 'property', 'Property Department'),
(4, 'board', 'Board'),
(5, 'auditor', 'Auditor'),
(6, 'budget', 'Budget'),
(7, 'canvasser', 'Canvasser'),
(8, 'members', 'General User');

-- --------------------------------------------------------

--
-- Table structure for table `items_approval`
--

CREATE TABLE IF NOT EXISTS `items_approval` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `request_approval_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `requestor_note` text,
  `officers_note` text,
  `status` int(11) DEFAULT '0',
  `archive` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `request_approval_id` (`request_approval_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `po_items`
--

CREATE TABLE IF NOT EXISTS `po_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) DEFAULT NULL,
  `po_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_code` varchar(150) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `unit_measurement` varchar(20) DEFAULT NULL,
  `product_description` text,
  `job` varchar(255) DEFAULT NULL,
  `unit_price` double(10,2) DEFAULT '0.00',
  `supplier` varchar(150) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT '0',
  `quantity` int(11) DEFAULT '0',
  `expenditure` varchar(50) DEFAULT NULL,
  `requestor_note` text,
  `purchasing_note` text,
  `remarks` text,
  `status` tinyint(4) DEFAULT '0',
  `archive` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `product_category` int(11) DEFAULT NULL,
  `product_number` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `info_id` int(11) DEFAULT NULL,
  `status` varchar(25) DEFAULT '1',
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE IF NOT EXISTS `product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT '1',
  `parent` int(11) DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product_information`
--

CREATE TABLE IF NOT EXISTS `product_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `picture_path` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE IF NOT EXISTS `property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `po_id` int(11) DEFAULT NULL,
  `property_name` varchar(150) DEFAULT NULL,
  `eta` datetime DEFAULT NULL,
  `date_delivered` datetime DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `user_note` text,
  `user_from` int(11) DEFAULT NULL,
  `officer_note` text,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `archive` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `property_item`
--

CREATE TABLE IF NOT EXISTS `property_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `po_id` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `eta` datetime DEFAULT NULL,
  `status` tinyint(2) DEFAULT '0',
  `archive` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE IF NOT EXISTS `purchase_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `requestor` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `pr_number` varchar(100) DEFAULT NULL,
  `po_num` varchar(11) DEFAULT NULL,
  `supplier_invoice` varchar(50) DEFAULT NULL,
  `rr_num` varchar(100) DEFAULT NULL,
  `warranty_condition` varchar(255) DEFAULT NULL,
  `requisitioner` varchar(255) DEFAULT NULL,
  `approve_by` varchar(100) DEFAULT NULL,
  `prepared_by` varchar(100) DEFAULT NULL,
  `payment_terms` varchar(50) DEFAULT NULL,
  `customer_number` int(11) DEFAULT NULL,
  `date_issue` datetime DEFAULT NULL,
  `supplier_id` int(11) DEFAULT '0',
  `vendor_name` varchar(255) DEFAULT NULL,
  `vendor_company_name` varchar(255) DEFAULT NULL,
  `vendor_address` varchar(300) DEFAULT NULL,
  `vendor_city` varchar(255) DEFAULT NULL,
  `vendor_phone` varchar(25) DEFAULT NULL,
  `vendor_email` varchar(255) DEFAULT NULL,
  `ship_to_name` varchar(255) DEFAULT NULL,
  `ship_to_company_name` varchar(255) DEFAULT NULL,
  `ship_to_address` varchar(255) DEFAULT NULL,
  `ship_to_city` varchar(255) DEFAULT NULL,
  `ship_to_phone` varchar(255) DEFAULT NULL,
  `ship_to_email` varchar(255) DEFAULT NULL,
  `shipping_method` varchar(255) DEFAULT NULL,
  `shipping_term` varchar(255) DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `status` tinyint(2) DEFAULT '0',
  `archive` tinyint(4) NOT NULL DEFAULT '0',
  `pd_remarks` text,
  `auditor_remarks` text,
  `authorize_by` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  KEY `request_id_2` (`request_id`),
  KEY `request_id_3` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `request_approval`
--

CREATE TABLE IF NOT EXISTS `request_approval` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `head_department_remarks` text,
  `status` int(11) DEFAULT '0',
  `archive` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `request_category`
--

CREATE TABLE IF NOT EXISTS `request_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_category`
--

INSERT INTO `request_category` (`id`, `name`, `description`, `status`, `date_created`, `date_updated`) VALUES
(1, 'General_Purchases', 'all for general use', 0, '2017-10-08 17:12:18', NULL),
(2, 'Inventory_Purchases', 'for direct materials use', 0, '2017-10-08 18:19:20', NULL),
(3, 'Stock_Purchases', 'for capital use', 0, '2017-10-08 18:19:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `request_list`
--

CREATE TABLE IF NOT EXISTS `request_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `request_name` varchar(100) DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `priority_level` int(11) DEFAULT NULL,
  `date_needed` datetime DEFAULT NULL,
  `request_note` text,
  `admin_note` text,
  `user_from` int(11) DEFAULT NULL,
  `dept_head_note` text,
  `canvasser` int(11) DEFAULT NULL,
  `total_amount` float(10,2) DEFAULT '0.00',
  `expenditure` varchar(50) DEFAULT NULL,
  `req_cat` int(11) DEFAULT NULL,
  `approve_purchase_by` int(11) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '1',
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `req_cat` (`req_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `settings` (`id`, `name`, `description`, `status`) VALUES
(1, 'board_approval_amount', '250000', 1),
(2, 'logo', '', 1),
(3, 'company_name', 'ExWeb Company', 1),
(4, 'company_address', 'Salcedo Village, Makati City, Philippines', 1),
(5, 'company_phone', '+63 2 111-1111', 1),
(6, 'penalty_clause', 'ExWeb Company shall be entitled to penalties for late delivery calculated at 5% of the contract price of the goods for every day late, subject to a maximum amount of 100% of the contract price.', 1),
(7, 'PO_note', 'The Supplier shall not be entitled to payment unless:\r\na: the order is complete and no dispute regarding the order is unresolved\r\nb: the delivery and installation of the goods or performance of services conforms in all respects to the quantity, quality, and installation ordered at the prices, discounts and terms ordered\r\nc: the goods have been inspected and found free of defects', 1),
(8, 'PO_reminder', 'Physical acceptance of the goods at the time of delivery shall not constitute complete acceptance of the goods until the goods and documentation have been checked.', 1),
(9, 'with_budget', 'Mr. E', 1),
(10, 'budget_certified_by', 'Mr. X', 1),
(11, 'approve_purchased_by', 'Mr. Webb', 1),
(12, 'announcement', 'Delays of Delivery may be encountered due to bad weather condition.', 1),
(13, 'logo_to_use', 'logo', 1),
(14, 'login_default_users', '0', 1),
(15, 'email_notification', '', 1);
-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(50) DEFAULT NULL,
  `status_number` int(11) DEFAULT NULL,
  `status_text` varchar(100) DEFAULT NULL,
  `status_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;


--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `status_name`, `status_number`, `status_text`, `status_type`) VALUES
(4, 'request', 2, 'Filed, awaiting for process', 'info'),
(5, 'request', 3, 'Canvassing', 'info'),
(6, 'request', 4, 'Forwarded to Budget Approval', 'info'),
(7, 'request', 5, 'Head/Recommending Approval Denied', 'danger'),
(8, 'request', 6, 'Budget Approval Denied', 'danger'),
(9, 'request', 7, 'User Responded to budget', 'warning'),
(10, 'request', 8, 'PO Preparation', 'info'),
(11, 'request', 9, 'Pending Board Approval', 'warning'),
(12, 'request', 10, 'Board Approval Denied', 'danger'),
(13, 'request', 11, 'Responded to board', 'warning'),
(14, 'request', 12, 'Auditor Confirmed, Processing..', 'info'),
(15, 'request', 13, 'Request Process Done', 'success'),
(16, 'request', 14, 'For Purchasing Review', 'info'),
(17, 'request', 15, 'Admin Responded', 'info'),
(18, 'request', 16, 'Canvasser Responded to Admin', 'info'),
(19, 'request', 17, 'Canvasser Returned to Requisitioner', 'info'),
(20, 'request', 18, 'Requisitioner to Canvasser', 'info'),
(21, 'request', 19, 'Admin to Requisitioner', 'info'),
(22, 'request', 20, 'Requisitioner to Admin', 'info'),
(23, 'request', 21, 'Partially Done', 'warning'),
(24, 'request', 22, 'Archived', 'warning'),
(25, 'item_approval', 0, 'Not Yet Reviewed', 'default'),
(26, 'item_approval', 1, 'Approved', 'success'),
(27, 'item_approval', 2, 'Denied', 'danger'),
(28, 'item_approval', 3, 'Users\' Response', 'warning'),
(29, 'req_approval', 0, 'Not Yet Reviewed', 'default'),
(30, 'req_approval', 1, 'Approved', 'success'),
(31, 'req_approval', 2, 'Waiting for others approval', 'warning'),
(32, 'req_approval', 3, 'Denied', 'danger'),
(33, 'req_approval', 4, 'On progress', 'info'),
(34, 'canvass', 1, 'Assigned', 'default'),
(35, 'canvass', 2, 'Updated', 'warning'),
(36, 'canvass', 3, 'Forwarded to Budget', 'info'),
(37, 'canvass', 4, 'Forwarded to Admin', 'info'),
(38, 'canvass', 5, 'Forwarded to Requisitioner', 'info'),
(39, 'canvass', 6, 'Returned by Admin', 'info'),
(40, 'canvass', 7, 'Return to Admin', 'info'),
(41, 'canvass', 8, 'Requisitioner Responded', 'info'),
(42, 'budget', 0, 'Assigned', 'default'),
(43, 'budget', 1, 'PO Preparation', 'success'),
(44, 'budget', 2, 'Denied', 'danger'),
(45, 'budget', 3, 'User Responded', 'warning'),
(46, 'budget', 4, 'Forwarded to Board', 'warning'),
(48, 'budget', 6, 'Admin Responded', 'info'),
(49, 'board', 0, 'Assigned', 'default'),
(50, 'board', 1, 'Approved', 'success'),
(51, 'board', 2, 'Denied', 'danger'),
(52, 'board', 3, 'User Responded', 'warning'),
(53, 'purchase_order', 0, 'Awaiting Auditor confirmation', 'info'),
(54, 'purchase_order', 1, 'Auditor Confirmed', 'success'),
(55, 'purchase_order', 2, 'Auditor Denied', 'danger'),
(56, 'purchase_order', 3, 'Updated By Purchasing', 'warning'),
(57, 'item', 0, 'Processing', 'info'),
(58, 'item', 1, 'Auditor Confirmed', 'success'),
(59, 'item', 2, 'Auditor Denied', 'danger'),
(60, 'item', 3, 'For Delivery', 'warning'),
(61, 'item', 4, 'Delivered', 'warning'),
(62, 'property', 0, 'For Delivery', 'info'),
(63, 'property', 1, 'Delivered', 'success'),
(64, 'request', 1, 'Sent to head department', 'info'),
(65, 'budget', 5, 'PO Preparation', 'success');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(100) DEFAULT NULL,
  `address` text,
  `tel_number` varchar(20) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `fax` varchar(30) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `payment_terms` varchar(50) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE IF NOT EXISTS `transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_table` varchar(100) DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `old_value` text,
  `new_value` text,
  `status` int(11) DEFAULT NULL,
  `archive` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'administrator', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'admin@admin.com', '', NULL, NULL, NULL, 1268889823, 1506440446, 1, 'Admin', 'istrator', 'ADMIN', '0');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_profile`
--

CREATE TABLE IF NOT EXISTS `users_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `midle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `nick_name` varchar(50) DEFAULT NULL,
  `picture_path` varchar(255) DEFAULT NULL,
  `picture_basename` varchar(100) DEFAULT NULL,
  `picture_mime` varchar(20) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `branch` int(11) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `head` tinyint(4) NOT NULL DEFAULT '0',
  `mobile_number` varchar(30) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items_approval`
--
ALTER TABLE `items_approval`
  ADD CONSTRAINT `items_approval_ibfk_1` FOREIGN KEY (`request_approval_id`) REFERENCES `request_approval` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `po_items`
--
ALTER TABLE `po_items`
  ADD CONSTRAINT `po_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request_approval`
--
ALTER TABLE `request_approval`
  ADD CONSTRAINT `request_approval_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
