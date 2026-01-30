<?php
include 'includes/db.php';

// 1. Insertar Categorías
$categorias = ['Novela', 'Ciencia', 'Historia'];
$cat_ids = [];

foreach ($categorias as $nombre) {
    // Usamos INSERT IGNORE o ON DUPLICATE para evitar errores si ya existen
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
    [
        'nombre' => 'Carlos López',
        'email' => 'carlos@biblioteca.com',
        'password' => 'carlos123',
        'rol' => 'lector'
    ],
    [
        'nombre' => 'Laura Martínez',
        'email' => 'laura@biblioteca.com',
        'password' => 'laura123',
        'rol' => 'lector'
    ],
    [
        'nombre' => 'Pedro Sánchez',
        'email' => 'pedro@biblioteca.com',
        'password' => 'pedro123',
        'rol' => 'lector'
    ]
];

foreach ($usuarios as $u) {
    $hash = password_hash($u['password'], PASSWORD_DEFAULT);
    // Nota: rol en instalar.php es varchar(20), no enum, pero valores coinciden
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE password=VALUES(password), rol=VALUES(rol)");
    $stmt->bind_param("ssss", $u['nombre'], $u['email'], $hash, $u['rol']);
    $stmt->execute();
    $stmt->close();
}

// 3. Insertar Libros (15 libros)
// Schema: id, titulo, autor, categoria_id, stock
// Eliminados: isbn, precio, fecha_publicacion

$libros = [
    // Novela (5)
    ['titulo' => 'Cien años de soledad', 'autor' => 'Gabriel García Márquez', 'cat' => 'Novela'],
    ['titulo' => '1984', 'autor' => 'George Orwell', 'cat' => 'Novela'],
    ['titulo' => 'El principito', 'autor' => 'Antoine de Saint-Exupéry', 'cat' => 'Novela'],
    ['titulo' => 'Don Quijote de la Mancha', 'autor' => 'Miguel de Cervantes', 'cat' => 'Novela'],
    ['titulo' => 'Orgullo y prejuicio', 'autor' => 'Jane Austen', 'cat' => 'Novela'],
    
    // Ciencia (5)
    ['titulo' => 'Breve historia del tiempo', 'autor' => 'Stephen Hawking', 'cat' => 'Ciencia'],
    ['titulo' => 'El gen egoísta', 'autor' => 'Richard Dawkins', 'cat' => 'Ciencia'],
    ['titulo' => 'Cosmos', 'autor' => 'Carl Sagan', 'cat' => 'Ciencia'],
    ['titulo' => 'Sapiens', 'autor' => 'Yuval Noah Harari', 'cat' => 'Ciencia'],
    ['titulo' => 'La estructura de las revoluciones científicas', 'autor' => 'Thomas S. Kuhn', 'cat' => 'Ciencia'],
    
    // Historia (5)
    ['titulo' => 'Los cañones de agosto', 'autor' => 'Barbara W. Tuchman', 'cat' => 'Historia'],
    ['titulo' => 'Homenaje a Cataluña', 'autor' => 'George Orwell', 'cat' => 'Historia'],
    ['titulo' => 'Guns, Germs, and Steel', 'autor' => 'Jared Diamond', 'cat' => 'Historia'],
    ['titulo' => 'SPQR', 'autor' => 'Mary Beard', 'cat' => 'Historia'],
    ['titulo' => 'El diario de Ana Frank', 'autor' => 'Ana Frank', 'cat' => 'Historia']
];

foreach ($libros as $l) {
    if (isset($cat_ids[$l['cat']])) {
        $cat_id = $cat_ids[$l['cat']];
        // Insertamos con stock por defecto 10
        $stmt = $conexion->prepare("INSERT INTO libros (titulo, autor, categoria_id, stock) VALUES (?, ?, ?, 10)");
        // No tenemos unique key obvia en titulo/autor, así que insertamos directamente.
        // Si se ejecuta múltiples veces duplicará libros, pero "cargar_datos" suele ser una operación inicial.
        // Podríamos hacer un check previo, pero simplificaremos asumiendo carga limpia o aceptando duplicados por ahora.
        $stmt->bind_param("ssi", $l['titulo'], $l['autor'], $cat_id);
        $stmt->execute();
        $stmt->close();
    }
}

echo 'DATOS CARGADOS';
?>
