-- Create database tables for Lima Wheels
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(255) NOT NULL,
  `apellido_completo` varchar(255) NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo_electronico` (`correo_electronico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample user data
INSERT INTO `usuarios` (`nombre_completo`, `apellido_completo`, `correo_electronico`, `contrasena`) VALUES 
('Juan Pérez', 'Gómez', 'juan.perez@example.com', 'contraseña123'),
('Johan', 'Pena', 'johan.pena@usil.pe', '123'),
('Brayan', 'Campos', 'brayan.campos@usil.pe', '123'),
('Key', 'Espejo', 'asd@asd.asd', '123');

CREATE TABLE `logs_acceso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `exitoso` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `logs_acceso_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;