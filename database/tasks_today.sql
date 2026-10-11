-- Import ONCE into a new, empty MySQL/MariaDB database.
-- Dates are relative to the import date in Philippine time.
SET time_zone = '+08:00';

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'pending',
  task_date DATE NOT NULL,
  created_at DATETIME NOT NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  created_at DATETIME NOT NULL
);

INSERT INTO tasks (title, status, task_date, created_at) VALUES
('Review project requirements', 'completed', DATE_SUB(CURDATE(), INTERVAL 1 DAY), NOW()),
('Design the database schema', 'completed', DATE_SUB(CURDATE(), INTERVAL 1 DAY), NOW()),
('Prepare the interface layout', 'completed', DATE_SUB(CURDATE(), INTERVAL 1 DAY), NOW()),
('Build the daily task dashboard', 'completed', CURDATE(), NOW()),
('Check task filtering by date', 'in progress', CURDATE(), NOW()),
('Review the demo user profile', 'pending', CURDATE(), NOW()),
('Complete project documentation', 'pending', CURDATE(), NOW()),
('Upload source files to GitHub', 'pending', DATE_ADD(CURDATE(), INTERVAL 1 DAY), NOW()),
('Verify the hosted application', 'pending', DATE_ADD(CURDATE(), INTERVAL 1 DAY), NOW());

INSERT INTO users (username, full_name, email, created_at) VALUES
('demo_user', 'Alex Santos', 'alex.santos@example.com', NOW());
