<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Biblioteca</title>
    <link rel="stylesheet" href="css/style.css?v=20260130v3">
    <!-- Modern Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.php">📚 Biblioteca</a>
            </div>
            <nav>
                <ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li>Hola <?php echo htmlspecialchars($_SESSION['nombre']); ?> (<?php echo $_SESSION['rol']; ?>)</li>
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="libros_lista.php">Ver Libros</a></li>
                        <?php if ($_SESSION['rol'] == 'admin'): ?>
                            <li><a href="libros_nuevo.php">Añadir Libro</a></li>
                            <li><a href="prestamos_lista.php">Gestión Préstamos</a></li>
                        <?php else: ?>
                            <li><a href="prestamos_lista.php">Mis Préstamos</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="registro.php">Registro</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">