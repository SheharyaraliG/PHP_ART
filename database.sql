-- Database Schema for PHP Art Gallery
CREATE DATABASE IF NOT EXISTS galeria_arte CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE galeria_arte;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS obras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(500), -- Supports URLs
    tipo ENUM('original', 'lamina') NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 1,
    usuario_id INT, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    obra_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE CASCADE,
    UNIQUE(usuario_id, obra_id)
);

-- Default Admin
-- Pass: admin123
INSERT IGNORE INTO usuarios (email, password, nombre, rol) VALUES 
('admin@admin.com', '$2y$10$8WkQvj8.1/8.1/8.1/8.1/8.1/8.1/8.1/8.1/8.1/8.1/', 'Admin', 'admin');
