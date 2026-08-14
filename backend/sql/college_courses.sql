-- College and Course Catalog tables

USE ecmss;

CREATE TABLE IF NOT EXISTS colleges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    country VARCHAR(100) NOT NULL,
    city VARCHAR(100),
    website VARCHAR(255),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(50),
    logo VARCHAR(500),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    college_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(20) NOT NULL,
    level ENUM('bachelor', 'master', 'diploma', 'phd') NOT NULL,
    duration VARCHAR(50) NOT NULL,
    description TEXT,
    requirements TEXT,
    tuition_fee DECIMAL(10, 2),
    currency VARCHAR(10) DEFAULT 'USD',
    status ENUM('active', 'inactive', 'review') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (college_id) REFERENCES colleges(id) ON DELETE CASCADE
);

-- Seed sample data
INSERT INTO colleges (name, code, country, city, website, contact_email, contact_phone, status) VALUES
('London University', 'LU', 'United Kingdom', 'London', 'https://london.ac.uk', 'admissions@lu.ac.uk', '+44 20 1234 5678', 'active'),
('Melbourne Tech', 'MU', 'Australia', 'Melbourne', 'https://melbtech.edu.au', 'int@melbtech.edu', '+61 3 9876 5432', 'active'),
('Toronto Uni', 'TU', 'Canada', 'Toronto', 'https://utoronto.ca', 'global@utoronto.ca', '+1 416 555 1234', 'active'),
('Stanford University', 'SU', 'United States', 'Stanford', 'https://stanford.edu', 'admissions@stanford.edu', '+1 650 555 9999', 'active'),
('University of Sydney', 'US', 'Australia', 'Sydney', 'https://sydney.edu.au', 'info@sydney.edu.au', '+61 2 5555 1234', 'active');

INSERT INTO courses (college_id, name, code, level, duration, description, requirements, tuition_fee, currency, status) VALUES
(1, 'BSc Computer Science', 'CS-101', 'bachelor', '3 Years', 'Comprehensive computer science program covering algorithms, data structures, and software engineering.', 'IELTS 6.5, A-Levels (ABB)', 25000.00, 'GBP', 'active'),
(1, 'MBA International Business', 'BUS-500', 'master', '1.5 Years', 'Advanced MBA program specializing in global business strategy and international trade.', 'IELTS 7.0, 3 Yrs Exp', 35000.00, 'GBP', 'active'),
(2, 'Adv. Diploma Graphic Design', 'DES-202', 'diploma', '2 Years', 'Creative diploma program focusing on digital design, branding, and visual communication.', 'IELTS 6.0, Portfolio', 18000.00, 'AUD', 'review'),
(3, 'BEng Mechanical Engineering', 'ENG-101', 'bachelor', '4 Years', 'Engineering program covering mechanics, thermodynamics, and manufacturing systems.', 'IELTS 6.5, Physics & Math', 28000.00, 'CAD', 'active'),
(3, 'MSc Data Science', 'DS-501', 'master', '2 Years', 'Advanced data science program with machine learning and big data analytics.', 'IELTS 7.0, Bachelor in CS/Stats', 32000.00, 'CAD', 'active'),
(4, 'PhD Computer Science', 'CS-PhD', 'phd', '4-5 Years', 'Research-intensive PhD program in artificial intelligence and machine learning.', 'IELTS 7.5, Masters in CS', 45000.00, 'USD', 'active'),
(5, 'Bachelor of Nursing', 'NUR-101', 'bachelor', '3 Years', 'Clinical nursing program with hands-on hospital placements.', 'IELTS 7.0, Science Background', 30000.00, 'AUD', 'active'),
(2, 'Master of IT', 'IT-501', 'master', '2 Years', 'Postgraduate IT program covering cloud computing and cybersecurity.', 'IELTS 6.5, Bachelor in IT', 22000.00, 'AUD', 'active');
