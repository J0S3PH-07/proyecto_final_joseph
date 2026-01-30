<?php
include 'includes/db.php';
session_start();

// Verificar si es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Verificar que se recibió un ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Eliminar el préstamo
    $stmt = mysqli_prepare($conexion, "DELETE FROM prestamos WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Redirigir con mensaje de éxito
        header('Location: prestamos_lista.php?mensaje=eliminado');
    } else {
        // Redirigir con mensaje de error
        header('Location: prestamos_lista.php?error=1');
    }
    mysqli_stmt_close($stmt);
} else {
    header('Location: prestamos_lista.php');
}
exit;
?>
