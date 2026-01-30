<?php
include 'includes/db.php';

// 1. Insertar Categorías
// Definimos las categorías básicas
$categorias = ['Novela', 'Ciencia', 'Historia'];
$cat_ids = [];

foreach ($categorias as $nombre) {
    // Usamos INSERT IGNORE o ON DUPLICATE para evitar errores si ya existen
    // Al mismo tiempo recuperamos el ID de la categoría (ya sea nueva o existente)
    $stmt = $conexion->prepare("INSERT INTO categorias (nombre) VALUES (?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), nombre=VALUES(nombre)");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $cat_ids[$nombre] = $stmt->insert_id;
    $stmt->close();
}

// 2. Insertar Usuarios
// Schema: id, nombre, email, password, rol
$usuarios = [
    // Administradores
    [
        'nombre' => 'Admin General',
        'email' => 'admin@biblioteca.com',
        'password' => 'admin123',
        'rol' => 'admin'
    ],
    // ... otros usuarios ...
    [
        'nombre' => 'María Administradora',
        'email' => 'maria@biblioteca.com',
        'password' => 'maria123',
        'rol' => 'admin'
    ],
    // Lectores
    [
        'nombre' => 'Juan Pérez',
        'email' => 'juan@biblioteca.com',
        'password' => 'juan123',
        'rol' => 'lector'
    ],
    [
        'nombre' => 'Ana García',
        'email' => 'ana@biblioteca.com',
        'password' => 'ana123',
        'rol' => 'lector'
    ],
    // ... más usuarios ...
];

foreach ($usuarios as $u) {
    // Hasheamos la contraseña antes de guardarla
    $hash = password_hash($u['password'], PASSWORD_DEFAULT);
    // Insertamos usuario o actualizamos si ya existe el email
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE password=VALUES(password), rol=VALUES(rol)");
    $stmt->bind_param("ssss", $u['nombre'], $u['email'], $hash, $u['rol']);
    $stmt->execute();
    $stmt->close();
}

// 3. Insertar Libros de ejemplo
// Se asocia cada libro a una categoría por nombre
$libros = [
    // Novela (5)
    ['titulo' => 'Cien años de soledad', 'autor' => 'Gabriel García Márquez', 'cat' => 'Novela'],
    ['titulo' => '1984', 'autor' => 'George Orwell', 'cat' => 'Novela'],
    // ...
    ['titulo' => 'El principito', 'autor' => 'Antoine de Saint-Exupéry', 'cat' => 'Novela'],
    ['titulo' => 'Don Quijote de la Mancha', 'autor' => 'Miguel de Cervantes', 'cat' => 'Novela'],
    ['titulo' => 'Orgullo y prejuicio', 'autor' => 'Jane Austen', 'cat' => 'Novela'],
    
    // Ciencia (5)
    ['titulo' => 'Breve historia del tiempo', 'autor' => 'Stephen Hawking', 'cat' => 'Ciencia'],
    ['titulo' => 'El gen egoísta', 'autor' => 'Richard Dawkins', 'cat' => 'Ciencia'],
    // ...
];

foreach ($libros as $l) {
    if (isset($cat_ids[$l['cat']])) {
        $cat_id = $cat_ids[$l['cat']];
        // Insertamos con stock por defecto 10
        $stmt = $conexion->prepare("INSERT INTO libros (titulo, autor, categoria_id, stock) VALUES (?, ?, ?, 10)");
        // No tenemos unique key obvia en titulo/autor, así que insertamos directamente.
        $stmt->bind_param("ssi", $l['titulo'], $l['autor'], $cat_id);
        $stmt->execute();
        $stmt->close();
    }
}

echo 'DATOS CARGADOS';
?>
