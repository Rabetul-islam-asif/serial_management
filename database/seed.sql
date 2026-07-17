-- Seed file for Doctor Serial Cloud

-- Hashed password for 'password' is: $2y$10$8.e9X72K0rW73c52/Rz1oO2v6/jA6k.Ure3qB7P5W1hXn9YvT5g7K
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password_hash`, `role`, `avatar`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Dr. Sarah Rahman', 'admin@doctorserial.cloud', '01712345678', '$2y$10$8.e9X72K0rW73c52/Rz1oO2v6/jA6k.Ure3qB7P5W1hXn9YvT5g7K', 'admin', 'sarah-avatar.png', 1, NOW(), NOW()),
(2, 'Rahim Uddin', 'receptionist@doctorserial.cloud', '01812345678', '$2y$10$8.e9X72K0rW73c52/Rz1oO2v6/jA6k.Ure3qB7P5W1hXn9YvT5g7K', 'receptionist', 'rahim-avatar.png', 1, NOW(), NOW());

-- Insert Doctor Profile
INSERT INTO `doctor_profile` (`id`, `user_id`, `name`, `degree`, `specialization`, `bmdc_number`, `hospital`, `bio`, `experience_years`, `consultation_fee`, `languages`, `photo`, `cover_image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dr. Sarah Rahman', 'MBBS, FCPS (Medicine), MD (Cardiology)', 'Cardiology & Internal Medicine Specialist', 'A-45892', 'National Heart Foundation & Research Institute', 'Dr. Sarah Rahman is a highly experienced Cardiologist with a demonstrated history of working in top-tier healthcare institutions. She specializes in interventional cardiology, heart failure management, and preventive medicine.', 12, 1000.00, '["Bengali", "English"]', 'sarah-photo.jpg', 'sarah-cover.jpg', NOW(), NOW());

-- Insert Chamber
INSERT INTO `chambers` (`id`, `doctor_id`, `name`, `address`, `phone`, `google_map_url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Metro Heart Chamber', 'House-42, Road-11, Dhanmondi, Dhaka - 1209', '01912345678', 'https://maps.google.com/?q=Dhanmondi+Dhaka', 1, 1, NOW(), NOW());

-- Insert Chamber Schedule
-- 1=Sunday, 2=Monday, 3=Tuesday, 4=Wednesday, 5=Thursday, 6=Friday, 7=Saturday
INSERT INTO `chamber_schedules` (`id`, `chamber_id`, `day_of_week`, `start_time`, `end_time`, `max_patients`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '17:00:00', '21:00:00', 30, 1, NOW(), NOW()), -- Sunday
(2, 1, 2, '17:00:00', '21:00:00', 30, 1, NOW(), NOW()), -- Monday
(3, 1, 3, '17:00:00', '21:00:00', 30, 1, NOW(), NOW()), -- Tuesday
(4, 1, 4, '17:00:00', '21:00:00', 30, 1, NOW(), NOW()), -- Wednesday
(5, 1, 5, '17:00:00', '21:00:00', 30, 1, NOW(), NOW()); -- Thursday

-- Insert default queue settings for Dhanmondi Chamber
INSERT INTO `queue_settings` (`chamber_id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'ratio_rules', '{"normal": 3, "report": 2, "vip": 1}', 'Ratio mapping of normal vs report vs vip patients', NOW(), NOW()),
(1, 'rejoin_gap', '3', 'Number of patients to bypass before a missed patient rejoins the queue', NOW(), NOW()),
(1, 'avg_consultation_time', '10', 'Estimated consultation duration in minutes per patient', NOW(), NOW());

-- Insert Queue Rules
INSERT INTO `queue_rules` (`chamber_id`, `rule_name`, `rule_order`, `patient_type`, `batch_size`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Normal Batch', 1, 'normal', 3, 1, NOW(), NOW()),
(1, 'Report Batch', 2, 'report', 2, 1, NOW(), NOW()),
(1, 'VIP Batch', 3, 'vip', 1, 1, NOW(), NOW());

-- Insert default System Settings
INSERT INTO `settings` (`key`, `value`, `group_name`, `created_at`, `updated_at`) VALUES
('clinic_name', 'Metro Healthcare Clinic', 'general', NOW(), NOW()),
('sms_provider', 'sandbox', 'sms', NOW(), NOW()),
('currency', 'BDT', 'general', NOW(), NOW());

-- Seed some standard Medicines
INSERT INTO `medicines` (`name`, `generic_name`, `type`, `strength`, `manufacturer`, `is_favorite`, `usage_count`, `created_at`, `updated_at`) VALUES
('Napa Extend', 'Paracetamol', 'tablet', '665mg', 'Beximco Pharmaceuticals Ltd.', 1, 24, NOW(), NOW()),
('Ace Plus', 'Paracetamol + Caffeine', 'tablet', '500mg+65mg', 'Square Pharmaceuticals PLC', 1, 18, NOW(), NOW()),
('Sergel', 'Esomeprazole', 'capsule', '20mg', 'Healthcare Pharmaceuticals Ltd.', 1, 45, NOW(), NOW()),
('Seclo', 'Omeprazole', 'capsule', '20mg', 'Square Pharmaceuticals PLC', 0, 12, NOW(), NOW()),
('Alatrol', 'Cetirizine Hydrochloride', 'tablet', '10mg', 'Square Pharmaceuticals PLC', 1, 15, NOW(), NOW()),
('Fexo', 'Fexofenadine', 'tablet', '120mg', 'Square Pharmaceuticals PLC', 0, 8, NOW(), NOW()),
('Azithrocin', 'Azithromycin', 'tablet', '500mg', 'Beximco Pharmaceuticals Ltd.', 1, 30, NOW(), NOW()),
('Monas 10', 'Montelukast', 'tablet', '10mg', 'Acme Laboratories Ltd.', 1, 22, NOW(), NOW()),
('Xorel', 'Rivaroxaban', 'tablet', '10mg', 'Incepta Pharmaceuticals Ltd.', 0, 3, NOW(), NOW()),
('Concor 5', 'Bisoprolol Fumarate', 'tablet', '5mg', 'Merck / Square', 1, 14, NOW(), NOW());

-- Doctor Education
INSERT INTO `doctor_education` (`doctor_id`, `degree`, `institution`, `year`, `sort_order`) VALUES
(1, 'MBBS', 'Dhaka Medical College', 2010, 1),
(1, 'FCPS (Medicine)', 'Bangladesh College of Physicians & Surgeons', 2015, 2),
(1, 'MD (Cardiology)', 'National Heart Foundation & Research Institute', 2018, 3);

-- Doctor Services
INSERT INTO `doctor_services` (`doctor_id`, `name`, `description`, `icon`, `sort_order`) VALUES
(1, 'Cardiac Consultation', 'Comprehensive heart health assessment and treatment planning', 'heart', 1),
(1, 'ECG & Stress Test', 'Electrocardiogram and exercise stress testing', 'activity', 2),
(1, 'Echocardiography', 'Ultrasound imaging of the heart structure and function', 'monitor', 3),
(1, 'Hypertension Management', 'Blood pressure monitoring and long-term management plans', 'trending-up', 4),
(1, 'Diabetes Care', 'Comprehensive diabetic care and metabolic assessments', 'thermometer', 5),
(1, 'Preventive Checkup', 'Full-body health screening and preventive medicine', 'shield', 6);

-- Doctor Awards
INSERT INTO `doctor_awards` (`doctor_id`, `title`, `year`, `description`, `sort_order`) VALUES
(1, 'Best Young Cardiologist Award', 2020, 'Awarded by the Bangladesh Cardiac Society for outstanding clinical contribution.', 1),
(1, 'Gold Medal in FCPS Examination', 2015, 'Highest marks in the FCPS Medicine final examination.', 2),
(1, 'Research Excellence Award', 2022, 'For published research in interventional cardiology in international journals.', 3);
