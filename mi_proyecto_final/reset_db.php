<?php
/**
 * REINICIAR SISTEMA
 * Este script elimina todo y vuelve a crear la base de datos limpia con usuarios correctos.
 * Úsalo una sola vez para arreglar tu base de datos.
 */

$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $conn = new mysqli($host, $user, $pass);
    
    // 1. Borrar y crear DB
    $conn->query("DROP DATABASE IF EXISTS biblioteca");
    $conn->query("CREATE DATABASE biblioteca");
    $conn->select_db("biblioteca");
    
    // 2. Tablas
    $sql = "
    CREATE TABLE usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        rol ENUM('admin', 'lector') NOT NULL DEFAULT 'lector'
    );
    
    CREATE TABLE categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50) NOT NULL UNIQUE
    );
    
    CREATE TABLE libros (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(200) NOT NULL,
        autor VARCHAR(100) NOT NULL,
        categoria_id INT,
        stock INT DEFAULT 0,
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    );
    
    CREATE TABLE prestamos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        libro_id INT NOT NULL,
        fecha_prestamo DATE NOT NULL,
        fecha_devolucion_esperada DATE NOT NULL,
        fecha_devolucion_real DATE,
        estado ENUM('prestado', 'devuelto', 'atrasado') DEFAULT 'prestado',
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
        FOREIGN KEY (libro_id) REFERENCES libros(id)
    );
    ";
    
    if ($conn->multi_query($sql)) {
        while ($conn->next_result()) {;} // Vaciar buffer
    }

    // 2.1 Insertar Categorías
    $conn->query("INSERT INTO categorias (nombre) VALUES ('Novela Histórica'), ('Ciencia Ficción'), ('Desarrollo Personal'), ('Programación'), ('Biografía')");

    // 2.2 Insertar Libros (Con nuevo formato ISBN xx-xxxx)
    $sql_libros = "INSERT INTO libros (titulo, autor, categoria_id, isbn, stock, precio, fecha_publicacion) VALUES 
    ('Los Pilares de la Tierra', 'Ken Follett', 1, '89-2345', 5, 25.50, '1989-01-01'),
    ('Dune', 'Frank Herbert', 2, '45-6789', 3, 18.90, '1965-08-01'),
    ('Hábitos Atómicos', 'James Clear', 3, '12-3456', 10, 21.00, '2018-10-16'),
    ('Clean Code', 'Robert C. Martin', 4, '98-7654', 4, 45.00, '2008-08-01'),
    ('Steve Jobs', 'Walter Isaacson', 5, '33-4455', 2, 19.95, '2011-10-24'),
    ('El Señor de los Anillos', 'J.R.R. Tolkien', 2, '55-6677', 7, 20.00, '1954-07-29'),
    ('La Catedral del Mar', 'Ildefonso Falcones', 1, '11-2233', 6, 16.50, '2006-01-01'),
    ('PHP & MySQL Novice to Ninja', 'Kevin Yank', 4, '77-8899', 3, 29.95, '2012-05-01')";
    
    $conn->query($sql_libros);

    // 3. Insertar Usuarios (Con contraseñas simples 1234 para evitar errores de copy-paste)
    // Para simplificar al máximo: TODOS los usuarios tendrán contraseña '1234'
    $clave = password_hash('1234', PASSWORD_DEFAULT);
    
    $usuarios = [
        ['Admin', 'admin@biblioteca.com', $clave, 'admin'],
        ['Lector', 'lector@biblioteca.com', $clave, 'lector']
    ];
    
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    foreach ($usuarios as $u) {
        $stmt->bind_param("ssss", $u[0], $u[1], $u[2], $u[3]);
        $stmt->execute();
    }
    
    echo "<h1>✅ SISTEMA REINICIADO</h1>";
    echo "<p>Base de datos creada desde cero.</p>";
    echo "<h3>Tus nuevas credenciales (Simplificadas):</h3>";
    echo "<ul>";
    echo "<li>Admin: <strong>admin@biblioteca.com</strong> / <strong>1234</strong></li>";
    echo "<li>Lector: <strong>lector@biblioteca.com</strong> / <strong>1234</strong></li>";
    echo "</ul>";
    echo "<br><a href='login.php'>👉 IR AL LOGIN</a>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
