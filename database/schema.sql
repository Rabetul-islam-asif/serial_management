-- Doctor Serial Cloud Database Schema

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `lab_requests`;
DROP TABLE IF EXISTS `prescription_items`;
DROP TABLE IF EXISTS `medicines`;
DROP TABLE IF EXISTS `prescriptions`;
DROP TABLE IF EXISTS `queue_settings`;
DROP TABLE IF EXISTS `queue_rules`;
DROP TABLE IF EXISTS `visits`;
DROP TABLE IF EXISTS `serials`;
DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `patients`;
DROP TABLE IF EXISTS `doctor_gallery`;
DROP TABLE IF EXISTS `doctor_services`;
DROP TABLE IF EXISTS `doctor_awards`;
DROP TABLE IF EXISTS `doctor_education`;
DROP TABLE IF EXISTS `chamber_schedules`;
DROP TABLE IF EXISTS `chambers`;
DROP TABLE IF EXISTS `doctor_profile`;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `rate_limits`;
DROP TABLE IF EXISTS `otp_codes`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- Users table
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'receptionist', 'patient') NOT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `is_active` BOOLEAN DEFAULT TRUE,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `last_login_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  INDEX `idx_users_email` (`email`),
  INDEX `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sessions table (database session storage option)
CREATE TABLE `sessions` (
  `id` VARCHAR(255) PRIMARY KEY,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- OTP Codes table
CREATE TABLE `otp_codes` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `phone` VARCHAR(20) NOT NULL,
  `code_hash` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `attempts` INT DEFAULT 0,
  `verified` BOOLEAN DEFAULT FALSE,
  `created_at` DATETIME NOT NULL,
  INDEX `idx_otp_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Rate Limits table
CREATE TABLE `rate_limits` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ip_address` VARCHAR(45) NOT NULL,
  `endpoint` VARCHAR(100) NOT NULL,
  `attempts` INT NOT NULL DEFAULT 1,
  `window_start` DATETIME NOT NULL,
  INDEX `idx_rate_limit` (`ip_address`, `endpoint`, `window_start`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Audit Logs table
CREATE TABLE `audit_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(255) NOT NULL,
  `entity_type` VARCHAR(100) NOT NULL,
  `entity_id` BIGINT UNSIGNED DEFAULT NULL,
  `old_values` JSON DEFAULT NULL,
  `new_values` JSON DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_audit_logs_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Doctor Profile table
CREATE TABLE `doctor_profile` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED UNIQUE NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `degree` VARCHAR(255) NOT NULL,
  `specialization` VARCHAR(255) NOT NULL,
  `bmdc_number` VARCHAR(50) NOT NULL,
  `hospital` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `experience_years` INT NOT NULL DEFAULT 0,
  `consultation_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `languages` JSON DEFAULT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `cover_image` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chambers table
CREATE TABLE `chambers` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `doctor_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `address` TEXT NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `google_map_url` TEXT DEFAULT NULL,
  `is_active` BOOLEAN DEFAULT TRUE,
  `sort_order` INT DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  FOREIGN KEY (`doctor_id`) REFERENCES `doctor_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chamber Schedules table
CREATE TABLE `chamber_schedules` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `chamber_id` BIGINT UNSIGNED NOT NULL,
  `day_of_week` TINYINT NOT NULL COMMENT '1=Sunday, 2=Monday, ..., 7=Saturday',
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `max_patients` INT NOT NULL DEFAULT 30,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `uk_chamber_day_time` (`chamber_id`, `day_of_week`, `start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Doctor Education table
CREATE TABLE `doctor_education` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `doctor_id` BIGINT UNSIGNED NOT NULL,
  `degree` VARCHAR(255) NOT NULL,
  `institution` VARCHAR(255) NOT NULL,
  `year` INT NOT NULL,
  `sort_order` INT DEFAULT 0,
  FOREIGN KEY (`doctor_id`) REFERENCES `doctor_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Doctor Awards table
CREATE TABLE `doctor_awards` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `doctor_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `year` INT NOT NULL,
  `description` TEXT DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  FOREIGN KEY (`doctor_id`) REFERENCES `doctor_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Doctor Services table
CREATE TABLE `doctor_services` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `doctor_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `icon` VARCHAR(100) DEFAULT 'activity',
  `sort_order` INT DEFAULT 0,
  FOREIGN KEY (`doctor_id`) REFERENCES `doctor_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Doctor Gallery table
CREATE TABLE `doctor_gallery` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `doctor_id` BIGINT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  FOREIGN KEY (`doctor_id`) REFERENCES `doctor_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Patients table
CREATE TABLE `patients` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) UNIQUE NOT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `age` INT NOT NULL,
  `gender` ENUM('male', 'female', 'other') NOT NULL,
  `blood_group` VARCHAR(10) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `medical_notes` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `deleted_at` DATETIME DEFAULT NULL,
  INDEX `idx_patients_phone` (`phone`),
  INDEX `idx_patients_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Appointments table
CREATE TABLE `appointments` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `patient_id` BIGINT UNSIGNED NOT NULL,
  `chamber_id` BIGINT UNSIGNED NOT NULL,
  `appointment_date` DATE NOT NULL,
  `appointment_type` ENUM('walkin', 'appointment', 'emergency', 'vip', 'followup') NOT NULL DEFAULT 'appointment',
  `status` ENUM('booked', 'confirmed', 'cancelled', 'completed', 'no_show') NOT NULL DEFAULT 'booked',
  `notes` TEXT DEFAULT NULL,
  `booked_by` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`booked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_app_date_chamber` (`appointment_date`, `chamber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Serials table (Live Queue)
CREATE TABLE `serials` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `appointment_id` BIGINT UNSIGNED DEFAULT NULL,
  `chamber_id` BIGINT UNSIGNED NOT NULL,
  `serial_date` DATE NOT NULL,
  `serial_number` INT NOT NULL,
  `queue_position` INT NOT NULL,
  `patient_type` ENUM('normal', 'report', 'vip', 'emergency', 'followup', 'senior', 'pregnant', 'custom') NOT NULL DEFAULT 'normal',
  `priority_level` INT DEFAULT 0,
  `status` ENUM('waiting', 'called', 'in_consultation', 'hold', 'skipped', 'missed', 'completed', 'cancelled', 'no_show') NOT NULL DEFAULT 'waiting',
  `called_at` DATETIME DEFAULT NULL,
  `started_at` DATETIME DEFAULT NULL,
  `completed_at` DATETIME DEFAULT NULL,
  `hold_reason` VARCHAR(255) DEFAULT NULL,
  `missed_rejoin_after` INT DEFAULT NULL,
  `original_position` INT DEFAULT NULL,
  `is_rejoined` BOOLEAN DEFAULT FALSE,
  `token_number` VARCHAR(20) NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE,
  INDEX `idx_serial_date_pos` (`serial_date`, `chamber_id`, `queue_position`),
  INDEX `idx_serial_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Queue Settings table
CREATE TABLE `queue_settings` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `chamber_id` BIGINT UNSIGNED NOT NULL,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` JSON NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `uk_chamber_key` (`chamber_id`, `setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Queue Rules table (Ordering rules)
CREATE TABLE `queue_rules` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `chamber_id` BIGINT UNSIGNED NOT NULL,
  `rule_name` VARCHAR(100) NOT NULL,
  `rule_order` INT NOT NULL,
  `patient_type` ENUM('normal', 'report', 'vip', 'emergency', 'followup', 'senior', 'pregnant', 'custom') NOT NULL,
  `batch_size` INT NOT NULL DEFAULT 1,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Visits table
CREATE TABLE `visits` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `patient_id` BIGINT UNSIGNED NOT NULL,
  `serial_id` BIGINT UNSIGNED DEFAULT NULL,
  `chamber_id` BIGINT UNSIGNED NOT NULL,
  `visit_date` DATE NOT NULL,
  `chief_complaint` TEXT DEFAULT NULL,
  `diagnosis` TEXT DEFAULT NULL,
  `doctor_notes` TEXT DEFAULT NULL,
  `next_visit_date` DATE DEFAULT NULL,
  `status` ENUM('in_progress', 'completed') NOT NULL DEFAULT 'in_progress',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`serial_id`) REFERENCES `serials` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON DELETE CASCADE,
  INDEX `idx_visit_date` (`visit_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Prescriptions table
CREATE TABLE `prescriptions` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `visit_id` BIGINT UNSIGNED NOT NULL,
  `patient_id` BIGINT UNSIGNED NOT NULL,
  `prescription_number` VARCHAR(50) UNIQUE NOT NULL,
  `rx_date` DATE NOT NULL,
  `special_instructions` TEXT DEFAULT NULL,
  `pdf_path` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  INDEX `idx_presc_patient` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Medicines table
CREATE TABLE `medicines` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `generic_name` VARCHAR(255) DEFAULT NULL,
  `type` ENUM('tablet', 'capsule', 'syrup', 'injection', 'cream', 'drops', 'inhaler', 'other') NOT NULL DEFAULT 'tablet',
  `strength` VARCHAR(50) DEFAULT NULL,
  `manufacturer` VARCHAR(255) DEFAULT NULL,
  `is_favorite` BOOLEAN DEFAULT FALSE,
  `usage_count` INT DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  INDEX `idx_medicine_name` (`name`),
  INDEX `idx_medicine_fav` (`is_favorite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Prescription Items table
CREATE TABLE `prescription_items` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `prescription_id` BIGINT UNSIGNED NOT NULL,
  `medicine_id` BIGINT UNSIGNED NOT NULL,
  `dosage` VARCHAR(100) NOT NULL COMMENT 'e.g. 1+0+1',
  `frequency` VARCHAR(100) NOT NULL COMMENT 'e.g. After meal',
  `duration` VARCHAR(100) NOT NULL COMMENT 'e.g. 7 days',
  `instructions` TEXT DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Lab Requests table
CREATE TABLE `lab_requests` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `visit_id` BIGINT UNSIGNED NOT NULL,
  `test_name` VARCHAR(255) NOT NULL,
  `test_category` VARCHAR(100) DEFAULT NULL,
  `instructions` TEXT DEFAULT NULL,
  `status` ENUM('requested', 'completed', 'reviewed') NOT NULL DEFAULT 'requested',
  `result_notes` TEXT DEFAULT NULL,
  `result_file` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invoices table
CREATE TABLE `invoices` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `visit_id` BIGINT UNSIGNED DEFAULT NULL,
  `patient_id` BIGINT UNSIGNED NOT NULL,
  `invoice_number` VARCHAR(50) UNIQUE NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `discount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` ENUM('pending', 'paid', 'partial', 'refunded') NOT NULL DEFAULT 'pending',
  `payment_method` VARCHAR(50) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  INDEX `idx_invoice_patient` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payments table
CREATE TABLE `payments` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `invoice_id` BIGINT UNSIGNED NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `transaction_ref` VARCHAR(100) DEFAULT NULL,
  `paid_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL,
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications table
CREATE TABLE `notifications` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `type` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `data` JSON DEFAULT NULL,
  `is_read` BOOLEAN DEFAULT FALSE,
  `read_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  INDEX `idx_notif_user_read` (`user_id`, `is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings table
CREATE TABLE `settings` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) UNIQUE NOT NULL,
  `value` TEXT DEFAULT NULL,
  `group_name` VARCHAR(100) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
