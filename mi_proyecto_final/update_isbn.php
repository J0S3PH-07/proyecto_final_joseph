<?php
require 'includes/db.php';

// Script de utilidad para actualizar formatos de ISBN masivamente
// NO es parte del flujo normal de la aplicación.

echo "<h1>🔄 Actualizando Formato ISBN a xx-xxxx</h1>";

// Obtenemos todos los libros actuales
$sql = "SELECT id FROM libros";
$result = $conexion->query($sql);

if ($result) {
    echo "<ul>";
    // Iteramos sobre cada libro
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        // Generar nuevo formato aleatorio xx-xxxx
        $p1 = rand(10, 99);
        $p2 = rand(1000, 9999);
        $new_isbn = "$p1-$p2";
        
        // Actualizamos el ISBN para este ID específico
        $update = "UPDATE libros SET isbn='$new_isbn' WHERE id=$id";
        if ($conexion->query($update)) {
            echo "<li>Libro ID $id actualizado a: <strong>$new_isbn</strong></li>";
        }
    }
    echo "</ul>";
    echo "<h2 style='color:green'>✅ ¡Todos los libros actualizados!</h2>";
} else {
    echo "Error consultando libros: " . $conexion->error;
}
?>
<br>
<a href="libros_lista.php">Volver a la Lista de Libros</a>
