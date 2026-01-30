<?php
include 'includes/db.php';
include 'header.php';

// Seguridad: Verificamos si hay un usuario logueado en la sesión
// Si no existe la variable de sesión 'user_id', redirigimos al login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Consultamos la lista de libros uniendo la tabla libros con categorias
// Usamos LEFT JOIN para obtener el nombre de la categoría en lugar de su ID
// Si un libro no tiene categoría, seguirá apareciendo con categoría NULL (o tratada en la vista)
$sql = "SELECT l.id, l.titulo, l.autor, l.stock, l.isbn, l.precio, l.fecha_publicacion, c.nombre AS categoria_nombre 
        FROM libros l 
        LEFT JOIN categorias c ON l.categoria_id = c.id";

// Ejecutamos la consulta contra la base de datos
$result = mysqli_query($conexion, $sql);
?>

<div class="container">
    <h1>Listado de Libros</h1>
    
    <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Título</th>
                <th>Autor</th>
                <th>Categoría</th>
                <th>ISBN</th>
                <th>Precio</th>
                <th>Publicado</th>
                <th>Stock</th>
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                    <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($libro = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                    <td><?php echo htmlspecialchars($libro['categoria_nombre'] ?? 'Sin categoría'); ?></td>
                    <td><span style="display: inline-block; white-space: nowrap; font-family: monospace; font-size: 0.95em; background: #f8f9fa; padding: 4px 8px; border: 1px solid #e9ecef; border-radius: 4px; color: #495057;"><?php echo htmlspecialchars($libro['isbn'] ?? 'N/A'); ?></span></td>
                    <td><span style="display: inline-block; white-space: nowrap; font-weight: bold; color: #2c3e50;"><?php echo number_format($libro['precio'], 2); ?> €</span></td>
                    <td><?php echo $libro['fecha_publicacion'] ? date('d/m/Y', strtotime($libro['fecha_publicacion'])) : 'Desconocida'; ?></td>
                    <td>
                        <span style="
                            font-weight: bold; 
                            color: <?php echo $libro['stock'] > 0 ? '#2ecc71' : '#e74c3c'; ?>;
                        ">
                            <?php echo htmlspecialchars($libro['stock']); ?>
                        </span>
                    </td>
                    
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <td style="text-align: center;">
                            <a href="libros_borrar.php?id=<?php echo $libro['id']; ?>" 
                               onclick="return confirm('¿Estás seguro de eliminar este libro?');"
                               style="color: red; text-decoration: none; font-weight: bold;">
                                Eliminar
                            </a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <?php if (mysqli_num_rows($result) === 0): ?>
        <p>No se encontraron libros.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
