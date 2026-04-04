-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2026 at 12:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `haizimen_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_user_id` int(10) UNSIGNED NOT NULL,
  `doctor_id` int(10) UNSIGNED DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `parent_user_id`, `doctor_id`, `appointment_date`, `appointment_time`, `notes`, `status`, `created_at`) VALUES
(1, 1, 4, '2026-03-26', '23:08:00', 'Fever', 'cancelled', '2026-03-24 14:35:14');

-- --------------------------------------------------------

--
-- Table structure for table `caretakers`
--

CREATE TABLE `caretakers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `caretaker_name` varchar(150) NOT NULL,
  `experience_years` varchar(50) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `availability` varchar(100) DEFAULT NULL,
  `fee` varchar(50) DEFAULT NULL,
  `preferred_location` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `caretakers`
--

INSERT INTO `caretakers` (`id`, `user_id`, `caretaker_name`, `experience_years`, `skills`, `availability`, `fee`, `preferred_location`, `email`, `phone`, `created_at`) VALUES
(1, 9, 'Ak Ak', '7', 'New', 'Part time', '600', 'Ernakulam', 'ak+ghrfghjkjk@cesco.com', '+916546516515', '2026-03-25 11:02:04'),
(2, 16, 'tgrfedwsqa rfedws', 'dsfrds', 'rfdecsx', 'redws', '78', 'rfedws', 'aaa+dfghjklfd@gmail.com', '56789876567', '2026-03-29 08:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `caretaker_availability`
--

CREATE TABLE `caretaker_availability` (
  `id` int(11) NOT NULL,
  `caretaker_user_id` int(11) NOT NULL,
  `day_name` varchar(20) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 0,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caretaker_requests`
--

CREATE TABLE `caretaker_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_user_id` int(10) UNSIGNED NOT NULL,
  `caretaker_id` int(10) UNSIGNED NOT NULL,
  `request_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `caretaker_requests`
--

INSERT INTO `caretaker_requests` (`id`, `parent_user_id`, `caretaker_id`, `request_date`, `notes`, `status`, `created_at`) VALUES
(1, 1, 1, '2026-03-28', '', 'completed', '2026-03-25 13:40:03');

-- --------------------------------------------------------

--
-- Table structure for table `daycares`
--

CREATE TABLE `daycares` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `center_name` varchar(150) NOT NULL,
  `manager_name` varchar(150) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `opening_time` varchar(50) DEFAULT NULL,
  `closing_time` varchar(50) DEFAULT NULL,
  `age_group_supported` varchar(100) DEFAULT NULL,
  `facilities` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daycares`
--

INSERT INTO `daycares` (`id`, `user_id`, `center_name`, `manager_name`, `capacity`, `opening_time`, `closing_time`, `age_group_supported`, `facilities`, `description`, `email`, `phone`, `address`, `created_at`) VALUES
(1, 7, 'Test', 'Test Center', 120, '23:51', '01:52', '7', 'Test', 'Test', 'ak+gvghjhbv@cesco.com', '+916546516515', '12st , kansas city', '2026-03-24 15:18:16'),
(2, 10, 'NA', 'NA Center', 3, '17:33', '21:33', '2-7', 'NA', 'NA', 'ak+dcfvgbhnjmk@cesco.com', '+916546516515', '12st , kansas city', '2026-03-25 11:03:51');

-- --------------------------------------------------------

--
-- Table structure for table `daycare_availability`
--

CREATE TABLE `daycare_availability` (
  `id` int(11) NOT NULL,
  `daycare_user_id` int(11) NOT NULL,
  `day_name` varchar(20) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 0,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daycare_requests`
--

CREATE TABLE `daycare_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_user_id` int(10) UNSIGNED NOT NULL,
  `daycare_id` int(10) UNSIGNED NOT NULL,
  `request_date` date NOT NULL,
  `child_age` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daycare_requests`
--

INSERT INTO `daycare_requests` (`id`, `parent_user_id`, `daycare_id`, `request_date`, `child_age`, `notes`, `status`, `created_at`) VALUES
(1, 1, 1, '2026-03-28', '8', 'test', 'pending', '2026-03-25 09:37:04');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `doctor_name` varchar(150) NOT NULL,
  `department` varchar(150) NOT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `qualification` varchar(150) DEFAULT NULL,
  `clinic_name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `doctor_name`, `department`, `hospital_id`, `email`, `phone`, `created_at`, `qualification`, `clinic_name`) VALUES
(4, 6, 'Akshay Augustin', 'NA', NULL, 'ak+fghj@cesco.com', '+5676567656', '2026-03-24 14:34:33', 'Btech', 'AIMS'),
(5, 8, 'NA Na', 'NA', NULL, 'ak+ghjk@cesco.com', '+916546516515', '2026-03-25 07:00:41', 'NA', 'NA'),
(6, 15, 'edrftghyj dfghjk', 'fghj', NULL, 'aaa+dfghjk@gmail.com', '7722889977', '2026-03-29 08:49:57', 'dfghnj', 'defghj'),
(7, 18, 'Akshay Augustin', 'Pediatrics', NULL, 'akshay@gmail.com', '876545678987654', '2026-03-31 14:25:55', 'MBBS', 'ABC HOSP'),
(8, 20, 'Akshay Augustin', 'Pediatrics', 1, 'fff@gmail.com', '6666666666', '2026-03-31 16:18:11', 'MBBS', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_availability`
--

CREATE TABLE `doctor_availability` (
  `id` int(11) NOT NULL,
  `doctor_user_id` int(11) NOT NULL,
  `day_name` varchar(20) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 0,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_availability`
--

INSERT INTO `doctor_availability` (`id`, `doctor_user_id`, `day_name`, `is_available`, `start_time`, `end_time`) VALUES
(1, 15, 'Monday', 1, '20:51:00', '02:55:00'),
(2, 15, 'Tuesday', 0, NULL, NULL),
(3, 15, 'Wednesday', 0, NULL, NULL),
(4, 15, 'Thursday', 0, NULL, NULL),
(5, 15, 'Friday', 0, NULL, NULL),
(6, 15, 'Saturday', 0, NULL, NULL),
(7, 15, 'Sunday', 0, NULL, NULL),
(8, 18, 'Monday', 1, '08:00:00', '12:00:00'),
(9, 18, 'Tuesday', 1, '08:00:00', '12:00:00'),
(10, 18, 'Wednesday', 1, '08:00:00', '12:00:00'),
(11, 18, 'Thursday', 1, '08:00:00', '12:00:00'),
(12, 18, 'Friday', 1, '08:00:00', '12:00:00'),
(13, 18, 'Saturday', 0, NULL, NULL),
(14, 18, 'Sunday', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hospital_name` varchar(150) NOT NULL,
  `registration_number` varchar(100) DEFAULT NULL,
  `hospital_type` varchar(100) DEFAULT NULL,
  `contact_person` varchar(120) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `user_id`, `hospital_name`, `registration_number`, `hospital_type`, `contact_person`, `email`, `phone`, `address`, `city`, `state`, `pincode`, `opening_time`, `closing_time`, `description`, `created_at`) VALUES
(1, 19, 'ABC HOSPITAL', 'ABC HOSPITAL', 'Children', 'ABC HOSPITAL', 'abc@gmail.com', '6666666666', 'ABC HOSPITAL', 'ABC HOSPITAL', 'ABC HOSPITAL', 'ABC HOSPITAL', '02:46:00', '15:46:00', 'ABC HOSPITAL', '2026-03-31 16:16:40');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `user_id`, `Username`, `Password`, `created_at`) VALUES
(1, 1, 'akshayaugustine43@gmail.com', '$2y$10$rLXKb6zMGhyouY/WZpTIwOuZFxZiDOyiuI0vGHRtOSpQYqNmkj.Sm', '2026-03-23 15:37:16'),
(2, NULL, 'ashil@gmail.com', '$2y$10$eveWtOd7eUUGlUxP5zOEmeSFtByrXLFzHHEOl00usNbBnHtwv5y4C', '2026-03-23 16:46:07'),
(3, NULL, 'test', '$2y$10$bsOedjRKSZ5JgjwF7f6Sq.RuqvuPTDHq/Ec8C3pYhUvcHOUmX0ake', '2026-03-24 14:13:01'),
(4, 6, 'doctor', '$2y$10$qYeBgKC4S5ictGzbMz6jDOgCponHW3JFYWNGemNoWAJMQ0ZeG0wR6', '2026-03-24 14:34:33'),
(5, 7, 'daycare', '$2y$10$pdXfMEbPCkCuU.77ciOdhOBi8qdY9K6LCk3mcg28PTIFaubK0VaUa', '2026-03-24 15:18:16'),
(6, 8, 'doctor@gmail.com', '$2y$10$4qIf5D2WPASoia97yESEnO2DAF.F8oToovXt02dE9mGaaC2/1ttHa', '2026-03-25 07:00:41'),
(7, 9, 'caretaker', '$2y$10$vCywE.SfN2M9BWtbI2Zvye6yDcYKVVmPNYxhBIJFPABmifPf8iLm6', '2026-03-25 11:02:04'),
(8, 10, 'daycare123', '$2y$10$SYYMGVbmAQhdm9O6J..iIujnT0yaJQwWUAN.ijP3.hooBZvAhCHAe', '2026-03-25 11:03:51'),
(9, 11, 'daycare555', '$2y$10$uqzlQQOONulI5DtaCcvVVejG6HOzF/mi0Sv2h2CCw8skJeSyCzSG.', '2026-03-25 11:48:31'),
(10, 12, 'princy', '$2y$10$AZb4pLTikwLy6C0z0VsaTOosYtt1lG6ZQTSLipdNHCp7DdUs311vG', '2026-03-25 11:53:07'),
(11, 13, 'akshay', '$2y$10$CTBV8onRnPwLRcnNMk5jReHGxH07vi4iVtyxpHL7NrgIVJncAvi8i', '2026-03-25 11:55:56'),
(12, 14, 'parent123', '$2y$10$GNbHDoc2s9V860sHVwR.FOkUYuLydyN5xPD1bbJYowbn04do5.5HK', '2026-03-29 08:48:42'),
(13, 15, 'doctor12345', '$2y$10$XfYKvwPUwa.n696nnwEHKegDx42sT2EtaQpwMOTNrXosrgm6xglG.', '2026-03-29 08:49:57'),
(14, 16, 'dcfvgbhjk', '$2y$10$GwDIwXzcgOvVvY0LHebNRO16FXXzWcb/8sOhVKk/fXN0P46Wy7DQW', '2026-03-29 08:51:17'),
(15, 17, 'sheeba', '$2y$10$41aTW8u4hKdZ3jKwgdyjTeMPGbdh0plgFflRd9pVg1FcaAvT7ZSDm', '2026-03-31 14:24:45'),
(16, 18, 'akshay1', '$2y$10$EqL9IQ2I3B8pObZ.D3rXMOfJwFPrRztsx/0x7utbs.1Hvn11z5WUe', '2026-03-31 14:25:55'),
(17, 19, 'hospital', '$2y$10$.PJ5gt.4FLAbPunIvhyQb.ONx4SBrBDijttuzbH2HGdxRWQ6AjFt2', '2026-03-31 16:16:40'),
(18, 20, 'ashil', '$2y$10$SY7L5SHIRoe3rXd3r.WeQuoDTW53.FIAV/MwpzF.Ek3W.yU4z7sK6', '2026-03-31 16:18:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'parent',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `certificate_path` varchar(255) DEFAULT NULL,
  `blood_group` varchar(20) DEFAULT NULL,
  `height` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `qualification_certificate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `first_name`, `last_name`, `gender`, `dob`, `certificate_path`, `blood_group`, `height`, `weight`, `mother_name`, `father_name`, `address`, `email`, `phone`, `username`, `password_hash`, `created_at`, `qualification_certificate`) VALUES
(1, 'parent', 'Ak', 'Ak', 'male', '2001-10-22', '', 'a -ve', '173', '156', 'Sheeba', 'Augustin', '12st , kansas city', 'ak@cesco.com', '+916546516515', 'akshayaugustine43@gmail.com', '$2y$10$iPsnIvpn1gUUywrcIUKn0eO32RprixKL2vXMQFsEsQZkfTLOxtlBm', '2026-03-23 15:37:16', NULL),
(4, 'admin', 'System', 'Admin', 'others', '2000-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin@haizimen.com', '9999999999', 'admin', '$2y$10$Hf8PeNn7z4JcyqA4dNYCneogZeqXhWqrufAMziQglMJ9xHZHxn2R6', '2026-03-23 17:47:36', NULL),
(6, 'doctor', 'Akshay', 'Augustin', 'male', '2026-03-10', '', '', '', '', '', '', '12st , kansas city', 'ak+fghj@cesco.com', '+5676567656', 'doctor', '$2y$10$qYeBgKC4S5ictGzbMz6jDOgCponHW3JFYWNGemNoWAJMQ0ZeG0wR6', '2026-03-24 14:34:33', NULL),
(7, 'daycare', 'Test', 'Center', 'others', '2000-01-01', '', '', '', '', '', '', '12st , kansas city', 'ak+gvghjhbv@cesco.com', '+916546516515', 'daycare', '$2y$10$pdXfMEbPCkCuU.77ciOdhOBi8qdY9K6LCk3mcg28PTIFaubK0VaUa', '2026-03-24 15:18:16', NULL),
(8, 'doctor', 'NA', 'Na', 'male', '2018-05-14', '', '', '', '', '', '', '12st , kansas city', 'ak+ghjk@cesco.com', '+916546516515', 'doctor@gmail.com', '$2y$10$4qIf5D2WPASoia97yESEnO2DAF.F8oToovXt02dE9mGaaC2/1ttHa', '2026-03-25 07:00:41', NULL),
(9, 'caretaker', 'Ak', 'Ak', 'male', '2026-03-13', '', '', '', '', '', '', '12st , kansas city', 'ak+ghrfghjkjk@cesco.com', '+916546516515', 'caretaker', '$2y$10$vCywE.SfN2M9BWtbI2Zvye6yDcYKVVmPNYxhBIJFPABmifPf8iLm6', '2026-03-25 11:02:04', NULL),
(10, 'daycare', 'NA', 'Center', 'others', '2000-01-01', '', '', '', '', '', '', '12st , kansas city', 'ak+dcfvgbhnjmk@cesco.com', '+916546516515', 'daycare123', '$2y$10$SYYMGVbmAQhdm9O6J..iIujnT0yaJQwWUAN.ijP3.hooBZvAhCHAe', '2026-03-25 11:03:51', NULL),
(11, 'parent', 'NA', 'Center', 'others', '2000-01-01', '', '', '', '', '', '', '12st , kansas city', 'princypraksh5222@gmail.com', '+916546516515', 'daycare555', '$2y$10$uqzlQQOONulI5DtaCcvVVejG6HOzF/mi0Sv2h2CCw8skJeSyCzSG.', '2026-03-25 11:48:31', NULL),
(12, 'parent', 'Princy', 'pp', 'female', '2026-03-12', '', '', '', '', '', '', '12st , kansas city', 'princyprakash5222@gmail.com', '+916546516515', 'princy', '$2y$10$AZb4pLTikwLy6C0z0VsaTOosYtt1lG6ZQTSLipdNHCp7DdUs311vG', '2026-03-25 11:53:07', NULL),
(13, 'parent', 'Akshay', 'Aug', 'male', '2026-03-07', '', '', '', '', '', '', '', 'akshayaugustine43+00@gmail.com', '777777777777', 'akshay', '$2y$10$CTBV8onRnPwLRcnNMk5jReHGxH07vi4iVtyxpHL7NrgIVJncAvi8i', '2026-03-25 11:55:56', NULL),
(14, 'parent', 'Akshay', 'Asdfghj', 'male', '2026-03-17', '', 'fg', '', '', '', '', 'aaa@gmail.com', 'aaa@gmail.com', '6666666666', 'parent123', '$2y$10$GNbHDoc2s9V860sHVwR.FOkUYuLydyN5xPD1bbJYowbn04do5.5HK', '2026-03-29 08:48:42', ''),
(15, 'doctor', 'edrftghyj', 'dfghjk', 'female', '2026-03-27', '', '', '', '', '', '', 'dfghjkl', 'aaa+dfghjk@gmail.com', '7722889977', 'doctor12345', '$2y$10$XfYKvwPUwa.n696nnwEHKegDx42sT2EtaQpwMOTNrXosrgm6xglG.', '2026-03-29 08:49:57', 'public/uploads/certificates/cert_69c8e7b54dd332.68630862.pdf'),
(16, 'caretaker', 'tgrfedwsqa', 'rfedws', 'male', '2026-03-19', '', '', '', '', '', '', 'trfedws', 'aaa+dfghjklfd@gmail.com', '56789876567', 'dcfvgbhjk', '$2y$10$GwDIwXzcgOvVvY0LHebNRO16FXXzWcb/8sOhVKk/fXN0P46Wy7DQW', '2026-03-29 08:51:17', ''),
(17, 'parent', 'Sheeba Augustin', 'Augustin', 'female', '1967-06-30', '', 'B +ve', '', '', '', '', 'Kuzhippilly house', 'sheeba@gmail.com', '88888888888', 'sheeba', '$2y$10$41aTW8u4hKdZ3jKwgdyjTeMPGbdh0plgFflRd9pVg1FcaAvT7ZSDm', '2026-03-31 14:24:45', ''),
(18, 'doctor', 'Akshay', 'Augustin', 'male', '2001-10-22', '', '', '', '', '', '', 'trfedws', 'akshay@gmail.com', '876545678987654', 'akshay1', '$2y$10$EqL9IQ2I3B8pObZ.D3rXMOfJwFPrRztsx/0x7utbs.1Hvn11z5WUe', '2026-03-31 14:25:55', ''),
(19, 'hospital', 'ABC HOSPITAL', 'Admin', 'others', '2000-01-01', '', '', '', '', '', '', 'ABC HOSPITAL', 'abc@gmail.com', '6666666666', 'hospital', '$2y$10$.PJ5gt.4FLAbPunIvhyQb.ONx4SBrBDijttuzbH2HGdxRWQ6AjFt2', '2026-03-31 16:16:40', ''),
(20, 'doctor', 'Akshay', 'Augustin', 'male', '2026-03-25', '', '', '', '', '', '', 'trfedws', 'fff@gmail.com', '6666666666', 'ashil', '$2y$10$SY7L5SHIRoe3rXd3r.WeQuoDTW53.FIAV/MwpzF.Ek3W.yU4z7sK6', '2026-03-31 16:18:11', '');

-- --------------------------------------------------------

--
-- Table structure for table `vaccinations`
--

CREATE TABLE `vaccinations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `vaccine_name` varchar(150) NOT NULL,
  `dose_number` varchar(50) DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `booked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccines`
--

CREATE TABLE `vaccines` (
  `id` int(10) UNSIGNED NOT NULL,
  `vaccine_name` varchar(150) NOT NULL,
  `age_group` varchar(100) NOT NULL,
  `protects_against` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccines`
--

INSERT INTO `vaccines` (`id`, `vaccine_name`, `age_group`, `protects_against`, `description`, `status`, `created_at`) VALUES
(1, 'BCG', 'At Birth', 'Tuberculosis', 'Given at birth to protect against tuberculosis.', 'active', '2026-03-25 06:02:10'),
(2, 'OPV-0', 'At Birth', 'Polio', 'Oral polio vaccine birth dose.', 'active', '2026-03-25 06:02:10'),
(3, 'Hepatitis B (Birth Dose)', 'At Birth', 'Hepatitis B', 'Birth dose protecting against hepatitis B liver infection.', 'active', '2026-03-25 06:02:10'),
(4, 'OPV-1', '6 Weeks', 'Polio', 'First dose of oral polio vaccine.', 'active', '2026-03-25 06:02:10'),
(5, 'Pentavalent-1', '6 Weeks', 'Diphtheria, Pertussis, Tetanus, Hepatitis B, Hib', 'First pentavalent dose.', 'active', '2026-03-25 06:02:10'),
(6, 'Rotavirus-1', '6 Weeks', 'Severe Diarrhea', 'First rotavirus vaccine dose.', 'active', '2026-03-25 06:02:10'),
(7, 'FIPV-1', '6 Weeks', 'Polio', 'Fractional IPV first dose.', 'active', '2026-03-25 06:02:10'),
(8, 'PCV-1', '6 Weeks', 'Pneumonia, Meningitis', 'First pneumococcal vaccine dose.', 'active', '2026-03-25 06:02:10'),
(9, 'OPV-2', '10 Weeks', 'Polio', 'Second dose of oral polio vaccine.', 'active', '2026-03-25 06:02:10'),
(10, 'Pentavalent-2', '10 Weeks', 'DPT, Hep B, Hib', 'Second pentavalent dose.', 'active', '2026-03-25 06:02:10'),
(11, 'Rotavirus-2', '10 Weeks', 'Severe Diarrhea', 'Second rotavirus dose.', 'active', '2026-03-25 06:02:10'),
(12, 'OPV-3', '14 Weeks', 'Polio', 'Third dose of oral polio vaccine.', 'active', '2026-03-25 06:02:10'),
(13, 'Pentavalent-3', '14 Weeks', 'DPT, Hep B, Hib', 'Third pentavalent dose.', 'active', '2026-03-25 06:02:10'),
(14, 'FIPV-2', '14 Weeks', 'Polio', 'Fractional IPV second dose.', 'active', '2026-03-25 06:02:10'),
(15, 'Rotavirus-3', '14 Weeks', 'Severe Diarrhea', 'Third rotavirus dose.', 'active', '2026-03-25 06:02:10'),
(16, 'PCV-2', '14 Weeks', 'Pneumonia, Meningitis', 'Second pneumococcal vaccine dose.', 'active', '2026-03-25 06:02:10'),
(17, 'MR-1', '9-12 Months', 'Measles, Rubella', 'First MR vaccine dose.', 'active', '2026-03-25 06:02:10'),
(18, 'JE-1', '9-12 Months', 'Japanese Encephalitis', 'First JE vaccine dose.', 'active', '2026-03-25 06:02:10'),
(19, 'PCV Booster', '9-12 Months', 'Pneumonia, Meningitis', 'Booster dose of pneumococcal vaccine.', 'active', '2026-03-25 06:02:10'),
(20, 'MR-2', '16-24 Months', 'Measles, Rubella', 'Second MR vaccine dose.', 'active', '2026-03-25 06:02:10'),
(21, 'JE-2', '16-24 Months', 'Japanese Encephalitis', 'Second JE vaccine dose.', 'active', '2026-03-25 06:02:10'),
(22, 'DPT Booster-1', '16-24 Months', 'Diphtheria, Pertussis, Tetanus', 'First DPT booster.', 'active', '2026-03-25 06:02:10'),
(23, 'OPV Booster', '16-24 Months', 'Polio', 'Booster dose of OPV.', 'active', '2026-03-25 06:02:10'),
(24, 'DPT Booster-2', '5-6 Years', 'Diphtheria, Pertussis, Tetanus', 'Second DPT booster.', 'active', '2026-03-25 06:02:10'),
(25, 'Td', '10 Years', 'Tetanus, Diphtheria', 'Td vaccine at 10 years.', 'active', '2026-03-25 06:02:10'),
(26, 'Td', '16 Years', 'Tetanus, Diphtheria', 'Td vaccine at 16 years.', 'active', '2026-03-25 06:02:10'),
(27, 'Td-1 / Td-2 / Booster', 'Pregnant Mother', 'Protects Mother & Newborn From Tetanus', 'Tetanus protection schedule for pregnant mother.', 'active', '2026-03-25 06:02:10');

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_bookings`
--

CREATE TABLE `vaccine_bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_user_id` int(10) UNSIGNED NOT NULL,
  `vaccine_id` int(10) UNSIGNED NOT NULL,
  `doctor_id` int(10) UNSIGNED DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `hospital_status` varchar(30) NOT NULL DEFAULT 'not_sent',
  `sent_to_hospital_at` datetime DEFAULT NULL,
  `reminder_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccine_bookings`
--

INSERT INTO `vaccine_bookings` (`id`, `parent_user_id`, `vaccine_id`, `doctor_id`, `hospital_id`, `booking_date`, `notes`, `status`, `hospital_status`, `sent_to_hospital_at`, `reminder_date`, `created_at`) VALUES
(1, 1, 9, 5, NULL, '2026-03-28', 'Test', 'pending', 'not_sent', NULL, '2026-03-26', '2026-03-25 07:03:18'),
(2, 1, 24, 4, NULL, '2026-03-30', 'AA', 'pending', 'not_sent', NULL, '2026-03-28', '2026-03-26 14:36:09'),
(3, 17, 9, 7, NULL, '2026-03-31', '', 'pending', 'not_sent', NULL, '2026-03-29', '2026-03-31 16:19:06'),
(4, 17, 14, 8, 1, '2026-03-31', '', 'approved', 'approved', '2026-03-31 21:50:35', '2026-03-29', '2026-03-31 16:19:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_appointments_doctor` (`doctor_id`);

--
-- Indexes for table `caretakers`
--
ALTER TABLE `caretakers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_caretakers_user` (`user_id`);

--
-- Indexes for table `caretaker_availability`
--
ALTER TABLE `caretaker_availability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_caretaker_day` (`caretaker_user_id`,`day_name`);

--
-- Indexes for table `caretaker_requests`
--
ALTER TABLE `caretaker_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_caretaker_requests_parent` (`parent_user_id`),
  ADD KEY `fk_caretaker_requests_caretaker` (`caretaker_id`);

--
-- Indexes for table `daycares`
--
ALTER TABLE `daycares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_daycares_user` (`user_id`);

--
-- Indexes for table `daycare_availability`
--
ALTER TABLE `daycare_availability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_daycare_day` (`daycare_user_id`,`day_name`);

--
-- Indexes for table `daycare_requests`
--
ALTER TABLE `daycare_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_daycare_requests_parent` (`parent_user_id`),
  ADD KEY `fk_daycare_requests_daycare` (`daycare_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_doctor_user` (`user_id`);

--
-- Indexes for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_doctor_day` (`doctor_user_id`,`day_name`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_login_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vaccinations`
--
ALTER TABLE `vaccinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vaccines`
--
ALTER TABLE `vaccines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vaccine_bookings`
--
ALTER TABLE `vaccine_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vaccine_bookings_parent` (`parent_user_id`),
  ADD KEY `fk_vaccine_bookings_vaccine` (`vaccine_id`),
  ADD KEY `fk_vaccine_bookings_doctor` (`doctor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `caretakers`
--
ALTER TABLE `caretakers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `caretaker_availability`
--
ALTER TABLE `caretaker_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `caretaker_requests`
--
ALTER TABLE `caretaker_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `daycares`
--
ALTER TABLE `daycares`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `daycare_availability`
--
ALTER TABLE `daycare_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daycare_requests`
--
ALTER TABLE `daycare_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `vaccinations`
--
ALTER TABLE `vaccinations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccines`
--
ALTER TABLE `vaccines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `vaccine_bookings`
--
ALTER TABLE `vaccine_bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appointments_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `caretakers`
--
ALTER TABLE `caretakers`
  ADD CONSTRAINT `fk_caretakers_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `caretaker_requests`
--
ALTER TABLE `caretaker_requests`
  ADD CONSTRAINT `fk_caretaker_requests_caretaker` FOREIGN KEY (`caretaker_id`) REFERENCES `caretakers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_caretaker_requests_parent` FOREIGN KEY (`parent_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daycares`
--
ALTER TABLE `daycares`
  ADD CONSTRAINT `fk_daycares_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daycare_requests`
--
ALTER TABLE `daycare_requests`
  ADD CONSTRAINT `fk_daycare_requests_daycare` FOREIGN KEY (`daycare_id`) REFERENCES `daycares` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_daycare_requests_parent` FOREIGN KEY (`parent_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `fk_doctors_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `fk_login_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vaccine_bookings`
--
ALTER TABLE `vaccine_bookings`
  ADD CONSTRAINT `fk_vaccine_bookings_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_vaccine_bookings_parent` FOREIGN KEY (`parent_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_vaccine_bookings_vaccine` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccines` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
