-- Migration: Update documents table for student-wise document review system
-- Run this if you already have the database created

USE ecmss;

-- Add student_id column if not exists
SET @dbname = DATABASE();
SET @tablename = 'documents';
SET @columnname = 'student_id';
SELECT COUNT(*) INTO @column_exists FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname;
SET @sql = IF(@column_exists = 0, 'ALTER TABLE documents ADD COLUMN student_id INT AFTER id', 'SELECT "Column student_id already exists"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add status column if not exists
SET @columnname = 'status';
SELECT COUNT(*) INTO @column_exists FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname;
SET @sql = IF(@column_exists = 0, "ALTER TABLE documents ADD COLUMN status ENUM('pending', 'approved', 'rejected', 'resubmit') DEFAULT 'pending' AFTER category", 'SELECT "Column status already exists"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add reviewed_by column if not exists
SET @columnname = 'reviewed_by';
SELECT COUNT(*) INTO @column_exists FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname;
SET @sql = IF(@column_exists = 0, 'ALTER TABLE documents ADD COLUMN reviewed_by INT AFTER status', 'SELECT "Column reviewed_by already exists"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add reviewed_at column if not exists
SET @columnname = 'reviewed_at';
SELECT COUNT(*) INTO @column_exists FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname;
SET @sql = IF(@column_exists = 0, 'ALTER TABLE documents ADD COLUMN reviewed_at TIMESTAMP NULL AFTER reviewed_by', 'SELECT "Column reviewed_at already exists"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add remarks column if not exists
SET @columnname = 'remarks';
SELECT COUNT(*) INTO @column_exists FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname;
SET @sql = IF(@column_exists = 0, 'ALTER TABLE documents ADD COLUMN remarks TEXT AFTER reviewed_at', 'SELECT "Column remarks already exists"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Modify category column to enum if it's not already
ALTER TABLE documents MODIFY COLUMN category ENUM('education', 'visa') DEFAULT 'education';

-- Add foreign keys (ignore errors if they already exist)
-- Note: Foreign keys may already exist from schema.sql

-- Insert sample documents (only if table is empty)
SET @doc_count = (SELECT COUNT(*) FROM documents);
SET @sql = IF(@doc_count = 0,
    "INSERT INTO documents (student_id, name, size, type, category, status, file_path, uploaded_by, created_at) VALUES (2, 'Undergraduate Transcript', '245760', 'pdf', 'education', 'pending', '/uploads/documents/sample_transcript.pdf', 1, NOW()), (3, 'Passport Copy', '1024000', 'pdf', 'visa', 'pending', '/uploads/documents/sample_passport.pdf', 1, NOW()), (5, 'Bank Statement', '512000', 'pdf', 'visa', 'pending', '/uploads/documents/sample_bank.pdf', 1, NOW())",
    'SELECT "Documents table already has data, skipping sample insert"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
