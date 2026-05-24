CREATE DATABASE IF NOT EXISTS salon_cms;
USE salon_cms;

CREATE TABLE IF NOT EXISTS `about` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `company_name` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `mission` TEXT NOT NULL,
    `vision` TEXT NOT NULL,
    `image_url` TEXT NOT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed the initial singular operational row required for CMS workflow management
INSERT INTO `about` (`id`, `company_name`, `description`, `mission`, `vision`, `image_url`) 
VALUES (1, 'Elegance Salon', 'Premium aesthetic treatments and bespoke hair styling operations in Karachi.', 'To elevate human beauty standards via highly sanitized, elite styling methods.', 'To leverage dynamic technical architecture to become Pakistan\'s premier salon franchise.', 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=800&q=80')
ON DUPLICATE KEY UPDATE id=id;
