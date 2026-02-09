-- Introducción a Microservicios: Arquitectura y Contenedores
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO usuarios (nombre, email) VALUES ('Usuario Microservicios', 'correo@correo.com');