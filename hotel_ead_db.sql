-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2026 at 06:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel_ead_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$3roCEjr74Ah8QZ0pVcMsAuV5XQuvyuZ8x4Qp8isCnjFN6SHcU5QBC');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled','Checked-in','Checked-out') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` enum('Pending','Completed','Failed') DEFAULT 'Pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Available','Booked','Maintenance') DEFAULT 'Available',
  `description` text DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `type`, `price`, `image`, `status`, `description`, `amenities`, `created_at`) VALUES
(1, 'Single Room 101', 'Single', 3500.00, '69ec46dc7f189.jpg', 'Available', 'A comfortable and affordable option for solo travelers, our Single Room offers a cozy space designed for relaxation and convenience. It features a comfortable single bed with fresh linens, along with essential amenities such as free Wi-Fi, a flat-screen TV, air conditioning, and a work desk. The room includes a private attached bathroom with a hot shower, fresh towels, and complimentary toiletries. With its clean design and daily housekeeping service, the Single Room is ideal for guests seeking a simple, peaceful, and hassle-free stay.', 'Free WiFi,Smart TV,Hot Water', '2026-04-25 04:38:43'),
(2, 'Single Room 103', 'Single', 3500.00, '69ec45c8c69d8.jpg', 'Available', 'A comfortable and affordable option for solo travelers, our Single Room offers a cozy space designed for relaxation and convenience. It features a comfortable single bed with fresh linens, along with essential amenities such as free Wi-Fi, a flat-screen TV, air conditioning, and a work desk. The room includes a private attached bathroom with a hot shower, fresh towels, and complimentary toiletries. With its clean design and daily housekeeping service, the Single Room is ideal for guests seeking a simple, peaceful, and hassle-free stay.', 'Free WiFi,Smart TV,Hot Water', '2026-04-25 04:39:23'),
(3, 'Single Room 105', 'Single', 3500.00, '69ec45cf2311e.jpg', 'Available', 'A comfortable and affordable option for solo travelers, our Single Room offers a cozy space designed for relaxation and convenience. It features a comfortable single bed with fresh linens, along with essential amenities such as free Wi-Fi, a flat-screen TV, air conditioning, and a work desk. The room includes a private attached bathroom with a hot shower, fresh towels, and complimentary toiletries. With its clean design and daily housekeeping service, the Single Room is ideal for guests seeking a simple, peaceful, and hassle-free stay.', 'Free WiFi,Smart TV,Hot Water', '2026-04-25 04:40:04'),
(4, 'Single Room 102', 'Single', 3500.00, '69ec45ead9ce3.jpg', 'Available', 'A comfortable and affordable option for solo travelers, our Single Room offers a cozy space designed for relaxation and convenience. It features a comfortable single bed with fresh linens, along with essential amenities such as free Wi-Fi, a flat-screen TV, air conditioning, and a work desk. The room includes a private attached bathroom with a hot shower, fresh towels, and complimentary toiletries. With its clean design and daily housekeeping service, the Single Room is ideal for guests seeking a simple, peaceful, and hassle-free stay.', 'Free WiFi,Smart TV,Hot Water', '2026-04-25 04:41:14'),
(5, 'Single Room 104', 'Single', 3500.00, '69ec46d6ba7db.jpg', 'Available', 'A comfortable and affordable option for solo travelers, our Single Room offers a cozy space designed for relaxation and convenience. It features a comfortable single bed with fresh linens, along with essential amenities such as free Wi-Fi, a flat-screen TV, air conditioning, and a work desk. The room includes a private attached bathroom with a hot shower, fresh towels, and complimentary toiletries. With its clean design and daily housekeeping service, the Single Room is ideal for guests seeking a simple, peaceful, and hassle-free stay.', 'Free WiFi,Smart TV,Hot Water', '2026-04-25 04:41:39'),
(6, 'Deluxe Room 201', 'Deluxe', 8000.00, '69ec468463903.jpg', 'Available', 'Experience added comfort and style in our Deluxe Room, offering a more spacious layout and upgraded furnishings. The room includes a large, comfortable bed, elegant décor, and enhanced amenities such as free Wi-Fi, a flat-screen TV, air conditioning, and a well-appointed seating or work area. The attached bathroom features modern fittings, a hot shower, fresh towels, and premium toiletries. Perfect for guests looking for a touch of luxury during their stay.', 'Free WiFi,Air Conditioning,Smart TV,Breakfast Included,Swimming Pool Access,Hot Water', '2026-04-25 04:43:48'),
(7, 'Deluxe Room 202', 'Deluxe', 8000.00, '69ec46995a847.jpg', 'Available', 'Experience added comfort and style in our Deluxe Room, offering a more spacious layout and upgraded furnishings. The room includes a large, comfortable bed, elegant décor, and enhanced amenities such as free Wi-Fi, a flat-screen TV, air conditioning, and a well-appointed seating or work area. The attached bathroom features modern fittings, a hot shower, fresh towels, and premium toiletries. Perfect for guests looking for a touch of luxury during their stay.', 'Free WiFi,Air Conditioning,Smart TV,Breakfast Included,Swimming Pool Access,Hot Water', '2026-04-25 04:44:09'),
(8, 'Deluxe Room 203', 'Deluxe', 8000.00, '69ec470c1f548.jpg', 'Available', '', 'Free WiFi,Air Conditioning,Smart TV,Breakfast Included,Swimming Pool Access,Hot Water', '2026-04-25 04:46:04'),
(9, 'Double Room 301', 'Double', 5500.00, '69ec475de3f07.jpg', 'Available', 'Perfect for couples or two guests, our Double Room offers a comfortable and relaxing stay with a spacious double bed or twin beds. The room is thoughtfully designed with modern amenities including free Wi-Fi, a flat-screen TV, air conditioning, and a work desk. Guests can enjoy a private attached bathroom with hot shower, fresh towels, and complimentary toiletries. Ideal for both leisure and business travelers seeking comfort and convenience.', 'Free WiFi,Smart TV,Hot Water', '2026-04-25 04:47:25'),
(10, 'Double Room 302', 'Double', 5500.00, '69ec4772b2969.jpg', 'Available', 'Perfect for couples or two guests, our Double Room offers a comfortable and relaxing stay with a spacious double bed or twin beds. The room is thoughtfully designed with modern amenities including free Wi-Fi, a flat-screen TV, air conditioning, and a work desk. Guests can enjoy a private attached bathroom with hot shower, fresh towels, and complimentary toiletries. Ideal for both leisure and business travelers seeking comfort and convenience.', 'Free WiFi,Smart TV,Hot Water', '2026-04-25 04:47:46'),
(11, 'Double Room 303', 'Double', 5500.00, '69ec4788ec13b.jpg', 'Available', 'Perfect for couples or two guests, our Double Room offers a comfortable and relaxing stay with a spacious double bed or twin beds. The room is thoughtfully designed with modern amenities including free Wi-Fi, a flat-screen TV, air conditioning, and a work desk. Guests can enjoy a private attached bathroom with hot shower, fresh towels, and complimentary toiletries. Ideal for both leisure and business travelers seeking comfort and convenience.', 'Free WiFi,Smart TV,Hot Water', '2026-04-25 04:48:08'),
(12, 'Double Room 401', 'Double', 5500.00, '69ec47ac4b149.jpg', 'Available', 'Perfect for couples or two guests, our Double Room offers a comfortable and relaxing stay with a spacious double bed or twin beds. The room is thoughtfully designed with modern amenities including free Wi-Fi, a flat-screen TV, air conditioning, and a work desk. Guests can enjoy a private attached bathroom with hot shower, fresh towels, and complimentary toiletries. Ideal for both leisure and business travelers seeking comfort and convenience.', 'Free WiFi,Smart TV,Hot Water', '2026-04-25 04:48:44'),
(13, 'Double Room 402', 'Double', 5500.00, '69ec47cb697ba.jpg', 'Available', 'Perfect for couples or two guests, our Double Room offers a comfortable and relaxing stay with a spacious double bed or twin beds. The room is thoughtfully designed with modern amenities including free Wi-Fi, a flat-screen TV, air conditioning, and a work desk. Guests can enjoy a private attached bathroom with hot shower, fresh towels, and complimentary toiletries. Ideal for both leisure and business travelers seeking comfort and convenience.', 'Free WiFi,Smart TV,Hot Water', '2026-04-25 04:49:15'),
(14, 'Deluxe Room 403', 'Deluxe', 8000.00, '69ec47fe425db.jpg', 'Available', 'Experience added comfort and style in our Deluxe Room, offering a more spacious layout and upgraded furnishings. The room includes a large, comfortable bed, elegant décor, and enhanced amenities such as free Wi-Fi, a flat-screen TV, air conditioning, and a well-appointed seating or work area. The attached bathroom features modern fittings, a hot shower, fresh towels, and premium toiletries. Perfect for guests looking for a touch of luxury during their stay.', 'Free WiFi,Air Conditioning,Smart TV,Breakfast Included,Swimming Pool Access,Hot Water', '2026-04-25 04:50:06'),
(15, 'Family Room 501', 'Family', 10000.00, '69ec4833629ba.jpeg', 'Available', 'Designed with families in mind, our Family Room provides ample space and comfort for a pleasant stay together. The room features multiple beds or a combination of a double bed and single beds, along with seating space for relaxation. Amenities include free Wi-Fi, a flat-screen TV, air conditioning, and a wardrobe. The private bathroom is equipped with a hot shower, fresh towels, and essential toiletries, making it a practical and cozy choice for family trips.', 'Free WiFi,Air Conditioning,Room Service,Breakfast Included,Hot Water', '2026-04-25 04:50:59'),
(16, 'Family Room 502', 'Family', 10000.00, '69ec484a1fa33.jpg', 'Available', 'Designed with families in mind, our Family Room provides ample space and comfort for a pleasant stay together. The room features multiple beds or a combination of a double bed and single beds, along with seating space for relaxation. Amenities include free Wi-Fi, a flat-screen TV, air conditioning, and a wardrobe. The private bathroom is equipped with a hot shower, fresh towels, and essential toiletries, making it a practical and cozy choice for family trips.', 'Free WiFi,Air Conditioning,Smart TV,Room Service,Breakfast Included,Hot Water', '2026-04-25 04:51:22'),
(17, 'Family Room 503', 'Family', 10000.00, '69ec48605bdac.jpeg', 'Available', 'Designed with families in mind, our Family Room provides ample space and comfort for a pleasant stay together. The room features multiple beds or a combination of a double bed and single beds, along with seating space for relaxation. Amenities include free Wi-Fi, a flat-screen TV, air conditioning, and a wardrobe. The private bathroom is equipped with a hot shower, fresh towels, and essential toiletries, making it a practical and cozy choice for family trips.', 'Free WiFi,Air Conditioning,Smart TV,Room Service,Breakfast Included,Hot Water', '2026-04-25 04:51:44'),
(18, 'Junior Suite Room', 'Suite', 15500.00, '69ec48f71f0aa.jpg', 'Available', 'Our Suite Room offers a premium stay experience with generous space and refined comfort. It features a separate living area and bedroom, providing privacy and relaxation. The suite is equipped with high-quality furnishings, a comfortable bed, free Wi-Fi, multiple flat-screen TVs, air conditioning, and a dedicated seating area. The spacious bathroom includes modern amenities, a hot shower, fresh towels, and premium toiletries. Ideal for guests seeking luxury, comfort, and a more exclusive stay.', 'Free WiFi,Air Conditioning,Smart TV,Room Service,Mini Bar,Breakfast Included,Swimming Pool Access,Hot Water,Coffee Machine,Spa', '2026-04-25 04:54:15');

-- --------------------------------------------------------

--
-- Table structure for table `support_requests`
--

CREATE TABLE `support_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Resolved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `created_at`) VALUES
(1, 'Eroj Bajracharya', 'eroj@gmail.com', '$2y$10$fwtz.BfqCO9yyeF0RdxH4ufrZPo8BVWc9zAQQL8zHDhG.rOWWu4ta', '9800000001', '2026-04-25 04:19:09'),
(2, 'Samyam Dhaubaji Shrestha', 'samyam@gmail.com', '$2y$10$DF13E2jumm3KH4vF4kRNMuSncWSVNypDkxzrE0Ox542Q1w/z4efPq', '9800000002', '2026-04-25 04:19:58'),
(3, 'Suryansh Bikram Shah', 'suryansh@gmail.com', '$2y$10$TW4ja0E4HBJjtt2zaJhH9eXrlmzqtjWkHjeBxTw4pUcA/EzcwZ5pK', '9800000003', '2026-04-25 04:21:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `support_requests`
--
ALTER TABLE `support_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD CONSTRAINT `support_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
