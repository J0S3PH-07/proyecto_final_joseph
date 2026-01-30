<?php
include 'includes/db.php';
include 'header.php';

// Seguridad: Solo usuarios registrados pueden ver esto
// Si no hay sesión, se redirige al login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 1. Construir consulta base
// Obtenemos los datos del préstamo uniendo tablas para obtener nombre de usuario y título de libro
// p = tabla prestamos, u = tabla usuarios, l = tabla libros
$query = "SELECT p.id, u.nombre as usuario, l.titulo as libro, p.fecha_prestamo, p.fecha_devolucion_esperada 
          FROM prestamos p 
          JOIN usuarios u ON p.usuario_id = u.id 
          JOIN libros l ON p.libro_id = l.id";

// 2. Si NO es admin, filtrar por su ID
// Los usuarios normales solo ven sus propios préstamos
if ($_SESSION['rol'] != 'admin') {
    $user_id = $_SESSION['user_id'];
    $query .= " WHERE p.usuario_id = '$user_id'";
}

// Ordenamos por fecha de préstamo descendente (más recientes primero)
$query .= " ORDER BY p.fecha_prestamo DESC";

// Ejecutamos la consulta
$resultado = mysqli_query($conexion, $query);
?>

<div class="container">
    <div class="admin-panel" style="max-width: 100%; text-align: left;">
        <h2><?php echo ($_SESSION['rol'] == 'admin') ? 'Gestión de Préstamos' : 'Mis Préstamos Activos'; ?></h2>
        
        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'eliminado'): ?>
            <div class="exito">Préstamo eliminado correctamente.</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error">Error al eliminar el préstamo.</div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'): ?>
            <div style="margin-bottom: 20px;">
                <a href="prestamos_nuevo.php" class="btn btn-primary">➕ Registrar Nuevo Préstamo</a>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Libro</th>
                    <th>Fecha de Préstamo</th>
                    <th>Fecha de Devolución</th>
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($fila['usuario']); ?></strong></td>
                        <td><?php echo htmlspecialchars($fila['libro']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($fila['fecha_prestamo'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($fila['fecha_devolucion_esperada'])); ?></td>
                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'): ?>
                            <td style="text-align: center;">
                                <a href="prestamos_borrar.php?id=<?php echo $fila['id']; ?>" 
                                   onclick="return confirm('¿Estás seguro de eliminar este préstamo?');"
                                   class="btn btn-danger" 
                                   style="padding: 5px 10px; font-size: 0.85em;">
                                    Eliminar
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
                
                <?php if (mysqli_num_rows($resultado) == 0): ?>
                    <tr>
                        <td colspan="<?php echo (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin') ? '5' : '4'; ?>" style="text-align: center;">No hay préstamos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
