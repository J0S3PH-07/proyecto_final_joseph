<?php
include 'includes/db.php';
include 'header.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$mensaje = '';
$exito = false;

// Verificamos si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $libro_id = $_POST['libro_id'];
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_devolucion_esperada = $_POST['fecha_devolucion_esperada'];

    if ($usuario_id && $libro_id && $fecha_prestamo && $fecha_devolucion_esperada) {
        // 1. Verificar si hay stock disponible para el libro seleccionado
        $check_stock = mysqli_query($conexion, "SELECT stock FROM libros WHERE id = '$libro_id'");
        $libro_data = mysqli_fetch_assoc($check_stock);

        if ($libro_data && $libro_data['stock'] > 0) {
            // 2. Iniciar transacción para asegurar consistencia
            // Esto asegura que o se hacen ambos cambios (insertar préstamo y bajar stock) o ninguno
            mysqli_begin_transaction($conexion);

            try {
                // Insertar el registro del préstamo
                $stmt = mysqli_prepare($conexion, "INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo, fecha_devolucion_esperada) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "iiss", $usuario_id, $libro_id, $fecha_prestamo, $fecha_devolucion_esperada);
                mysqli_stmt_execute($stmt);

                // Disminuir Stock del libro en 1
                mysqli_query($conexion, "UPDATE libros SET stock = stock - 1 WHERE id = '$libro_id'");

                // Confirmar cambios (Commit) si todo salió bien
                mysqli_commit($conexion);
                
                $mensaje = "<div class='exito'>✅ Préstamo realizado con éxito. Stock actualizado.</div>";
                $exito = true;

            } catch (Exception $e) {
                // Si ocurre algún error, deshacemos todos los cambios pendientes (Rollback)
                mysqli_rollback($conexion); 
                $mensaje = "<div class='error'>Error al registrar: " . $e->getMessage() . "</div>";
            }
        } else {
             $mensaje = "<div class='error'>❌ No se puede realizar el préstamo: <strong>No hay stock disponible</strong> para este libro.</div>";
        }
    } else {
        $mensaje = "<div class='error'>Por favor completa todos los campos.</div>";
    }
}

$usuarios = mysqli_query($conexion, "SELECT id, nombre FROM usuarios ORDER BY nombre ASC");
$libros = mysqli_query($conexion, "SELECT id, titulo FROM libros ORDER BY titulo ASC");
?>

<div class="container">
    <div class="admin-panel">
        <h2>Registrar Nuevo Préstamo</h2>
        <?php echo $mensaje; ?>
        
        <form method="POST" action="">
            <label>Usuario:</label>
            <select name="usuario_id" required>
                <option value="">Selecciona un usuario</option>
                <?php while($u = mysqli_fetch_assoc($usuarios)): ?>
                    <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['nombre']); ?></option>
                <?php endwhile; ?>
            </select>

            <label>Libro:</label>
            <select name="libro_id" required>
                <option value="">Selecciona un libro</option>
                <?php while($l = mysqli_fetch_assoc($libros)): ?>
                    <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['titulo']); ?></option>
                <?php endwhile; ?>
            </select>

            <label>Fecha de Préstamo:</label>
            <input type="date" name="fecha_prestamo" value="<?php echo date('Y-m-d'); ?>" required>

            <label>Fecha de Devolución:</label>
            <input type="date" name="fecha_devolucion_esperada" value="<?php echo date('Y-m-d', strtotime('+15 days')); ?>" required>

            <button type="submit" class="btn btn-primary btn-block">Guardar Préstamo</button>
        </form>
        <p style="text-align:center;"><a href="prestamos_lista.php">Volver al listado</a></p>
    </div>
</div>

<?php if ($exito): ?>
<script>
    setTimeout(function() {
        window.location.href = 'prestamos_lista.php';
    }, 2000); // Redirige después de 2 segundos
</script>
<?php endif; ?>

<?php include 'footer.php'; ?>