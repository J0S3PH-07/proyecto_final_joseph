<?php
require 'includes/db.php';

echo "<h1>🔄 Actualizando Formato ISBN a xx-xxxx</h1>";

$sql = "SELECT id FROM libros";
$result = $conexion->query($sql);

if ($result) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        // Generar nuevo formaot xx-xxxx
        $p1 = rand(10, 99);
        $p2 = rand(1000, 9999);
        $new_isbn = "$p1-$p2";
        
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
