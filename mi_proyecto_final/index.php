<?php 
// 1. Siempre iniciamos sesión primero
session_start();
include 'includes/db.php';
include 'header.php'; 
?>

<div class="dashboard">
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="admin-panel">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
            <p>Has accedido con el rol de: <strong><?php echo ucfirst($_SESSION['rol']); ?></strong></p>
            <hr>
            
            <?php if ($_SESSION['rol'] == 'admin'): ?>
                <h2>Panel de Administración</h2>
                <p>Desde aquí puedes gestionar el inventario de libros y usuarios.</p>
                <a href="libros_lista.php" class="btn btn-primary">Gestionar Libros</a>
            <?php else: ?>
                <h2>Panel de Lector</h2>
                <p>Explora nuestro catálogo y gestiona tus préstamos.</p>
                <a href="libros_lista.php" class="btn btn-success">Ver Catálogo</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="admin-panel">
            <h1>Bienvenido a la Biblioteca Municipal</h1>
            <p>Para gestionar libros o ver el catálogo completo, por favor identifícate.</p>
            <a href="login.php" class="btn btn-primary">Ir al Login</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>