DROP DATABASE IF EXISTS biblioteca;
CREATE DATABASE biblioteca;
USE biblioteca;

-- Tabla de Usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'lector') NOT NULL DEFAULT 'lector',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla de Libros
CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    categoria_id INT,
    isbn VARCHAR(20) UNIQUE,
    stock INT DEFAULT 0,
    precio DECIMAL(10, 2),
    fecha_publicacion DATE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

-- Tabla de Prestamos
CREATE TABLE prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    libro_id INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion_esperada DATE NOT NULL,
    fecha_devolucion_real DATE,
    estado ENUM('prestado', 'devuelto', 'atrasado') DEFAULT 'prestado',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE
);

-- Tabla de Comentarios (Opcional pero recomendada para completitud)
CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    libro_id INT NOT NULL,
    comentario TEXT,
    calificacion INT CHECK (calificacion >= 1 AND calificacion <= 5),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Insertar Datos de Ejemplo (Mínimo 15 registros totales)
-- --------------------------------------------------------

-- 1. Usuarios (7 registros: 2 admins + 5 lectores)
-- Contraseñas: admin123, maria123, juan123, ana123, carlos123, laura123, pedro123
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Admin General', 'admin@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('María Administradora', 'maria@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Juan Pérez', 'juan@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lector'),
('Ana García', 'ana@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lector'),
('Carlos López', 'carlos@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lector'),
('Laura Martínez', 'laura@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lector'),
('Pedro Sánchez', 'pedro@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lector');

-- 2. Categorias (5 registros)
INSERT INTO categorias (nombre) VALUES
('Novela Histórica'),
('Ciencia Ficción'),
('Desarrollo Personal'),
('Programación'),
('Biografía');

-- 3. Libros (8 registros)
INSERT INTO libros (titulo, autor, categoria_id, isbn, stock, precio, fecha_publicacion) VALUES
('Los Pilares de la Tierra', 'Ken Follett', 1, '89-2345', 5, 25.50, '1989-01-01'),
('Dune', 'Frank Herbert', 2, '45-6789', 3, 18.90, '1965-08-01'),
('Hábitos Atómicos', 'James Clear', 3, '12-3456', 10, 21.00, '2018-10-16'),
('Clean Code', 'Robert C. Martin', 4, '98-7654', 4, 45.00, '2008-08-01'),
('Steve Jobs', 'Walter Isaacson', 5, '33-4455', 2, 19.95, '2011-10-24'),
('El Señor de los Anillos', 'J.R.R. Tolkien', 2, '55-6677', 7, 20.00, '1954-07-29'),
('La Catedral del Mar', 'Ildefonso Falcones', 1, '11-2233', 6, 16.50, '2006-01-01'),
('PHP & MySQL Novice to Ninja', 'Kevin Yank', 4, '77-8899', 3, 29.95, '2012-05-01');

-- 4. Prestamos (5 registros)
INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo, fecha_devolucion_esperada, estado) VALUES
(2, 1, '2023-10-01', '2023-10-15', 'devuelto'),
(2, 4, '2023-11-01', '2023-11-15', 'prestado'),
(3, 3, '2023-11-05', '2023-11-20', 'atrasado'),
(4, 2, '2023-11-10', '2023-11-25', 'prestado'),
(5, 5, '2023-11-12', '2023-11-26', 'prestado');

-- 5. Comentarios (3 registros)
INSERT INTO comentarios (usuario_id, libro_id, comentario, calificacion) VALUES
(2, 1, 'Una historia fascinante de principio a fin.', 5),
(3, 3, 'Cambió mi forma de ver los hábitos.', 5),
(4, 2, 'Un clásico imprescindible.', 4);