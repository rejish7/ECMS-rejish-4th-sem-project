CREATE DATABASE IF NOT EXISTS ecmss;
USE ecmss;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) UNIQUE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'counselor', 'student') DEFAULT 'student',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS counselors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    specialization VARCHAR(100),
    max_students INT DEFAULT 50,
    status ENUM('available', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) UNIQUE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    education_level VARCHAR(50),
    counselor_id INT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (counselor_id) REFERENCES counselors(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(50) UNIQUE,
    student_id INT,
    counselor_id INT,
    mode ENUM('In-Person', 'Video Call') DEFAULT 'In-Person',
    datetime DATETIME,
    status ENUM('scheduled', 'completed', 'cancelled', 'in-progress') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    FOREIGN KEY (counselor_id) REFERENCES counselors(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    name VARCHAR(255) NOT NULL,
    size VARCHAR(20),
    type VARCHAR(20),
    category ENUM('education', 'visa') DEFAULT 'education',
    assigned_by INT,
    assigned_at TIMESTAMP NULL,
    submitted_at TIMESTAMP NULL,
    status ENUM('assigned', 'pending', 'approved', 'rejected', 'resubmit') DEFAULT 'pending',
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL,
    remarks TEXT,
    file_path VARCHAR(500),
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inquiry_id VARCHAR(50) UNIQUE,
    student_id INT,
    country_of_interest VARCHAR(100),
    level_of_study VARCHAR(50),
    message TEXT,
    status ENUM('new', 'assigned', 'in-progress', 'closed') DEFAULT 'new',
    counselor_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    FOREIGN KEY (counselor_id) REFERENCES counselors(id) ON DELETE SET NULL
);

-- Insert default admin user (password: admin@123) - bcrypt hash via password_hash()
INSERT INTO users (user_id, name, email, password, role, status) VALUES
('USR-0001', 'Admin User', 'admin@ecms.edu', '$2y$10$HeTqyb0rcV2CqjYOsqFUiOas5EGnV9cEYkKvdZ9Vg4HeYp78KBSz.', 'admin', 'active')
ON DUPLICATE KEY UPDATE id=id;

-- Insert sample counselors
INSERT INTO counselors (name, email, specialization, max_students, status) VALUES
('Sarah Jenkins', 'sarah.j@ecms.edu', 'Undergraduate', 50, 'available'),
('Michael Chang', 'm.chang@ecms.edu', 'Postgraduate', 40, 'available'),
('Elena Patterson', 'elena.p@ecms.edu', 'Visa Counseling', 60, 'available')
ON DUPLICATE KEY UPDATE id=id;

-- Insert sample students
INSERT INTO students (student_id, name, email, education_level, counselor_id, status) VALUES
('STU-2023-089', 'Alex Lawson', 'alex@student.edu', 'Undergraduate', 1, 'active'),
('STU-2023-102', 'Maria Rodriguez', 'maria@student.edu', 'High School', 2, 'active'),
('STU-2023-145', 'James Duncan', 'james@student.edu', 'Postgraduate', 3, 'active'),
('STU-2023-210', 'Chloe Kim', 'chloe@student.edu', 'Undergraduate', 1, 'active')
ON DUPLICATE KEY UPDATE id=id;

-- Login accounts for seeded students & counselors (email is the same as their profile row).
-- These live in the users table so they can sign in. Default password: password123
INSERT INTO users (user_id, name, email, password, role, status) VALUES
('USR-STU-0001', 'Alex Lawson', 'alex@student.edu', '$2y$10$TCOfUM94l0ZktPfeo9Gtw.rDM1n96MYp36WU86oNHsHtTF7oCXcBW', 'student', 'active'),
('USR-STU-0002', 'Maria Rodriguez', 'maria@student.edu', '$2y$10$TCOfUM94l0ZktPfeo9Gtw.rDM1n96MYp36WU86oNHsHtTF7oCXcBW', 'student', 'active'),
('USR-STU-0003', 'James Duncan', 'james@student.edu', '$2y$10$TCOfUM94l0ZktPfeo9Gtw.rDM1n96MYp36WU86oNHsHtTF7oCXcBW', 'student', 'active'),
('USR-STU-0004', 'Chloe Kim', 'chloe@student.edu', '$2y$10$TCOfUM94l0ZktPfeo9Gtw.rDM1n96MYp36WU86oNHsHtTF7oCXcBW', 'student', 'active'),
('USR-CNS-0001', 'Sarah Jenkins', 'sarah.j@ecms.edu', '$2y$10$TCOfUM94l0ZktPfeo9Gtw.rDM1n96MYp36WU86oNHsHtTF7oCXcBW', 'counselor', 'active'),
('USR-CNS-0002', 'Michael Chang', 'm.chang@ecms.edu', '$2y$10$TCOfUM94l0ZktPfeo9Gtw.rDM1n96MYp36WU86oNHsHtTF7oCXcBW', 'counselor', 'active'),
('USR-CNS-0003', 'Elena Patterson', 'elena.p@ecms.edu', '$2y$10$TCOfUM94l0ZktPfeo9Gtw.rDM1n96MYp36WU86oNHsHtTF7oCXcBW', 'counselor', 'active')
ON DUPLICATE KEY UPDATE id=id;

-- Insert sample sessions
INSERT INTO sessions (session_id, student_id, counselor_id, mode, datetime, status) VALUES
('SES-001', 1, 1, 'In-Person', '2026-08-10 09:00:00', 'scheduled'),
('SES-002', 2, 2, 'Video Call', '2026-08-10 11:00:00', 'scheduled'),
('SES-003', 3, 3, 'In-Person', '2026-08-09 14:00:00', 'completed'),
('SES-004', 4, 1, 'Video Call', '2026-08-08 10:00:00', 'completed')
ON DUPLICATE KEY UPDATE id=id;

-- Insert sample inquiries
INSERT INTO inquiries (inquiry_id, student_id, country_of_interest, level_of_study, message, status, counselor_id, created_at) VALUES
('INQ-2023-089', 1, 'USA', 'Undergraduate', 'I want to know about scholarship opportunities in the US.', 'assigned', 1, '2023-10-24 10:00:00'),
('INQ-2023-092', 2, 'UK', 'Postgraduate', 'What are the admission requirements for UK universities?', 'new', NULL, '2023-10-26 14:00:00'),
('INQ-2023-045', 3, 'Canada', 'Postgraduate', 'Looking for information about work permits after graduation.', 'in-progress', 3, '2023-09-12 09:00:00'),
('INQ-2023-012', 4, 'Australia', 'Undergraduate', 'Need guidance on student visa application process.', 'closed', 1, '2023-07-05 11:00:00')
ON DUPLICATE KEY UPDATE id=id;

-- Insert sample documents
INSERT INTO documents (student_id, name, size, type, category, status, file_path, uploaded_by, created_at) VALUES
(1, 'Undergraduate Transcript', '245760', 'pdf', 'education', 'pending', '/uploads/documents/sample_transcript.pdf', 1, '2023-10-24 10:00:00'),
(2, 'Passport Copy', '1024000', 'pdf', 'visa', 'pending', '/uploads/documents/sample_passport.pdf', 1, '2023-10-24 11:00:00'),
(3, 'Bank Statement', '512000', 'pdf', 'visa', 'pending', '/uploads/documents/sample_bank.pdf', 1, '2023-10-23 09:00:00'),
(1, 'Recommendation Letter', '153600', 'pdf', 'education', 'approved', '/uploads/documents/sample_recom.pdf', 1, '2023-10-20 14:00:00'),
(4, 'Degree Certificate', '307200', 'pdf', 'education', 'resubmit', '/uploads/documents/sample_degree.pdf', 1, '2023-10-22 16:00:00')
ON DUPLICATE KEY UPDATE id=id;
