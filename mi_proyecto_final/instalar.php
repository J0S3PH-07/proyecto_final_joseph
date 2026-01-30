<?php
include 'includes/db.php';

// SQL para la estructura de la base de datos
// Usamos IF NOT EXISTS para evitar errores si ya existen las tablas
$sql = "CREATE DATABASE IF NOT EXISTS biblioteca;
USE biblioteca;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(20) DEFAULT 'usuario'
);

-- Tabla de categorías para libros
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

-- Tabla de libros
CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    categoria_id INT,
    stock INT DEFAULT 0,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Tabla de préstamos (relación usuarios <-> libros)
CREATE TABLE IF NOT EXISTS prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    libro_id INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion DATE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (libro_id) REFERENCES libros(id)
);";

// Ejecutamos múltiples consultas a la vez (multi_query)
if (mysqli_multi_query($conexion, $sql)) {
    // Es necesario recorrer los resultados de multi_query para liberar el buffer
    // y evitar errores en futuras consultas
    do {
        if ($result = mysqli_store_result($conexion)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conexion));
    echo 'LISTO: Tablas creadas correctamente.';
} else {
    echo 'Error al crear tablas: ' . mysqli_error($conexion);
}
?>
