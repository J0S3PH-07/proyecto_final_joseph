<?php
session_start();
require 'includes/db.php';

// Seguridad: Verificar si el usuario es administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    // Si no es admin, redirigir al inicio o mostrar error
    header("Location: index.php");
    exit();
}

// Lógica de borrado
if (isset($_GET['id'])) {
    // Escapar el ID para seguridad básica (aunque sea int)
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    
    $sql = "DELETE FROM libros WHERE id = '$id'";
    
    if (mysqli_query($conexion, $sql)) {
        // Éxito: volver a la lista
        header("Location: libros_lista.php");
        exit();
    } else {
        // Fallo: redirigir a página de error
        $error_msg = "Error al eliminar el libro: " . mysqli_error($conexion);
        header("Location: error.php?msg=" . urlencode($error_msg));
        exit();
    }
} else {
    // Si no hay ID, volver a la lista
    header("Location: libros_lista.php");
    exit();
}
?>
