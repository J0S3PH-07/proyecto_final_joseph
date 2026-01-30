<?php
include 'header.php';
require 'includes/db.php';

// Seguridad: Solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $autor = mysqli_real_escape_string($conexion, $_POST['autor']);
    $isbn = mysqli_real_escape_string($conexion, $_POST['isbn']);
    $stock = (int)$_POST['stock'];
    $precio = (float)$_POST['precio'];
    $fecha_publicacion = mysqli_real_escape_string($conexion, $_POST['fecha_publicacion']);
    $categoria_id = (int)$_POST['categoria_id'];

    if (!empty($titulo) && !empty($autor)) {
        // Insertar libro con todos los campos
        $sql = "INSERT INTO libros (titulo, autor, isbn, stock, precio, fecha_publicacion, categoria_id) 
                VALUES ('$titulo', '$autor', '$isbn', $stock, $precio, '$fecha_publicacion', $categoria_id)";
        
        if (mysqli_query($conexion, $sql)) {
            header("Location: libros_lista.php");
            exit();
        } else {
            $error = "Error al guardar el libro: " . mysqli_error($conexion);
        }
    } else {
        $error = "Por favor completa todos los campos obligatorios.";
    }
}
?>

<div class="container">
    <div class="admin-panel" style="max-width: 800px; margin: 0 auto;">
        <h2>Añadir Nuevo Libro</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="libros_nuevo.php" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div style="grid-column: 1 / -1;">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required style="width: 100%;">
            </div>

            <div style="grid-column: 1 / -1;">
                <label for="autor">Autor:</label>
                <input type="text" id="autor" name="autor" required style="width: 100%;">
            </div>

            <div>
                <label for="isbn">ISBN (Generado):</label>
                <input type="text" id="isbn" name="isbn" readonly style="width: 100%; background-color: #fafafa; color: #555; border: 1px solid #ddd; cursor: default; font-family: monospace; letter-spacing: 1px;">
            </div>

            <div>
                <label for="categoria_id">Categoría:</label>
                <select name="categoria_id" id="categoria_id" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <?php
                    // Cargar categorías dinámicamente
                    $cats = mysqli_query($conexion, "SELECT id, nombre FROM categorias");
                    while($c = mysqli_fetch_assoc($cats)): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label for="precio">Precio (€):</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" required style="width: 100%;">
            </div>

            <div>
                <label for="stock">Stock (Cantidad):</label>
                <input type="number" id="stock" name="stock" min="0" value="1" required style="width: 100%;">
            </div>

            <div style="grid-column: 1 / -1;">
                <label for="fecha_publicacion">Fecha de Publicación:</label>
                <input type="date" id="fecha_publicacion" name="fecha_publicacion" style="width: 100%;">
            </div>

            <div style="grid-column: 1 / -1; margin-top: 30px; display: flex; gap: 20px;">
                <input type="submit" value="Guardar Libro" class="btn btn-primary" style="flex: 1; padding: 12px;">
                <a href="libros_lista.php" class="btn btn-secondary" style="flex: 1; text-align: center; padding: 12px; display: flex; align-items: center; justify-content: center;">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
function generarISBN() {
    // Generar ISBN con formato xx-xxxx
    let parte1 = Math.floor(Math.random() * 90 + 10); // 2 dígitos
    let parte2 = Math.floor(Math.random() * 9000 + 1000); // 4 dígitos
    
    let isbn = parte1 + "-" + parte2;
    
    // Asignar al campo
    document.getElementById('isbn').value = isbn;
}

// Generar uno automáticamente al cargar la página
window.onload = function() {
    generarISBN();
    // Establecer fecha de hoy por defecto
    document.getElementById('fecha_publicacion').valueAsDate = new Date();
};
</script>

<?php include 'footer.php'; ?>
