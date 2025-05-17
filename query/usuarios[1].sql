-- Crear base de datos y seleccionarla
-- CREATE DATABASE lima_wheels_bd;
USE lima_wheels_bd;

-- Crear tabla de usuarios
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_completo VARCHAR(255) NOT NULL,
  apellido_completo VARCHAR(255) NOT NULL,
  correo_electronico VARCHAR(255) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos de ejemplo en usuarios
INSERT INTO usuarios (nombre_completo, apellido_completo, correo_electronico, contrasena) VALUES 
('Juan Pérez', 'Gómez', 'juan.perez@example.com', 'contraseña123'),
('Johan', 'Pena', 'johan.pena@usil.pe', '123'),
('Brayan', 'Campos', 'brayan.campos@usil.pe', '123'),
('Key', 'Espejo', 'asd@asd.asd', '123');

-- Crear tabla de logs de acceso
CREATE TABLE logs_acceso (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ip_address VARCHAR(45),
  exitoso TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Crear tabla de automóviles
CREATE TABLE automoviles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  marca VARCHAR(50) NOT NULL,
  modelo VARCHAR(50) NOT NULL,
  imagen TEXT NOT NULL,
  precio DECIMAL(10,2) NOT NULL,
  descripcion TEXT NOT NULL,
  condicion VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Consultas de verificación
-- SELECT * FROM usuarios;
-- SELECT * FROM logs_acceso;
-- SELECT * FROM automoviles;

-- DROP DATABASE IF EXISTS lima_wheels_bd;