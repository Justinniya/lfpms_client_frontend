-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2025 at 12:17 PM
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
-- Database: `lfpms`
--

-- --------------------------------------------------------

--
-- Table structure for table `business_information`
--

CREATE TABLE `business_information` (
  `bi_id` int(11) NOT NULL,
  `NBusiness` varchar(255) NOT NULL,
  `BusinessAddress` varchar(255) NOT NULL,
  `TypeBusiness` varchar(255) NOT NULL,
  `brn` varchar(255) NOT NULL,
  `VDocuments` varchar(255) NOT NULL,
  `BLcense` varchar(255) NOT NULL,
  `TDocuments` varchar(255) NOT NULL,
  `BPermit` varchar(255) NOT NULL,
  `BIR` varchar(255) NOT NULL,
  `BBC` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `business_information`
--

INSERT INTO `business_information` (`bi_id`, `NBusiness`, `BusinessAddress`, `TypeBusiness`, `brn`, `VDocuments`, `BLcense`, `TDocuments`, `BPermit`, `BIR`, `BBC`, `userid`) VALUES
(28, 'HF FOOD PRODUCTS', 'Result_WEBI_Seatwork3.pdf', 'Result_WEBI_Seatwork3.pdf', 'Result_WEBI_Seatwork3.pdf', 'Result_WEBI_Seatwork3.pdf', 'Result_WEBI_Seatwork3.pdf', 'Result_WEBI_Seatwork3.pdf', 'Result_WEBI_Seatwork3.pdf', 'Result_WEBI_Seatwork3.pdf', 'Result_WEBI_Seatwork3.pdf', 10),
(29, 'Jave Store', 'Jave St. IC', 'Pastry', '10', '', '', '', '', '', '', 23),
(30, 'Jave Store', 'Jave St. IC', 'Pastry', '10', '', '', '', '', '', '', 24),
(31, '23443', '213', '43324', '342432', '', 'Blue  Professional Certificate of Completion.pdf', 'CHAP-1-2-FOR-HARDBOUND.pdf', 'Blue  Professional Certificate of Completion.pdf', '', '', 29),
(32, '65432', '2', '43324', '2133', '', 'Availbale-Letters-FINAL.docx', 'QF-SIP-07-SIP-Coordinators-Supervisors-Report.docx', 'SUMMARY-BSIS-GUZMAN-ESPINOSA-ASTROMINADA-RULLAN-CANOY-FINAL (1).docx', '', '', 30),
(33, '434', '213', '554', '45544', '', 'QF-SIP-11-SIP-Center-Evaluation-11112021 (1).docx', 'QF-SIP-07-SIP-Coordinators-Supervisors-Report.docx', 'QF-SIP-09-SIP-Training-Plan-11112021.docx', '', '', 33),
(34, '23443', 'asddas', '43324', '3223', '', 'QF-SIP-11-SIP-Center-Evaluation-11112021 (1).docx', 'QF-SIP-07-SIP-Coordinators-Supervisors-Report.docx', 'SUMMARY-BSIS-GUZMAN-ESPINOSA-ASTROMINADA-RULLAN-CANOY-FINAL (1).docx', '', '', 34),
(35, 'Bea Shop', 'Lapaz City Proper', 'Beauty Shop', '123-492', '', '', '', '', '', '', 36),
(36, '32', '34', '3434', '43234', '', '', '', '', '', '', 61),
(37, 'Cafe De Ore', 'Pavia', 'Coffee Shop', '23', '', '', '', '', '', '', 66),
(38, 'dsds', 'dasdas', 'dassad', 'dasdas', '', 'DENOSTA-ANA-MARIA-P..pdf', 'BERMEJO-GRACELDA-.-G..pdf', 'AMBUT-CARMELO-V..pdf', '', '', 71),
(39, 'dsds', 'dasdas', 'dassad', 'dasdas', '', 'DENOSTA-ANA-MARIA-P..pdf', 'BERMEJO-GRACELDA-.-G..pdf', 'AMBUT-CARMELO-V..pdf', '', '', 72),
(40, 'assda', 'sdsdadsa', 'dasdasds', 'dsdsdsa', '', 'DENOSTA-ANA-MARIA-P..pdf', 'SOROLLA-JED-P..pdf', 'BERMEJO-GRACELDA-.-G..pdf', '', '', 73),
(41, 'Nang Palang\'s Pies', 'Brgy. Trapiche, Oton, Philippines', 'Home Bakery Business', '12345', '', 'business license sample.png', 'tax document sample.png', 'business permit sample.png', '', '', 75),
(42, 'De Paul Artisanal Salt Manufacturing', 'Brgy. Mambatad Miagao, Iloilo', 'Food Production Business', '12345', '', 'business license sample.png', 'tax document sample.png', 'business permit sample.png', '', '', 76),
(43, 'D\'Original Jaspe Baye-Baye', 'Brgy. Cabugao Sur, Sta. Barbara, Iloilo', 'Cottage Food Business', '12345', '', 'business license sample.png', 'tax document sample.png', 'business permit sample.png', '', '', 77),
(44, 'El\'s Dry Goods', 'Brgy. Baguingin, Tigbauan, Iloilo', 'Home Base Food Processing Business', '12345', '', 'business license sample.png', 'tax document sample.png', 'business permit sample.png', '', '', 78),
(45, 'Lyn\'s Banana & Cassava Products ', 'Brgy. Isauan, Tigbauan, Iloilo, Philippines', 'Cottage Food Business', '12345', '', 'business license sample.png', 'tax document sample.png', 'business permit sample.png', '', '', 79),
(46, 'Roquet\'s Healthy Snacks', 'Brgy. Taro, Tigbauan, Iloilo', 'Cottage Food Business', '12345', '', 'business license sample.png', 'tax document sample.png', 'business permit sample.png', '', '', 80),
(47, 'HF FOOD PRODUCTS', 'Atabayan, Tigbauan, Iloilo', 'Small Scale Food Production', '12345', '', 'business license sample.png', 'tax document sample.png', 'business permit sample.png', '', '', 85),
(48, 'Am Joe', 'Brgy. Durog, Miagao, Iloilo, Philippines', 'Cottage Food Business', '12345', '', 'business license sample.png', 'tax document sample.png', 'business permit sample.png', '', '', 86),
(49, 'Bay Sur Food Products Manufacturing', 'Brgy. Kirayan Sur. Miagao, Iloilo', 'Food Production Business', '12345', '', 'business license sample.png', 'tax document sample.png', 'business permit sample.png', '', '', 87);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Con_stat` tinyint(1) NOT NULL,
  `M_status` tinyint(1) NOT NULL,
  `ad_status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `room_id`, `user_id`, `message`, `file_path`, `created_at`, `Con_stat`, `M_status`, `ad_status`) VALUES
(1, 1, 65, 'Welcome! Consultant and MSME, Establishing Communication has been established to conduct Product Development.', NULL, '2025-03-02 06:25:29', 0, 0, 0),
(2, 2, 65, 'Welcome! Consultant and MSME, Establishing Communication has been established to conduct Product Development.', NULL, '2025-03-02 06:33:46', 0, 0, 0),
(3, 3, 65, 'Welcome! Consultant and MSME, Establishing Communication has been established to conduct Product Development.', NULL, '2025-03-02 06:40:52', 0, 0, 0),
(4, 4, 65, 'Welcome! Consultant and MSME, Establishing Communication has been established to conduct Product Development.', NULL, '2025-03-02 06:51:19', 0, 0, 0),
(5, 5, 65, 'Welcome! Consultant and MSME, Establishing Communication has been established to conduct Product Development.', NULL, '2025-03-02 07:28:48', 1, 1, 0),
(6, 6, 65, 'Welcome! Consultant and MSME, Establishing Communication has been established to conduct Product Development.', NULL, '2025-04-28 05:58:20', 1, 1, 0),
(7, 6, 10, 'sdiajshdjashadd', NULL, '2025-04-28 05:59:01', 1, 1, 0),
(8, 6, 10, 'dssadasda', NULL, '2025-04-28 05:59:03', 1, 1, 0),
(9, 6, 1, 'asdasda', NULL, '2025-04-28 06:00:05', 0, 1, 0),
(10, 6, 1, 'dsadas', NULL, '2025-04-28 06:00:39', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chatmember`
--

CREATE TABLE `chatmember` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatmember`
--

INSERT INTO `chatmember` (`id`, `room_id`, `user_id`, `joined_at`) VALUES
(1, 1, 10, '2025-03-02 06:25:29'),
(2, 1, 1, '2025-03-02 06:25:29'),
(3, 1, 65, '2025-03-02 06:25:29'),
(4, 2, 10, '2025-03-02 06:33:46'),
(5, 2, 1, '2025-03-02 06:33:46'),
(6, 2, 65, '2025-03-02 06:33:46'),
(7, 3, 10, '2025-03-02 06:40:52'),
(8, 3, 1, '2025-03-02 06:40:52'),
(9, 3, 65, '2025-03-02 06:40:52'),
(10, 4, 10, '2025-03-02 06:51:19'),
(11, 4, 1, '2025-03-02 06:51:19'),
(12, 4, 65, '2025-03-02 06:51:19'),
(13, 5, 10, '2025-03-02 07:28:48'),
(14, 5, 1, '2025-03-02 07:28:48'),
(15, 5, 65, '2025-03-02 07:28:48'),
(16, 6, 10, '2025-04-28 05:58:20'),
(17, 6, 1, '2025-04-28 05:58:20'),
(18, 6, 65, '2025-04-28 05:58:20');

-- --------------------------------------------------------

--
-- Table structure for table `consultancyquestionnaire`
--

CREATE TABLE `consultancyquestionnaire` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `labelingFormat` varchar(50) NOT NULL,
  `brandName` varchar(255) NOT NULL,
  `ProductName` varchar(50) NOT NULL,
  `productIdentity` varchar(255) NOT NULL,
  `label1` text DEFAULT NULL,
  `label2` text DEFAULT NULL,
  `label3` text DEFAULT NULL,
  `label4` text DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `netContent` varchar(50) NOT NULL,
  `ingredients` text DEFAULT NULL,
  `expiryDate` date NOT NULL,
  `DirectProduct` text NOT NULL,
  `ConceptDesign` text NOT NULL,
  `Size` varchar(20) NOT NULL,
  `DominantColor` varchar(20) NOT NULL,
  `Comment` text NOT NULL,
  `SelectedColor` text NOT NULL,
  `SubmittionDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `draft_img` varchar(255) DEFAULT NULL,
  `status` tinyint(3) NOT NULL,
  `datecreated` datetime DEFAULT NULL,
  `dateend` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultancyquestionnaire`
--

INSERT INTO `consultancyquestionnaire` (`id`, `user_id`, `product_id`, `name`, `address`, `municipality`, `phoneNumber`, `labelingFormat`, `brandName`, `ProductName`, `productIdentity`, `label1`, `label2`, `label3`, `label4`, `tagline`, `netContent`, `ingredients`, `expiryDate`, `DirectProduct`, `ConceptDesign`, `Size`, `DominantColor`, `Comment`, `SelectedColor`, `SubmittionDate`, `draft_img`, `status`, `datecreated`, `dateend`) VALUES
(1, 10, 55, 'aa', 'aa', 'aa', 'aaa', 'aa', 'aa', '1231sadas', 'aa', 'aa', 'aa', 'aa', 'aa', 'aa', 'aa', 'aa', '2025-03-02', 'aa', 'aa', 'small', 'ss', 'ss', 'champagne,palepink', '2025-03-02 06:32:56', NULL, 7, '2025-03-02 14:24:59', '2025-03-02 14:32:50'),
(2, 10, 56, 'asdas', 'das', 'dasdas', 'dasdas', 'das', 'das', 'Bibingka', 'dasd', 'dasd', 'das', 'das', 'das', 'das', 'das', 'das', '2025-03-20', 'das', 'das', 'medium', 'das', 'dsa', 'apricot', '2025-03-02 06:39:57', NULL, 7, '2025-03-02 14:33:18', '2025-03-02 14:39:54'),
(3, 10, 6, 'dasdas', 'sdasd', 'dasdas', 'das', 'das', 'dasdas', 'Buko Pie', 'dasdsa', 'dasd', 'asdas', 'dasdas', 'dasdas', 'dsad', 'asdasdas', 'dasdas', '2025-03-19', 'dsa', 'das', 'medium', 'dasd', 'dasdas', 'apricot,pear', '2025-03-02 06:48:21', NULL, 7, '2025-03-02 14:40:38', '2025-03-02 14:48:14'),
(4, 10, 54, 'asd', 'asdasd', 'asd', 'asdasd', 'dasdsa', 'das', 'Seah Salth', 'dasdas', 'dasdas', 'das', 'dasdas', 'dasd', 'asdadas', 'dasdsa', 'dasdas', '2025-03-27', 'dasdas', 'dasdas', 'medium', 'adasdas', 'dasds', 'apricot', '2025-04-28 05:56:42', NULL, 7, '2025-03-02 14:48:53', '2025-03-02 15:30:55'),
(5, 10, 43, 'dasjdnakn', 'kljnlkjnl', 'jnlkjnlkjn', 'lkjnlkjn', 'asda', 'opopi', 'Buko Pina Pie', 'jpoijpoijpoi', 'jpoijpoi', 'jpoijpoi', 'jpoijpoi', 'jpoijpoi', 'jpoijpoij', 'poijpoij', 'poijpoijpoij', '2025-04-16', 'poijpoijpoi', 'jpoijpojpoi', 'small', 'oijnosiandoi', 'ojnadkajsn', 'pear', '2025-04-28 05:58:10', 'kylho.png', 2, '2025-04-28 13:57:40', '2025-04-28 13:58:10');

-- --------------------------------------------------------

--
-- Table structure for table `consultation_report`
--

CREATE TABLE `consultation_report` (
  `ConReport_ID` int(11) NOT NULL,
  `consultationID` int(11) NOT NULL,
  `ConceptDesign` varchar(100) NOT NULL,
  `size` varchar(100) NOT NULL,
  `Comment` varchar(100) NOT NULL,
  `DominantColor` varchar(100) NOT NULL,
  `SelectedColor` varchar(100) NOT NULL,
  `draft_img` varchar(100) NOT NULL,
  `submission_datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `room_id` int(11) NOT NULL,
  `conID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultation_report`
--

INSERT INTO `consultation_report` (`ConReport_ID`, `consultationID`, `ConceptDesign`, `size`, `Comment`, `DominantColor`, `SelectedColor`, `draft_img`, `submission_datetime`, `room_id`, `conID`) VALUES
(1, 1, 'dasda', 'medium', 'dasd', 'dsadas', 'pear', 'Screenshot 2024-08-08 203111.png', '2025-03-02 06:31:27', 1, 1),
(2, 2, '11', 'medium', '11', '11', 'apricot,pear', 'Screenshot 2024-08-08 202238.png', '2025-03-02 06:39:17', 2, 1),
(5, 3, 'dasdas', 'medium', 'dasd', 'adsa', 'apricot', 'Screenshot 2024-08-08 203111.png', '2025-03-02 06:42:32', 3, 1),
(6, 4, 'dasd', 'medium', 'asdasd', 'asdasdas', 'pear', 'Screenshot 2024-08-08 205159.png', '2025-03-02 06:54:19', 4, 1),
(7, 4, 'dsa', 'medium', 'asdas', 'dasdsa', 'pear', 'card.png', '2025-04-02 08:48:56', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` bigint(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `number`, `message`, `created_at`) VALUES
(2, 'Athea marie Deocampo', 'atheahisuan36@gmail.com', 23, 'vcvvcvc', '2024-05-12 23:02:33'),
(3, 'joei', 'joeialexandra@gmail.com', 12345678, 'can I schedule a meeting?', '2024-05-13 14:02:01'),
(4, 'Med Staff', 'ckklient@gmail.com', 827393420, 'Goods', '2024-05-24 13:44:40'),
(5, '2 1213', '213231213@gmail.com', 231231213, 'sewew', '2024-10-27 08:45:47');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

CREATE TABLE `evaluation` (
  `id` int(11) NOT NULL,
  `Cname` varchar(50) DEFAULT NULL,
  `MSME` varchar(50) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `Pname` varchar(50) DEFAULT NULL,
  `Stime` time DEFAULT NULL,
  `Product_Line` varchar(50) DEFAULT NULL,
  `Enterprise_Category` varchar(50) DEFAULT NULL,
  `Currently_Exporting` text DEFAULT NULL,
  `ExportPlan` text DEFAULT NULL,
  `QUALITY_Score` int(11) DEFAULT NULL,
  `QUALITY_Comment` text DEFAULT NULL,
  `DESIGN_Score` int(11) DEFAULT NULL,
  `DESIGN_Comment` text DEFAULT NULL,
  `PACKAGING_Score` int(11) DEFAULT NULL,
  `packagingComments` text DEFAULT NULL,
  `MARKETABILITY_Score` int(11) DEFAULT NULL,
  `marketabilityComments` text DEFAULT NULL,
  `BRAND_Score_Score` int(11) DEFAULT NULL,
  `brandComments` text DEFAULT NULL,
  `PRODUCTION_Score` int(11) DEFAULT NULL,
  `productionComments` text DEFAULT NULL,
  `FINANCING_Score` int(11) DEFAULT NULL,
  `financingComments` text DEFAULT NULL,
  `CULTURAL_Score` int(11) DEFAULT NULL,
  `culturalComments` text DEFAULT NULL,
  `INNOVATION_Score` int(11) DEFAULT NULL,
  `Innovation` text DEFAULT NULL,
  `CUSTOMER_Score` int(11) DEFAULT NULL,
  `CUSTOMER` text DEFAULT NULL,
  `Designer` varchar(50) DEFAULT NULL,
  `total_score` int(11) DEFAULT NULL,
  `intensive` text DEFAULT NULL,
  `Recommend` text DEFAULT NULL,
  `Enroll` text DEFAULT NULL,
  `ExamDate` date DEFAULT NULL,
  `Province` varchar(50) DEFAULT NULL,
  `TimeEnd` time DEFAULT NULL,
  `EName` varchar(50) DEFAULT NULL,
  `Signature` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation`
--

INSERT INTO `evaluation` (`id`, `Cname`, `MSME`, `product_id`, `Pname`, `Stime`, `Product_Line`, `Enterprise_Category`, `Currently_Exporting`, `ExportPlan`, `QUALITY_Score`, `QUALITY_Comment`, `DESIGN_Score`, `DESIGN_Comment`, `PACKAGING_Score`, `packagingComments`, `MARKETABILITY_Score`, `marketabilityComments`, `BRAND_Score_Score`, `brandComments`, `PRODUCTION_Score`, `productionComments`, `FINANCING_Score`, `financingComments`, `CULTURAL_Score`, `culturalComments`, `INNOVATION_Score`, `Innovation`, `CUSTOMER_Score`, `CUSTOMER`, `Designer`, `total_score`, `intensive`, `Recommend`, `Enroll`, `ExamDate`, `Province`, `TimeEnd`, `EName`, `Signature`) VALUES
(1, '1', '2', 3, '4', '00:00:00', NULL, 'Micro', 'Yes', 'Yes', 5, '6', 5, '8', 5, '10', 5, '12', 5, '14', 5, '16', 5, '18', 5, '20', 5, '22', 5, '24', '25', 0, 'Yes', 'Yes', 'Yes', '2024-06-06', '12', '00:00:00', '25', 'signature/PC1RM107.png'),
(2, '1', '2', 3, '4', '00:00:00', 'Food', 'Micro', 'Yes', 'Yes', 1, '2', 1, '2', 1, '2', 1, '2', 1, '2', 1, '2', 1, '2', 1, '2', 1, '2', 1, '1', '22', 0, 'Yes', 'Yes', 'Yes', '2024-06-06', '213231', '00:00:00', '2 1213', 'signature/PC1RM107.png'),
(3, 'Nani', '10', 8, 'J AND I', '00:00:00', 'Food', 'Micro', 'Yes', 'Yes', 5, 'Good', 5, 'Good', 5, 'Good', 4, 'Good', 5, 'Good', 5, 'Good', 3, 'Good', 4, 'Good', 4, 'Good', 5, 'Good', 'Nanis', 40, 'Yes', 'Yes', 'Yes', '2024-06-06', 'Iloilo', '00:00:00', 'Naniss', 'signature/PC1RM107.png'),
(4, 'Nani', '10', 8, 'J AND I', '23:21:00', 'Food', 'Micro', 'Yes', 'Yes', 5, 'Good', 5, 'Good', 5, 'Good', 4, 'Good', 5, 'Good', 5, 'Good', 3, 'Good', 4, 'Good', 4, 'Good', 5, 'Good', 'Nanis', 40, 'Yes', 'Yes', 'Yes', '2024-06-06', 'Iloilo', '22:24:00', 'Naniss', 'signature/PC1RM107.png'),
(5, '1', '10', 7, 'LYN&#39;S Banana Chips', '21:31:00', 'Non-Food', 'Medium', 'Yes', 'No', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', '', 9, 'Yes', 'Yes', 'Yes', '2024-06-06', '1', '21:36:00', '2', 'signature/PC1RM107.png'),
(6, 'e', '10', 6, 'Roquets Health Snack', '22:21:00', 'Non-Food', 'Small', 'No', 'No', 2, '2', 0, '2', 2, '', 2, '2', 2, '2', 2, '2', 2, '2', 2, '2', 2, '2', 2, '2', '21', 18, 'non-intensive', NULL, 'No', '2024-06-06', '1212', '22:25:00', '1212', 'signature/Signature.png'),
(7, 'e', '10', 7, 'LYN&#39;S Banana Chips', '22:21:00', 'Non-Food', 'Small', 'No', 'No', 2, '2', 0, '2', 2, '', 2, '2', 2, '2', 2, '2', 2, '2', 2, '2', 2, '2', 2, '2', '21', 0, 'non-intensive', NULL, 'No', '2024-06-06', '1212', '22:25:00', '1212', 'signature/Signature.png'),
(8, 'e', '10', 9, 'LOLO CHOCO (TABLEA)', '22:21:00', 'Non-Food', 'Small', 'No', 'No', 2, '2', 0, '2', 2, '', 2, '2', 2, '2', 2, '2', 2, '2', 2, '2', 2, '2', 2, '2', '21', 0, 'non-intensive', NULL, 'No', '2024-06-06', '1212', '22:25:00', '1212', 'signature/Signature.png'),
(9, '3213', '10', 6, 'Roquets Health Snack', '13:25:00', 'Non-Food', 'Small', 'No', 'No', 1, '1', 1, '1', 1, '1', 1, '1', 1, '1', 1, '1', 1, '1', 1, '1', 1, '1', 1, '1', '11', 10, 'non-intensive', 'conditional', 'No', '2024-06-06', '11', '22:31:00', '11', 'signature/Signature.png'),
(10, 'CKKLIENT GRIND', '66', 40, 'Local Coffee Bean', '13:13:00', 'Non-Food', 'Small', 'Yes', 'No', 5, 'Good', 5, 'Visually Appealing', 5, 'Fantastic', 5, 'Okay', 5, 'Okay', 5, 'Goods', 5, 'Stable', 5, 'Okay', 5, 'FIne', 5, 'Good Service', '', 50, 'intensive', 'Yes', 'yes', '2024-06-05', 'Iloilo', '14:18:00', 'Christian', 'signature/Signature.png'),
(11, '1', '66', 40, 'Local Coffee Bean', '01:12:00', NULL, NULL, NULL, NULL, 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 'ew', 10, NULL, NULL, NULL, '2024-06-11', '2', '02:13:00', '1', 'signature/process.php'),
(12, '1', '66', 40, 'Local Coffee Bean', '08:42:00', 'Food', 'Medium', 'Yes', 'Yes', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1, '', '1', 10, 'intensive', 'Yes', 'yes', '2024-06-11', '1', '02:36:00', '1', 'signature/85562c14-2418-4ba1-abbe-d57572398d57.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `rating` varchar(20) DEFAULT NULL,
  `rating_value` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `datesubmitted` date NOT NULL DEFAULT current_timestamp(),
  `product_id` int(200) NOT NULL,
  `sentiment` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `Scan` tinyint(1) DEFAULT NULL,
  `Coupon` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `userid`, `name`, `rating`, `rating_value`, `comment`, `datesubmitted`, `product_id`, `sentiment`, `category`, `Scan`, `Coupon`) VALUES
(142, 35, NULL, '3', 3, 'Flavor taste good', '2024-10-27', 6, 'Satisfied', 'Taste', NULL, 1),
(143, 35, NULL, '1', 1, 'Bad Taste ', '2024-10-27', 8, 'Unsatisfied', 'Taste', NULL, 1),
(144, 0, 'Chan1', '1', 1, 'Taste bad', '2024-10-27', 43, 'Unsatisfied', 'Taste', 1, 1),
(145, 35, NULL, '2', 2, 'jdkfQuality ', '2024-10-27', 43, 'Neutral', 'Quality', NULL, 1),
(146, 0, '', '2', 2, 'Alright goods', '2024-11-04', 6, 'Neutral', 'Quality', 1, 1),
(147, 74, NULL, '2', 2, 'dbawjhbdkjawhbdjahsbdhqwbdkhbasd', '2024-11-18', 6, 'Neutral', 'Quality', NULL, 1),
(148, 0, 'dbajwhbdwjqhbdkhj', '2', 2, 'Design Quality Package Label ', '2024-11-18', 66, 'Neutral', 'Labeling', 1, 1),
(149, 90, NULL, '2', 2, 'hello there', '2024-11-18', 66, 'Neutral', 'Quality', NULL, 1),
(150, 1, NULL, NULL, NULL, 'da', '2024-11-18', 66, NULL, NULL, NULL, NULL),
(151, 35, NULL, '2', 2, 'Design Taste Quality Label Package Branding Logo Flavor ', '2024-11-19', 11, 'Neutral', 'Labeling', NULL, 1),
(152, 35, NULL, '2', 2, 'Sulit', '2025-01-07', 6, 'Neutral', 'Quality', NULL, 0),
(153, 35, NULL, '3', 3, 'Design is good also the taste is so savory and it has quality, the label ang package branding is also at peak ', '2025-01-07', 6, 'Satisfied', 'Labeling', NULL, 0),
(154, 35, NULL, '2', 2, 'das', '2025-01-07', 8, 'Neutral', 'Quality', NULL, 0),
(155, 35, NULL, '2', 2, 'dwasdaw', '2025-01-07', 8, 'Neutral', 'Quality', NULL, 0),
(156, 35, NULL, '2', 2, 'das', '2025-01-07', 8, 'Neutral', 'Quality', NULL, 0),
(157, 35, NULL, '2', 2, 'asdfewfdsf', '2025-01-07', 8, 'Neutral', 'Quality', NULL, 0),
(158, 35, NULL, '2', 2, 'Design Taste Quality Label Package Branding Logo Flavor Super yummy', '2025-02-27', 7, 'Neutral', 'Labeling', NULL, 1),
(159, 0, 'meow', '2', 2, 'Design Taste Quality Label Package Branding Logo Flavor ', '2025-02-28', 8, 'Neutral', 'Labeling', 1, 1),
(160, 0, 'dasd', '2', 2, 'Love youuu', '2025-03-02', 8, 'Neutral', 'Quality', 1, 1),
(161, 0, 'WEWEW', '2', 2, 'asdasdasdas', '2025-03-02', 8, 'Neutral', 'Quality', 1, 1),
(162, 0, '', '2', 2, 'dasdasdasdsaTaste ', '2025-03-02', 8, 'Neutral', 'Quality', 1, 1),
(163, 35, 'fasdfas', '1', 1, 'Design Taste Quality Label Package Branding Logo Flavor sdfasdfsafsadfasd', '2025-03-02', 8, 'Unsatisfied', 'Labeling', 1, 1),
(164, 35, '', '2', 2, 'Hellow love goodbye', '2025-03-02', 8, 'Neutral', 'Quality', 1, 0),
(165, 0, '', '2', 2, 'Design Quality Label Package Branding Logo Flavor ', '2025-06-06', 7, 'Neutral', 'Labeling', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `msme_id` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `productPrice` int(11) NOT NULL,
  `productImage` text NOT NULL,
  `productDetails` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `msme_id`, `productName`, `productPrice`, `productImage`, `productDetails`) VALUES
(7, 10, 'Cassava Cake', 255, 'Cassava Cake.jpg', 'Meow'),
(8, 10, 'Egg pie', 300, 'Eggpie.jpg', 'egg pie is a classic treat that stands out among Iloilo’s local pastries. Known for its creamy, custardy filling, the egg pie features a golden-brown crust and a smooth, firm filling that blends egg and milk flavors.'),
(9, 10, 'Mango Pie', 350, 'mango pie.png', 'This mango pie features a sweet, rich filling made from ripe mangoes, encased in a golden, flaky crust. Known for its fresh and fruity flavor, this pie stands out for showcasing the tropical taste of Philippine mangoes, which are celebrated for their sweetness and unique flavor profile. '),
(10, 10, 'Buko Pandan Pie', 400, 'buko pandan pie.jpg', 'Buko Pandan Pie combines the unique flavors of coconut and pandan in a creamy filling with young coconut strips, similar to the traditional buko pie but with a fresh twist of pandan essence. '),
(11, 10, 'Buko Langka Pie', 450, 'Buko-Langka-Pie.png', 'Buko Langka Pie is a delightful twist on the traditional buko pie, featuring the added sweetness and distinct flavor of langka (jackfruit). This pie is well-loved for its rich, creamy filling made from young coconut (buko) and ripe jackfruit, encased in a flaky crust.'),
(40, 66, 'Local Coffee Bean', 100, 'download.jpg', 'Local Picked Coffee Bean from the Soil Land'),
(41, 10, 'Pineapple Pie', 500, 'Pineapple Pie.jpg', 'pineapple pie is a delightful treat that&#39;s well-loved for its combination of juicy pineapple chunks encased in a light and buttery crust. This pie stands out not only for its flavor but also for the generous portion of filling, making it a satisfying dessert option. The balance of sweet and tart pineapple makes it refreshing and perfect for those who enjoy fruity pastries.'),
(43, 10, 'Buko Pina Pie', 550, 'Buko Pina Pie.png', 'Buko piña pie is a delightful Filipino dessert that combines the sweetness of young coconut (buko) with the tropical flavor of pineapple. '),
(44, 75, 'Buko Pie', 300, 'Buko Pie.jpg', 'Nang Palang’s Buko Pie is that scrumptious and heavenly delicious pie with tasteful coconut meat mixed with milk and pie dough.'),
(45, 75, 'Cassava Cake', 550, 'Cassava Cake.jpg', 'Cassava cake is a traditional Filipino moist cake made from grated cassava, coconut milk, and condensed milk with a custard layer on top. It is a very popular dish in the Philippines, where it is commonly eaten for merienda. It is also served during gatherings and special occasions.'),
(46, 75, 'Egg pie', 250, 'Eggpie.jpg', 'Egg pie is a sweet Filipino pie dessert with an egg custard filling and a characteristic toasty brown top made from egg whites. It is made with flour, sugar, milk, butter, and eggs. Calamansi juice or zest may also be added. It is a type of custard pie. Egg pies are commonly sold in bakeries in the Philippines.'),
(47, 75, 'Mango Pie', 400, 'mango pie.png', 'mango pie recipes often call for mango puree mixed with ingredients like cornstarch for thickness, vanilla for flavor, and sometimes a hint of lime or lemon for added tanginess.'),
(48, 75, 'Buko Pandan Pie', 350, 'buko pandan pie.jpg', 'Buko pandan pie is a tropical dessert that combines the flavors of young coconut (buko) and pandan, a fragrant tropical leaf. The pie has a creamy filling and a flaky crust. '),
(49, 75, 'Buko Langka Pie', 450, 'Buko-Langka-Pie.png', 'Buko langka pie is a dessert made with young coconut meat and jackfruit filling, encased in a pastry crust. It&#39;s a sweet and fruity treat that&#39;s perfect for satisfying a craving.'),
(50, 75, 'Pineapple Pie', 330, 'Pineapple Pie.jpg', 'Pineapple Pie made of a delightfully sweet pineapple filling, nestled on the flakiest butter pie crust. This is a certified family favorite!'),
(51, 75, 'Buko Pina Pie', 230, 'Buko Pina Pie.png', 'Buko pina pie is a dessert that combines coconut and pineapple flavors in a pie. It has a flaky crust and a sweet filling with pieces of coconut and pineapple in syrup'),
(52, 75, 'Chicken Pie', 499, 'Chicken Pie.png', 'A traditional stew or pie made with chicken, vegetables, sausages, mushrooms, peas, carrots, potatoes, soy sauce, and spices in a creamy sauce. It&#39;s usually baked in an earthenware dish with a pastry crust.'),
(53, 75, 'Ube Pie', 299, 'Ube Pie.jpg', 'Ube Pie is known for its creamy ube (purple yam) filling and smooth, buttery crust, showcasing the flavors of freshly prepared ube. '),
(54, 10, 'Sea Salt', 255, '8bc03ef7-986b-45c7-bb17-33893ee30cc3.jpg', 'Seaaah Salt'),
(55, 10, 'Ube Pie', 222, 'Ube Pie.jpg', 'sddsafsadfas'),
(56, 10, 'Bibingka', 30000, 'Bibingka.png', 'Bibingka is a delightful treat that stands out among the various offerings at this well-loved bakery in Oton, Iloilo. Bibingka, a traditional Filipino rice cake, is known for its soft, fluffy texture and is often cooked in a banana leaf-lined mold. '),
(58, 85, 'Ginisang Bagoong Hot Spicy', 150, 'Ginisang Bagoong Hot Spicy.jpeg', 'Ikaays HF Food Products offers a delicious version of Ginisang Bagoong Hot Spicy, a traditional Filipino shrimp paste condiment known for its robust flavors and versatility. This particular product is expertly crafted using high-quality shrimp paste that has been sautéed to enhance its natural umami taste, combined with a medley of spices and chili peppers to deliver a delightful kick of heat.'),
(59, 85, 'Ginisang Bagoong Garlic', 200, 'Ginisang Bagoong Garlic.jpeg', 'Ginisang Bagoong Garlic by Ikaays HF Food Products is a savory and flavorful Filipino condiment made from fermented shrimp paste, known locally as bagoong alamang, sautéed with garlic, onions, and tomatoes.'),
(60, 80, 'Banana Chips', 120, 'Banana Chips.jpeg', 'Roquet&#39;s banana chips from Tigbauan are a popular snack made from locally sourced bananas, specifically the saba variety, known for their rich flavor and ideal texture for frying. The product is typically characterized by being crispy, flavorful, and often lightly salted, making them a great snack on their own or as a topping for various dishes.'),
(61, 80, 'Camote Chips', 120, 'Camote Chips.jpeg', 'Camote chips, or sweet potato chips, have gained popularity as a healthier snack option in the Philippines. These chips are made from high-quality camote varieties, including yellow, orange, and a unique purple variant that are grown by partner farmers committed to sustainable agriculture. The production process involves frying the camote slices in coconut oil, which enhances their natural sweetness while also providing a crunchy texture.'),
(62, 80, 'Banana Fries', 120, 'Banana Fries.jpeg', 'Banana fries, often made from the Saba variety of bananas, are becoming increasingly popular as a tasty and healthier alternative to traditional potato fries. This Filipino snack is not only delicious but also offers a unique texture and flavor profile that appeals to many.'),
(63, 79, 'Erlindas Banana Chips', 250, 'Erlindas Banana Chips.jpeg', 'Erlindas banana chips is known for maintaining high quality, using local bananas sourced from the area. These chips are often sold in various packaging options, making them convenient for gifting or personal enjoyment. In addition to the original flavor, there may be variations with added seasonings or flavors to cater to different tastes.'),
(64, 78, 'Kalkag', 130, 'Kalkag.jpeg', 'Kalkag, available at Els Dry Goods in Tigbauan, is a type of dried fish popular in the Philippines, particularly in the Iloilo region. This product is made from small fish, often anchovies, which are salted, dried, and sun-dried to preserve their flavor and texture.'),
(65, 77, 'Special Baye-Baye', 300, 'Special Baye Baye.jpg', 'D Original Jaspe, located in Santa Barbara, Iloilo, is renowned for its Special Baye-Baye, a traditional Ilonggo delicacy that has garnered significant popularity. This unique treat is primarily made from pinipig, which is pounded rice, and is combined with grated coconut and sugar, creating a sweet, chewy texture.'),
(66, 76, 'Roasted Seasalt Lemong Grass', 100, 'Roasted Seasalt Lemon Grass.jpg', 'The Roasted Seasalt Lemongrass from Miagao, known locally as Budbud; is a unique artisanal product that embodies traditional salt-making techniques. This salt is created using bamboo nodes, which serve as filtration vessels. Coastal sand is placed inside these bamboo splits, and seawater is poured in to evaporate under the sun, resulting in a clearer and more crystalline salt that is less salty than typical varieties. Following this process, the salt is roasted for food safety and flavor. '),
(67, 86, 'Batuan Jam', 100, 'Am Joe - Batuan Jam.jpg', 'The Batuan jam by Am Joe is a unique product from Miagao, Iloilo, made from the native Batuan fruit, scientifically known as Garcinia binucao. This fruit is recognized for its sour flavor, commonly used in traditional Ilonggo dishes, making it a valued ingredient in local cuisine.'),
(68, 86, 'Cocoa', 110, 'Am Joe Food Products - Cocoa.jpg', 'Cocoa by Am Joe is a popular café located in Miagao, Iloilo, that specializes in a variety of delicious chocolate-based beverages and desserts. Their menu features an array of cocoa drinks, including traditional hot chocolate, cold cocoa, and specialty drinks that incorporate local ingredients and flavors. '),
(69, 87, 'Assorted Pastillas', 60, 'Assorted Pastillas.jpg', 'Bay Sur Food Products Manufacturing, based in Miagao, Iloilo, produces a delightful assortment of pastillas, a traditional Filipino milk-based confection. This variety pack includes different flavors of pastillas, each crafted to highlight unique tastes while staying true to the classic creamy, soft, and melt-in-the-mouth texture of this Filipino treat. '),
(70, 87, 'Banana Chips', 65, 'Banana Chips.jpg', 'Bay Sur Food Products, based in Miagao, Iloilo, specializes in crafting locally sourced and high-quality banana chips. These chips are made from carefully selected bananas, ensuring they meet both taste and texture standards. The bananas are thinly sliced and fried to achieve a crisp, golden finish, then lightly coated with sugar to enhance their natural sweetness. '),
(71, 87, 'Garlic Chili Oil Sauce', 100, 'Garlic Chili Oil Sauce.jpg', 'Bay Sur Food Products Manufacturing, based in Miagao, Iloilo, offers a flavorful Garlic Chili Oil known for its blend of heat and aromatic garlic, ideal for spicing up a variety of dishes. This chili oil combines carefully selected chili peppers and garlic, finely processed to release deep flavors while maintaining a mild yet appetizing heat that complements Filipino and Asian cuisines alike.'),
(72, 87, 'Kalkag Dried Tiny Shrimps', 70, 'Kalkag.jpg', 'Kalkag, produced by Bay Sur Food Products Manufacturing in Miagao, Iloilo, is a traditional Filipino food product made primarily from sun-dried tiny shrimps, commonly referred to as kalkag or alamang in local dialects. This delicacy is known for its rich, savory flavor and slight saltiness, which makes it a popular ingredient in Filipino cuisine.'),
(73, 87, 'Kamote Chips', 75, 'Kamote Chips.jpg', 'Kamote Chips by Bay Sur Food Products, a company based in Miagao, Iloilo, Philippines, is a snack made from locally sourced sweet potatoes, also known as kamote in Filipino. These chips are crafted using high-quality sweet potatoes, sliced thinly, and fried to a crisp, offering a unique and natural flavor profile thats both sweet and savory. '),
(74, 87, 'Pastillas de Chocolate', 79, 'Pastillas de Chocolate.jpg', 'Pastillas de Chocolate by Bay Sur Food Products Manufacturing, based in Miagao, Iloilo, is a sweet delicacy that combines the flavors of creamy milk and rich chocolate, inspired by traditional Filipino pastillas. These chocolate pastillas are typically crafted using fresh, local ingredients, including premium cacao and milk, which give them a soft and melt-in-the-mouth texture with a hint of chewiness. '),
(75, 87, 'Pastillas de leche', 79, 'Pastillas de Leche.jpg', 'Pastillas de leche by Bay Sur Food Products Manufacturing, located in Miagao, Philippines, is a Filipino delicacy renowned for its rich, creamy, and sweet flavor. Made with fresh, high-quality carabao s milk (water buffalo milk), the product is crafted to provide an authentic, melt-in-your-mouth experience unique to this traditional milk-based confection.'),
(76, 87, 'Pastillas de Melon', 79, 'Pastillas de Melon.jpg', 'Pastillas de Melon by Bay Sur Food Products Manufacturing, based in Miagao, Iloilo, is a traditional Filipino delicacy made primarily from milk and melon flavoring. This sweet, chewy confection offers a delightful twist on the classic pastillas by incorporating the refreshing taste of melon, creating a distinct and enjoyable flavor profile. '),
(77, 87, 'Pastillas de Pandan', 79, 'Pastillas de Pandan.jpg', 'Pastillas de Pandan by Bay Sur Food Products Manufacturing is a delicately crafted Filipino confectionery made in Miagao, Iloilo, blending traditional flavors with a touch of pandan. These soft, creamy milk-based candies are infused with pandan, a fragrant tropical plant often described as having a mildly sweet and grassy vanilla-like aroma. This distinct flavor provides a unique twist on the classic pastillas, a well-loved Filipino sweet. '),
(78, 87, 'Salted Peanut', 35, 'Salted Peanut.jpg', 'Bay Sur Food Products Manufacturing, based in Miagao, Iloilo, produces a popular snack item: salted peanuts. These peanuts are carefully selected for quality, ensuring a fresh and crunchy texture that makes them a favorite among snack enthusiasts. They are lightly roasted and seasoned with just the right amount of salt, providing a savory flavor profile that complements the natural nuttiness of the peanuts. '),
(79, 87, 'Sauteed Shrimp Paste Hot & Spicy', 100, 'Shrimp Paste Hot and Spicy.jpg', 'Bay Sur Food Products Manufacturing, based in Miagao, offers a popular product known as Sauteed Shrimp Paste Hot & Spicy, also referred to as Ginisang Bagoong. This flavorful condiment is made primarily from shrimp fry, corn oil, vinegar, onion, garlic, salt, sugar, and monosodium glutamate (MSG), giving it a robust taste profile that enhances various dishes. The shrimp paste is available in different packaging sizes, typically ranging from 8.8 ounces to larger containers, making it versatile '),
(80, 87, 'Sauteed Shrimp Paste Original', 100, 'Shrimp Paste Original Flavor.jpg', 'Sauteed Shrimp Paste Original, produced by Bay Sur Food Products in Miagao, is a distinctive Filipino condiment known for its robust flavor. This product, commonly referred to as Ginisang Bagoong, is made primarily from fermented shrimp, combined with essential seasonings like tomatoes, onions, and garlic. It is often cooked with pork fat to enhance its richness, yielding a savory, umami-packed paste that is both aromatic and delicious.'),
(81, 87, 'Sauteed Shrimp Paste Sweet & Sour', 100, 'Shrimp Paste Sweet and Sour.jpg', 'Bay Sur Food Products Manufacturing, based in Miagao, produces a unique condiment known as Sautéed Shrimp Paste, or ginisang bagoong. This product is a delightful combination of sweet, savory, and slightly spicy flavors, making it an excellent accompaniment to various dishes. The paste is typically made by sautéing shrimp paste with aromatics like garlic, onions, and tomatoes, and it often includes sugar for sweetness and a hint of chili for a spicy kick.'),
(82, 87, 'Sauteed Shrimp Paste Uncooked', 100, 'Shrimp Paste Uncooked.jpg', 'Bay Sur Food Products Manufacturing, located in Miagao, Iloilo, specializes in producing a variety of local food items, including their uncooked sautéed shrimp paste, also known as bagoong alamang. This product is a staple in Filipino cuisine, prized for its rich umami flavor and versatility. The shrimp paste is typically made from fresh shrimp, fermented to enhance its savory taste, making it an ideal ingredient for numerous dishes such as pinakbet, binagoongan, and kare-kare'),
(83, 87, 'Spicy Salted Peanut', 60, 'Spicy Salted Peanuts.jpg', 'Bay Sur Food Products, located in Miagao, is known for its delicious Spicy Salted Peanuts. This product combines roasted peanuts with a perfect blend of spices and salt, delivering a crunchy and flavorful snack that appeals to those who enjoy a bit of heat. The peanuts are carefully prepared to ensure quality and taste, making them a popular choice among local consumers and visitors alike. The product packaging is typically designed to maintain freshness while also being visually appealing.'),
(84, 87, 'Taro Chips', 60, 'Taro Chips.jpg', 'Bay Sur Food Products, based in Miagao, is known for its innovative use of taro in its product line, including their popular taro chips. These chips are made from locally sourced taro, or gabi, which is recognized for its high starch content and dietary fiber benefits. The company has embraced the growing trend of producing snacks that highlight local agricultural products, appealing to both local consumers and tourists.');

-- --------------------------------------------------------

--
-- Table structure for table `product_updates`
--

CREATE TABLE `product_updates` (
  `update_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `column_name` varchar(255) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_updates`
--

INSERT INTO `product_updates` (`update_id`, `product_id`, `column_name`, `old_value`, `new_value`, `updated_at`, `is_read`) VALUES
(20, 43, 'productName', 'TEST', 'TEST1', '2024-10-27 12:34:22', 1),
(21, 43, 'productPrice', '10', '101', '2024-10-27 12:34:22', 1),
(22, 43, 'productDetails', 'TESTING', 'TESTING1', '2024-10-27 12:34:22', 1),
(24, 41, 'productName', 'QRR', 'N/A', '2024-11-02 13:19:59', 1),
(25, 41, 'productPrice', '55', '10', '2024-11-02 13:19:59', 1),
(26, 41, 'productImage', 'M3Eval11.jpg', 'PLACEHOLDER.png', '2024-11-02 13:19:59', 1),
(27, 41, 'productDetails', 'QR testt', 'N/A', '2024-11-02 13:19:59', 1),
(28, 43, 'productName', 'TEST1', 'N/A2', '2024-11-02 13:20:26', 1),
(29, 43, 'productImage', 'TESTLFPMS.png', 'PLACEHOLDER.png', '2024-11-02 13:20:26', 1),
(30, 43, 'productDetails', 'TESTING1', 'N/A', '2024-11-02 13:20:26', 1),
(35, 7, 'productName', 'LYN&#39;S Banana Chips1', 'Cassava Cake', '2024-11-02 13:41:21', 1),
(36, 7, 'productPrice', '501', '500', '2024-11-02 13:41:21', 1),
(37, 7, 'productImage', 'Messenger_creation_2d3bd33b-2826-4d9c-8fb1-aeae1d4c2ff3.jpeg', 'Cassava Cake.jpg', '2024-11-02 13:41:21', 1),
(38, 7, 'productDetails', 'NANIS', 'cassava cake is crafted from freshly grated cassava root, sweetened with milk to create a balanced flavor that is both mildly sweet and rich.', '2024-11-02 13:41:21', 1),
(39, 8, 'productName', 'J AND I', 'Egg pie', '2024-11-02 13:42:13', 1),
(40, 8, 'productPrice', '50', '300', '2024-11-02 13:42:13', 1),
(41, 8, 'productImage', 'Messenger_creation_f1c99906-c3f7-4249-9e42-7cf6ed24e318.jpeg', 'Eggpie.jpg', '2024-11-02 13:42:13', 1),
(42, 8, 'productDetails', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop p', 'egg pie is a classic treat that stands out among Iloilo’s local pastries. Known for its creamy, custardy filling, the egg pie features a golden-brown crust and a smooth, firm filling that blends egg and milk flavors.', '2024-11-02 13:42:13', 1),
(43, 9, 'productName', 'LOLO CHOCO (TABLEA)', 'Mango Pie', '2024-11-02 13:43:38', 1),
(44, 9, 'productPrice', '50', '350', '2024-11-02 13:43:38', 1),
(45, 9, 'productImage', 'Messenger_creation_8cc82615-2a7e-438f-96e8-abafc59071f1.jpeg', 'mango pie.png', '2024-11-02 13:43:38', 1),
(46, 9, 'productDetails', 'lll', 'This mango pie features a sweet, rich filling made from ripe mangoes, encased in a golden, flaky crust. Known for its fresh and fruity flavor, this pie stands out for showcasing the tropical taste of Philippine mangoes, which are celebrated for their sweetness and unique flavor profile. ', '2024-11-02 13:43:38', 1),
(47, 10, 'productName', 'IKAAY&#39;S  Baggoong', 'Buko Pandan Pie', '2024-11-02 13:44:34', 1),
(48, 10, 'productPrice', '60', '400', '2024-11-02 13:44:34', 1),
(49, 10, 'productImage', 'Messenger_creation_45d598bb-638a-43bb-876b-88c995225a64.jpeg', 'buko pandan pie.jpg', '2024-11-02 13:44:34', 1),
(50, 10, 'productDetails', 'IKAW FOOD Bogong', 'Buko Pandan Pie combines the unique flavors of coconut and pandan in a creamy filling with young coconut strips, similar to the traditional buko pie but with a fresh twist of pandan essence. ', '2024-11-02 13:44:34', 1),
(51, 11, 'productName', 'EL&#39;S DRY GOODS ', 'Buko Langka Pie', '2024-11-02 13:45:01', 1),
(52, 11, 'productPrice', '70', '450', '2024-11-02 13:45:01', 1),
(53, 11, 'productImage', 'Messenger_creation_b6b83769-d69f-420b-8ab6-63fba3c60bca.jpeg', 'Buko-Langka-Pie.png', '2024-11-02 13:45:01', 1),
(54, 11, 'productDetails', 'Kalkag masarap', 'Buko Langka Pie is a delightful twist on the traditional buko pie, featuring the added sweetness and distinct flavor of langka (jackfruit). This pie is well-loved for its rich, creamy filling made from young coconut (buko) and ripe jackfruit, encased in a flaky crust.', '2024-11-02 13:45:58', 1),
(55, 41, 'productName', 'N/A', 'Pineapple Pie', '2024-11-02 13:47:02', 1),
(56, 41, 'productPrice', '10', '500', '2024-11-02 13:47:02', 1),
(57, 41, 'productImage', 'PLACEHOLDER.png', 'Pineapple Pie.jpg', '2024-11-02 13:47:02', 1),
(58, 41, 'productDetails', 'N/A', 'pineapple pie is a delightful treat that&#39;s well-loved for its combination of juicy pineapple chunks encased in a light and buttery crust. This pie stands out not only for its flavor but also for the generous portion of filling, making it a satisfying dessert option. The balance of sweet and tart pineapple makes it refreshing and perfect for those who enjoy fruity pastries.', '2024-11-02 13:47:02', 1),
(59, 43, 'productName', 'N/A2', 'Buko Pina Pie', '2024-11-02 13:47:46', 1),
(60, 43, 'productPrice', '101', '500', '2024-11-02 13:47:46', 1),
(61, 43, 'productImage', 'PLACEHOLDER.png', 'Buko Pina Pie.png', '2024-11-02 13:47:46', 1),
(62, 43, 'productDetails', 'N/A', 'Buko piña pie is a delightful Filipino dessert that combines the sweetness of young coconut (buko) with the tropical flavor of pineapple. ', '2024-11-02 13:47:46', 1),
(63, 43, 'productPrice', '500', '550', '2024-11-02 13:48:29', 1),
(64, 63, 'productName', 'Lyn&#39;s Banana Chips', 'Lyn&#39; s Banana Chips', '2024-11-02 14:27:10', 1),
(65, 63, 'productDetails', 'Lyn&#39;s banana chips is known for maintaining high quality, using local bananas sourced from the area. These chips are often sold in various packaging options, making them convenient for gifting or personal enjoyment. In addition to the original flavor, there may be variations with added seasonings or flavors to cater to different tastes.', 'Lyn&#39; s banana chips is known for maintaining high quality, using local bananas sourced from the area. These chips are often sold in various packaging options, making them convenient for gifting or personal enjoyment. In addition to the original flavor, there may be variations with added seasonings or flavors to cater to different tastes.', '2024-11-02 14:27:10', 1),
(66, 63, 'productName', 'Lyn&#39; s Banana Chips', 'Erlinda&#39;s Banana Chips', '2024-11-02 14:27:43', 1),
(67, 63, 'productImage', 'Lyn&#39;s Banana Chips.jpeg', 'Banana Chips.jpeg', '2024-11-02 14:27:43', 1),
(68, 63, 'productDetails', 'Lyn&#39; s banana chips is known for maintaining high quality, using local bananas sourced from the area. These chips are often sold in various packaging options, making them convenient for gifting or personal enjoyment. In addition to the original flavor, there may be variations with added seasonings or flavors to cater to different tastes.', 'Erlinda&#39; s banana chips is known for maintaining high quality, using local bananas sourced from the area. These chips are often sold in various packaging options, making them convenient for gifting or personal enjoyment. In addition to the original flavor, there may be variations with added seasonings or flavors to cater to different tastes.', '2024-11-02 14:27:43', 1),
(69, 63, 'productName', 'Erlinda&#39;s Banana Chips', 'Erlindas Banana Chips', '2024-11-02 14:28:25', 1),
(70, 63, 'productImage', 'Banana Chips.jpeg', 'Erlindas Banana Chips.jpeg', '2024-11-02 14:28:25', 1),
(71, 63, 'productDetails', 'Erlinda&#39; s banana chips is known for maintaining high quality, using local bananas sourced from the area. These chips are often sold in various packaging options, making them convenient for gifting or personal enjoyment. In addition to the original flavor, there may be variations with added seasonings or flavors to cater to different tastes.', 'Erlindas banana chips is known for maintaining high quality, using local bananas sourced from the area. These chips are often sold in various packaging options, making them convenient for gifting or personal enjoyment. In addition to the original flavor, there may be variations with added seasonings or flavors to cater to different tastes.', '2024-11-02 14:28:25', 1),
(72, 64, 'productDetails', 'Kalkag, available at El&#39;s Dry Goods in Tigbauan, is a type of dried fish popular in the Philippines, particularly in the Iloilo region. This product is made from small fish, often anchovies, which are salted, dried, and sun-dried to preserve their flavor and texture.', 'Kalkag, available at Els Dry Goods in Tigbauan, is a type of dried fish popular in the Philippines, particularly in the Iloilo region. This product is made from small fish, often anchovies, which are salted, dried, and sun-dried to preserve their flavor and texture.', '2024-11-02 14:32:31', 1),
(73, 65, 'productDetails', 'D&#39;Original Jaspe, located in Santa Barbara, Iloilo, is renowned for its Special Baye-Baye, a traditional Ilonggo delicacy that has garnered significant popularity. This unique treat is primarily made from pinipig, which is pounded rice, and is combined with grated coconut and sugar, creating a sweet, chewy texture.', 'D Original Jaspe, located in Santa Barbara, Iloilo, is renowned for its Special Baye-Baye, a traditional Ilonggo delicacy that has garnered significant popularity. This unique treat is primarily made from pinipig, which is pounded rice, and is combined with grated coconut and sugar, creating a sweet, chewy texture.', '2024-11-02 14:40:59', 1),
(74, 66, 'productDetails', 'The Roasted Seasalt Lemongrass from Miagao, known locally as &#34;Budbud,&#34; is a unique artisanal product that embodies traditional salt-making techniques. This salt is created using bamboo nodes, which serve as filtration vessels. Coastal sand is placed inside these bamboo splits, and seawater is poured in to evaporate under the sun, resulting in a clearer and more crystalline salt that is less salty than typical varieties. Following this process, the salt is roasted for food safety and flavor. ', 'The Roasted Seasalt Lemongrass from Miagao, known locally as Budbud; is a unique artisanal product that embodies traditional salt-making techniques. This salt is created using bamboo nodes, which serve as filtration vessels. Coastal sand is placed inside these bamboo splits, and seawater is poured in to evaporate under the sun, resulting in a clearer and more crystalline salt that is less salty than typical varieties. Following this process, the salt is roasted for food safety and flavor. ', '2024-11-02 14:48:05', 1),
(75, 73, 'productDetails', 'Kamote Chips by Bay Sur Food Products, a company based in Miagao, Iloilo, Philippines, is a snack made from locally sourced sweet potatoes, also known as &#34;kamote&#34; in Filipino. These chips are crafted using high-quality sweet potatoes, sliced thinly, and fried to a crisp, offering a unique and natural flavor profile that&#39;s both sweet and savory. ', 'Kamote Chips by Bay Sur Food Products, a company based in Miagao, Iloilo, Philippines, is a snack made from locally sourced sweet potatoes, also known as kamote in Filipino. These chips are crafted using high-quality sweet potatoes, sliced thinly, and fried to a crisp, offering a unique and natural flavor profile thats both sweet and savory. ', '2024-11-02 15:37:51', 1),
(79, 65, 'productPrice', '30', '300', '2024-11-24 03:27:05', 1),
(80, 55, 'productPrice', '650', '555', '2025-02-21 06:22:30', 1),
(81, 56, 'productPrice', '700', '200', '2025-02-21 06:22:53', 1),
(82, 55, 'productName', 'Ube Pie', 'Ube Piehh', '2025-02-21 07:25:47', 1),
(83, 55, 'productDetails', 'Ube Pie is one of the delectable offerings from their bakery, which is well-known for its wide range of pastries. The ube pie features a rich filling made from ube (purple yam), which is a popular ingredient in Filipino desserts. ', 'YOW', '2025-02-21 07:32:54', 1),
(84, 55, 'productDetails', 'YOW', 'YOWsz', '2025-02-21 07:34:29', 1),
(87, 7, 'productName', 'Cassava Cake', 'JOEIII', '2025-02-23 02:50:57', 1),
(88, 7, 'productPrice', '500', '255', '2025-02-23 02:50:57', 1),
(89, 7, 'productDetails', 'cassava cake is crafted from freshly grated cassava root, sweetened with milk to create a balanced flavor that is both mildly sweet and rich.', 'Meow', '2025-02-23 02:50:57', 1),
(90, 7, 'productName', 'JOEIII', 'JOEIIIEE', '2025-02-23 02:55:26', 1),
(92, 54, 'productPrice', '600', '500021321', '2025-02-25 05:36:30', 1),
(94, 54, 'productPrice', '500021321', '123421', '2025-02-26 15:35:11', 1),
(95, 55, 'productPrice', '555', '5502', '2025-02-27 02:50:21', 1),
(96, 55, 'productPrice', '5502', '555', '2025-02-27 03:09:50', 1),
(97, 55, 'productDetails', 'YOWsz', 'oke', '2025-02-27 03:09:50', 1),
(98, 55, 'productPrice', '555', '1000', '2025-02-27 03:11:43', 1),
(99, 55, 'productDetails', 'oke', 'ube', '2025-02-27 03:11:43', 1),
(100, 55, 'productName', 'Ube Piehh', 'Ube Pie', '2025-02-27 03:16:23', 1),
(101, 55, 'productPrice', '1000', '2222', '2025-02-27 03:16:23', 1),
(102, 55, 'productDetails', 'ube', 'dasdasdsad', '2025-02-27 03:16:23', 1),
(103, 55, 'productName', 'Ube Pie', '1231sadas', '2025-02-27 03:20:58', 1),
(104, 55, 'productPrice', '2222', '5000', '2025-02-27 03:20:58', 1),
(105, 55, 'productDetails', 'dasdasdsad', 'sddsafsadfas', '2025-02-27 03:20:58', 1),
(106, 54, 'productName', 'meoweiwewi', 'Seah Salth', '2025-02-28 07:33:17', 1),
(107, 54, 'productPrice', '123421', '500', '2025-02-28 07:33:17', 1),
(108, 54, 'productDetails', 'Chicken Pie is a delightful offering from their bakery, known for its tender pieces of chicken enveloped in a flaky, buttery crust. This pie is designed to provide a comforting, savory experience, making it a favorite among locals and visitors alike. It reflects the shop&#39;s commitment to quality, using fresh ingredients and time-honored baking techniques to ensure each pie is flavorful and satisfying.', 'Seaaah Salt', '2025-02-28 07:33:17', 1),
(109, 55, 'productPrice', '5000', '222', '2025-03-02 06:32:42', 1),
(110, 56, 'productPrice', '200', '30000', '2025-03-02 06:39:46', 1),
(113, 54, 'productPrice', '500', '255', '2025-03-02 06:58:03', 1),
(114, 54, 'productImage', 'Chicken Pie.png', '8bc03ef7-986b-45c7-bb17-33893ee30cc3.jpg', '2025-03-02 06:58:03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `purchase_id` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`purchase_id`, `transaction_id`, `product_id`, `quantity`, `total_price`) VALUES
(36, 'A-2024-10-27-1', 8, 1, 50.00),
(37, 'A-2024-10-27-1', 11, 1, 70.00),
(38, 'A-2024-10-27-2', 43, 1, 101.00),
(41, 'A-2024-11-04-2', 66, 2, 200.00),
(42, 'A-2024-11-04-3', 58, 1, 150.00),
(43, 'A-2024-11-04-4', 65, 5, 150.00),
(44, 'A-2024-11-18-1', 66, 1, 100.00),
(45, 'A-2024-11-18-2', 66, 2, 200.00),
(46, 'A-2024-11-24-1', 65, 1, 30.00),
(47, 'A-2025-02-21-1', 7, 1, 500.00),
(48, 'A-2025-02-21-1', 8, 1, 300.00),
(49, 'A-2025-02-21-1', 8, 1, 300.00),
(52, 'A-2025-02-21-4', 7, 1, 500.00),
(53, 'A-2025-02-21-5', 8, 1, 300.00),
(54, 'A-2025-02-21-6', 7, 1, 500.00),
(55, 'A-2025-02-21-7', 7, 1, 500.00),
(57, 'A-2025-02-21-9', 7, 1, 500.00),
(58, 'A-2025-02-27-1', 7, 1, 255.00),
(59, 'A-2025-02-27-1', 9, 1, 350.00),
(60, 'A-2025-02-27-1', 7, 1, 255.00),
(62, 'A-2025-02-27-3', 7, 1, 255.00),
(63, 'A-2025-02-27-3', 8, 12, 3600.00),
(64, 'A-2025-02-27-4', 9, 2, 700.00),
(65, 'A-2025-02-28-1', 8, 1, 300.00),
(66, 'A-2025-02-28-1', 54, 10, 5000.00),
(68, 'A-2025-06-06-2', 56, 1, 30000.00);

-- --------------------------------------------------------

--
-- Table structure for table `qr_scans`
--

CREATE TABLE `qr_scans` (
  `id` int(11) NOT NULL,
  `scan_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `ReviewStat` tinyint(1) NOT NULL,
  `product_id` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qr_scans`
--

INSERT INTO `qr_scans` (`id`, `scan_time`, `ReviewStat`, `product_id`, `transaction_id`) VALUES
(52, '2024-10-27 12:43:27', 1, 0, 'A-2024-10-27-2'),
(53, '2024-11-03 14:39:33', 0, 0, 'A-2024-11-03-1'),
(54, '2024-11-03 14:48:27', 0, 0, 'A-2024-11-03-1'),
(55, '2024-11-04 04:09:22', 0, 0, 'A-2024-11-03-1'),
(56, '2024-11-04 04:11:54', 0, 0, 'A-2024-11-03-1'),
(57, '2024-11-04 04:12:40', 0, 0, 'A-2024-11-03-1'),
(58, '2024-11-04 04:14:43', 0, 0, 'A-2024-11-04-2'),
(59, '2024-11-04 04:21:52', 0, 0, 'A-2024-11-04-3'),
(60, '2024-11-04 05:31:42', 0, 0, 'A-2024-11-04-3'),
(61, '2024-11-04 05:40:18', 0, 0, 'A-2024-11-04-4'),
(62, '2024-11-04 14:53:58', 1, 0, 'A-2024-11-04-1'),
(63, '2024-11-18 04:02:30', 1, 0, 'A-2024-11-18-1'),
(64, '2025-02-28 14:46:08', 0, 0, 'A-2024-10-27-1'),
(65, '2025-02-28 14:47:51', 1, 0, 'A-2025-02-28-1'),
(66, '2025-02-28 14:48:26', 1, 0, 'A-2025-02-28-1'),
(67, '2025-03-01 04:21:54', 1, 0, 'A-2025-02-28-1'),
(68, '2025-03-01 04:27:26', 1, 0, 'A-2025-02-28-1'),
(69, '2025-03-01 04:28:47', 1, 0, 'A-2025-02-28-1'),
(70, '2025-03-01 04:42:53', 1, 0, 'A-2025-02-28-1'),
(71, '2025-03-01 04:43:31', 1, 0, 'A-2025-02-28-1'),
(72, '2025-03-01 09:21:22', 1, 0, 'A-2025-02-28-1'),
(73, '2025-03-01 09:24:32', 1, 0, 'A-2025-02-28-1'),
(74, '2025-03-01 09:40:22', 1, 0, 'A-2025-02-28-1'),
(75, '2025-03-01 09:40:41', 1, 0, 'A-2025-02-28-1'),
(76, '2025-03-01 09:41:27', 1, 0, 'A-2025-02-28-1'),
(77, '2025-03-01 09:42:17', 1, 0, 'A-2025-02-28-1'),
(78, '2025-03-01 09:42:51', 1, 0, 'A-2025-02-28-1'),
(79, '2025-03-01 09:43:26', 1, 0, 'A-2025-02-28-1'),
(80, '2025-03-01 09:43:33', 1, 0, 'A-2025-02-28-1'),
(81, '2025-03-01 09:44:14', 1, 0, 'A-2025-02-28-1'),
(82, '2025-03-01 09:44:44', 1, 0, 'A-2025-02-28-1'),
(83, '2025-03-01 09:44:54', 1, 0, 'A-2025-02-28-1'),
(84, '2025-03-01 09:45:09', 1, 0, 'A-2025-02-28-1'),
(85, '2025-03-01 09:45:14', 1, 0, 'A-2025-02-28-1'),
(86, '2025-03-01 09:45:29', 1, 0, 'A-2025-02-28-1'),
(87, '2025-03-01 09:45:35', 1, 0, 'A-2025-02-28-1'),
(88, '2025-03-01 09:45:39', 1, 0, 'A-2025-02-28-1'),
(89, '2025-03-01 09:45:59', 1, 0, 'A-2025-02-28-1'),
(90, '2025-03-01 09:46:40', 1, 0, 'A-2025-02-28-1'),
(91, '2025-03-01 09:47:42', 1, 0, 'A-2025-02-28-1'),
(92, '2025-03-01 10:05:35', 1, 0, 'A-2025-02-28-1'),
(93, '2025-03-02 03:34:50', 1, 0, 'A-2025-02-28-1'),
(94, '2025-03-02 03:45:09', 1, 0, 'A-2025-02-28-1'),
(95, '2025-03-02 03:49:37', 1, 0, 'A-2025-02-28-1'),
(96, '2025-03-02 03:52:03', 1, 0, 'A-2025-02-28-1'),
(97, '2025-03-02 03:56:18', 0, 0, 'A-2025-02-28-1'),
(98, '2025-03-02 03:56:45', 0, 0, 'A-2025-02-28-1'),
(99, '2025-06-06 04:28:46', 1, 0, 'A-2025-02-21-1'),
(100, '2025-06-06 04:31:25', 0, 0, 'A-2025-02-21-1'),
(101, '2025-06-06 04:32:58', 0, 0, 'A-2025-02-21-1'),
(102, '2025-06-06 04:38:21', 0, 0, 'A-2025-06-06-2');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(4) NOT NULL,
  `Con_status` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `name`, `created_at`, `status`, `Con_status`) VALUES
(1, 'Trial 1', '2025-03-02 06:25:29', 1, 1),
(2, 'asd', '2025-03-02 06:33:46', 1, 1),
(3, 'asdasddsfsdafasdfsad', '2025-03-02 06:40:52', 1, 1),
(4, 'okoko', '2025-03-02 06:51:19', 1, 1),
(5, 'jl;jk', '2025-03-02 07:28:48', 1, 1),
(6, 'Marian', '2025-04-28 05:58:20', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `system_feedback`
--

CREATE TABLE `system_feedback` (
  `feedbackID` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `comment` text NOT NULL,
  `feedback_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_feedback`
--

INSERT INTO `system_feedback` (`feedbackID`, `name`, `comment`, `feedback_date`) VALUES
(1, NULL, 'lol', '2024-10-17 14:27:42'),
(2, 'TESTING', 'lol', '2024-10-17 14:37:38'),
(3, 'admin', 'dwe', '2024-10-17 14:39:23'),
(4, 'admin', 'dwe', '2024-10-17 14:39:46'),
(5, 'admin', 'lol', '2024-10-17 14:42:39'),
(6, '', 'The buko pie is very good', '2024-11-04 11:19:05'),
(7, 'Buko Pie', 'The Product is very good', '2024-11-04 11:21:13'),
(8, 'Jave', 'The taste is bad', '2024-11-04 11:25:53'),
(9, '', 'The Product is bad', '2024-11-04 11:28:05'),
(10, '', 'The Product is Very Bad', '2024-11-04 12:10:00'),
(11, 'Dikembie', 'afeowiufoiuwnfoiunsadfsd', '2024-11-18 12:03:14');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` varchar(50) NOT NULL,
  `msme_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `total_price` int(50) NOT NULL,
  `quantity` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `msme_id`, `user_id`, `transaction_date`, `total_price`, `quantity`) VALUES
('A-2024-10-27-1', 10, 35, '2024-10-27 20:35:03', 270, 5),
('A-2024-10-27-2', 10, 35, '2024-10-27 20:36:30', 101, 1),
('A-2024-11-03-1', 10, 74, '2024-11-03 22:23:21', 400, 2),
('A-2024-11-04-1', 10, 84, '2024-11-04 11:25:27', 200, 1),
('A-2024-11-04-2', 76, 81, '2024-11-04 12:14:05', 200, 2),
('A-2024-11-04-3', 85, 82, '2024-11-04 12:21:10', 150, 1),
('A-2024-11-04-4', 77, 83, '2024-11-04 13:36:23', 150, 5),
('A-2024-11-18-1', 76, 83, '2024-11-18 12:01:43', 100, 1),
('A-2024-11-18-2', 76, 90, '2024-11-18 12:06:09', 200, 2),
('A-2024-11-24-1', 77, 81, '2024-11-24 09:55:29', 30, 1),
('A-2025-02-21-1', 10, 74, '2025-02-21 16:06:05', 1100, 3),
('A-2025-02-21-2', 10, NULL, '2025-02-21 16:17:06', 200, 1),
('A-2025-02-21-3', 10, 74, '2025-02-21 16:41:44', 200, 1),
('A-2025-02-21-4', 10, NULL, '2025-02-21 16:41:59', 500, 1),
('A-2025-02-21-5', 10, NULL, '2025-02-21 16:45:41', 300, 1),
('A-2025-02-21-6', 10, 35, '2025-02-21 16:51:03', 500, 1),
('A-2025-02-21-7', 10, 74, '2025-02-21 17:08:46', 500, 1),
('A-2025-02-21-8', 10, 74, '2025-02-21 17:09:47', 200, 1),
('A-2025-02-21-9', 10, 74, '2025-02-21 17:10:13', 500, 1),
('A-2025-02-27-1', 10, 35, '2025-02-27 16:28:30', 860, 3),
('A-2025-02-27-2', 10, 74, '2025-02-27 16:41:07', 5000, 1),
('A-2025-02-27-3', 10, NULL, '2025-02-27 16:47:49', 3855, 13),
('A-2025-02-27-4', 10, NULL, '2025-02-27 16:54:05', 700, 2),
('A-2025-02-28-1', 10, 91, '2025-02-28 22:47:16', 5300, 11),
('A-2025-06-06-1', 10, 74, '2025-06-06 12:27:49', 200, 1),
('A-2025-06-06-2', 10, NULL, '2025-06-06 12:37:32', 30000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Mname` varchar(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `usertype` int(1) NOT NULL,
  `address` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `status` tinyint(10) NOT NULL,
  `profile_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `email`, `fname`, `Lname`, `Mname`, `username`, `password`, `phone`, `usertype`, `address`, `district`, `status`, `profile_image`) VALUES
(1, 'dikembie28@gmail.com', 'Kshark', 'Galilea', 'Kimmy', 'Comsulltant2', 'admin', '123456', 3, 'fasfs', '', 1, 'uploads/1749204870_elena.jpg'),
(10, 'Elena.tingson@gmail.com', 'ELENA', 'TINGSON', 'MALANDACC', 'elena', 'elenaa', '09111111113', 2, 'Brgy. Trapiche, Oton, Philippines', 'TIGBAUAN', 1, 'uploads/1749203965_elena.jpg'),
(35, 'example@gmail.com', 'Christian Paul', 'Meow', 'Regalado', 'ck', 'pass', '09137848803', 1, 'nani', '', 1, ''),
(65, 'Elena.tingson@gmail.com', 'DTI', 'Admin', 'Assessment', 'kim', 'admin', '09111111113', 0, 'Brgy. Trapiche, Oton, Philippines', '', 1, ''),
(67, 'ckklient@gmail.com', 'consultant1', '2', '1', 'con', 'pass', '098893048974', 3, 'N/A', '', 1, ''),
(74, 'kylecarlo.regalado@wvsu.edu.ph', 'KYLE CARLO', 'REGALADO', 'HINOJALES', 'kyle', 'kyle', '09070085226', 1, 'Pali Benedicto st. Mandurriao Iloilo City, Philippines', '', 1, ''),
(76, 'lorlie.noblezada@gmail.com', 'LORLIE', 'NOBLEZADA', 'PAGUNTALAN', 'lorlie', 'lorlie', '09957419479', 2, 'Brgy. Mambatad Miagao, Iloilo', '', 1, ''),
(77, 'joshue.jaspe@gmail.com', 'JOSHUA', 'JASPE', 'GARCIA', 'jaspe', 'jaspe', '09204291975', 2, 'Brgy. Cabugao Sur, Santa Barbara, Iloilo', '', 1, ''),
(78, 'lilibeth.sarmiento@gmail.com', 'LILIBETH E', 'SARMIENTO', 'EL', 'lili', 'lili', '09094532416', 2, 'Brgy. Baguingin, Tigbauan, Iloilo', '', 0, ''),
(79, 'erlinda.tuala@gmail.com', 'ERLINDA', 'TUALA', 'T.', 'erlinda', 'erlinda', '09185445737', 2, 'Brgy. Isauan, Tigbauan, Iloilo, Philippines', '', 0, ''),
(80, 'roqueta.praile@gmail.com', 'ROQUETA', 'PRAILE', 'R. ', 'roqueta', 'roqueta', '09092291974', 2, 'Brgy. Taro, Tigbauan, Iloilo', '', 0, ''),
(81, 'joeialexandra.villavert@wvsu.edu.ph', 'JOIE ALEXANDRA', 'VILLAVERT', 'MAALIW', 'joei', 'joei', '09468313702', 1, 'Sibalom, Antique, Iloilo, Philippines', '', 1, ''),
(82, 'shairashane.cabangisan@wvsu.edu.ph', 'SHAIRA SHANE', 'CABANGISAN', 'A', 'shaira', 'shaira', '09660568992', 1, 'Tigbauan, Iloilo', '', 1, ''),
(83, 'hazeljoy.manahan@wvsu.edu.ph', 'HAZEL JOY', 'MANAHAN', 'BEBING', 'hazel', 'hazel', '0907313702', 1, 'Santa Barbara, Iloilo', '', 1, ''),
(84, 'javefryan.go@wvsu.edu.ph', 'JAVE FRYAN', 'GO', 'PEDROSO', 'jave', 'jave', '09070099726', 1, 'Brgy. magsaysay Valeria st. Iloilo City', '', 1, ''),
(85, 'filene.balleza@gmail.com', 'FILENE THERESE', 'BALLEZA', 'TILANO', 'filene', 'filene', '09054316313', 2, 'Atabayan, Tigbauan, Iloilo', '', 1, ''),
(86, 'joemar@gmail.com', 'JOEMAR', 'MARJOE', 'B.', 'joemar', 'joemar', '09073683414', 2, 'Brgy. Durog, Miagao, Iloilo, Philippines', '', 1, ''),
(87, 'marysol.villarta@gmail.com', 'MARY', 'VILLARTA', 'SOL', 'mary', 'mary', '09089856846', 2, 'Brgy. Kirayan Sur. Miagao, Iloilo', '', 1, ''),
(89, 'selena@gmail.com', 'selena', 'gomez', 'grey', 'selena', 'selena', '091413242562', 3, 'jaro', '', 1, ''),
(90, 'das@gmail.com', 'da', 'dsa', 'das', 'asd', 'asd', '2131', 1, '123asd', '', 1, ''),
(91, 'dikembie28@gmail.com', 'Dikembie', 'Galilea', 'Macavinta', 'dikembie28', 'admin', '09610285013', 1, 'Dalipe', '', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `business_information`
--
ALTER TABLE `business_information`
  ADD PRIMARY KEY (`bi_id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `chatmember`
--
ALTER TABLE `chatmember`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `consultancyquestionnaire`
--
ALTER TABLE `consultancyquestionnaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`user_id`);

--
-- Indexes for table `consultation_report`
--
ALTER TABLE `consultation_report`
  ADD PRIMARY KEY (`ConReport_ID`),
  ADD KEY `consultationID` (`consultationID`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_updates`
--
ALTER TABLE `product_updates`
  ADD PRIMARY KEY (`update_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `qr_scans`
--
ALTER TABLE `qr_scans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_feedback`
--
ALTER TABLE `system_feedback`
  ADD PRIMARY KEY (`feedbackID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `business_information`
--
ALTER TABLE `business_information`
  MODIFY `bi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `chatmember`
--
ALTER TABLE `chatmember`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `consultancyquestionnaire`
--
ALTER TABLE `consultancyquestionnaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `consultation_report`
--
ALTER TABLE `consultation_report`
  MODIFY `ConReport_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `product_updates`
--
ALTER TABLE `product_updates`
  MODIFY `update_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `qr_scans`
--
ALTER TABLE `qr_scans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `system_feedback`
--
ALTER TABLE `system_feedback`
  MODIFY `feedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`),
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`);

--
-- Constraints for table `chatmember`
--
ALTER TABLE `chatmember`
  ADD CONSTRAINT `chatmember_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`),
  ADD CONSTRAINT `chatmember_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`);

--
-- Constraints for table `consultancyquestionnaire`
--
ALTER TABLE `consultancyquestionnaire`
  ADD CONSTRAINT `users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`);

--
-- Constraints for table `consultation_report`
--
ALTER TABLE `consultation_report`
  ADD CONSTRAINT `consultationID` FOREIGN KEY (`consultationID`) REFERENCES `consultancyquestionnaire` (`id`);

--
-- Constraints for table `product_updates`
--
ALTER TABLE `product_updates`
  ADD CONSTRAINT `product_updates_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`),
  ADD CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
