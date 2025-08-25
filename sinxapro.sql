-- =============================================
-- SINXAPRO Database Setup untuk XAMPP
-- =============================================

-- 1. Buat database
CREATE DATABASE sinxapro_db;
USE sinxapro_db;

-- 2. Tabel users untuk authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    profile_picture VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- 3. Tabel uploaded_files untuk menyimpan info file yang diupload
CREATE TABLE uploaded_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT NOT NULL,
    file_type VARCHAR(100) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description TEXT,
    is_public BOOLEAN DEFAULT FALSE,
    download_count INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. Tabel sessions untuk mengelola login sessions
CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 5. Insert sample users (password: "admin123" dan "user123" - hashed)
INSERT INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@sinxapro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin'),
('user1', 'user1@sinxapro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User Satu', 'user'),
('demo', 'demo@sinxapro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Demo User', 'user');

-- 6. Create indexes untuk performance
CREATE INDEX idx_username ON users(username);
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_user_files ON uploaded_files(user_id);
CREATE INDEX idx_session_token ON user_sessions(session_token);
CREATE INDEX idx_session_expires ON user_sessions(expires_at);

-- 7. Sample uploaded files
INSERT INTO uploaded_files (user_id, original_name, file_name, file_path, file_size, file_type, description, is_public) VALUES
(2, 'document.pdf', 'doc_20241201_001.pdf', 'uploads/doc_20241201_001.pdf', 1024000, 'application/pdf', 'Dokumen penting', TRUE),
(2, 'image.jpg', 'img_20241201_001.jpg', 'uploads/img_20241201_001.jpg', 512000, 'image/jpeg', 'Gambar profil', FALSE);

-- =============================================
-- Konfigurasi XAMPP:
-- 1. Jalankan XAMPP Control Panel
-- 2. Start Apache dan MySQL
-- 3. Buka phpMyAdmin (http://localhost/phpmyadmin)
-- 4. Import script SQL ini
-- 5. Buat folder 'uploads' di root project dengan permission 777
-- =============================================