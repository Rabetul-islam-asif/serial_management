-- Create phone_accounts table
CREATE TABLE IF NOT EXISTS `phone_accounts` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `phone` TEXT UNIQUE NOT NULL,
  `password_hash` TEXT NOT NULL,
  `is_active` INTEGER DEFAULT 1,
  `last_login_at` TEXT DEFAULT NULL,
  `created_at` TEXT NOT NULL,
  `updated_at` TEXT NOT NULL
);
