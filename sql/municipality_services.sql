-- إنشاء قاعدة البيانات
CREATE DATABASE municipality_services;

-- استخدام قاعدة البيانات
USE municipality_services;

-- إنشاء الجداول وإدخال البيانات
CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `request_details` text NOT NULL,
  `requester_name` varchar(255) NOT NULL,
  `office_number` varchar(50) NOT NULL,
  `status` enum('Pending','In Progress','Completed','Canceled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `service_requests` (`id`, `user_id`, `service_type`, `request_details`, `requester_name`, `office_number`, `status`, `created_at`) VALUES
(1, 1, 'Facility Maintenance', 'Electrical, Furniture Repair', 'Anhar Mohammed', '106', 'Pending', '2025-11-24 17:02:24');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(100) NOT NULL,
  `office_number` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `department`, `office_number`, `created_at`) VALUES
(1, 'Anhar', 'anhar@gmail.com', '123456', 'IT', '108', '2025-11-23 18:04:22');

-- الفهارس
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

-- تعديل الجداول لتفعيل AUTO_INCREMENT
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- إضافة قيود العلاقة بين الجداول
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
