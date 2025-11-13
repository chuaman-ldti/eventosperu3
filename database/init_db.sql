-- init_db.sql
CREATE DATABASE IF NOT EXISTS eventos_peru CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eventos_peru;

-- Tabla clientes (para asociar eventos)
DROP TABLE IF EXISTS clientes;
CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  telefono VARCHAR(20),
  email VARCHAR(100)
);

-- Tabla proveedores
DROP TABLE IF EXISTS proveedores;
CREATE TABLE proveedores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  categoria VARCHAR(80) NOT NULL,
  distrito VARCHAR(80) NOT NULL,
  precio DECIMAL(10,2) DEFAULT 0,
  reputacion DECIMAL(3,1) DEFAULT 0,
  experiencia INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla eventos
DROP TABLE IF EXISTS eventos;
CREATE TABLE eventos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  fecha DATE NOT NULL,
  lugar VARCHAR(160),
  estado ENUM('Pendiente','En Progreso','Completado','Cancelado') DEFAULT 'Pendiente',
  cliente_id INT,
  proveedor_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL,
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE SET NULL
);

-- Tabla resenas (reseñas)
DROP TABLE IF EXISTS resenas;
CREATE TABLE resenas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  evento_id INT,
  proveedor_id INT,
  calificacion INT,
  comentario TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE,
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE CASCADE
);

-- Datos de ejemplo
INSERT INTO clientes (nombre, telefono, email) VALUES
('Juan Pérez','987654321','juan@example.com'),
('María López','912345678','maria@example.com');

INSERT INTO proveedores (nombre, categoria, distrito, precio, reputacion, experiencia) VALUES
('Fiestas Pepe','Cumpleaños','Miraflores',800.00,4.6,5),
('Eventos Luz','Matrimonio','Surco',3500.00,4.8,8),
('Animaciones KIDS','Cumpleaños','Todos',600.00,4.4,4);

INSERT INTO eventos (titulo, fecha, lugar, estado, cliente_id, proveedor_id) VALUES
('Cumple de Sofía', DATE_ADD(CURDATE(), INTERVAL 14 DAY), 'Parque Kennedy', 'Pendiente', 1, 1),
('Boda Ana & Luis', DATE_ADD(CURDATE(), INTERVAL 45 DAY), 'Hotel Westin', 'Pendiente', 2, NULL);
