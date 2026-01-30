<?php
session_start();
require 'includes/db.php';

// Seguridad: Verificar si el usuario tiene permiso de administrador
// Solo los administradores pueden borrar libros
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    // Si no es admin, redirigir al inicio para proteger la acción
    header("Location: index.php");
    exit();
}

// Lógica de borrado: Verificamos si recibimos un ID válido por GET
if (isset($_GET['id'])) {
    // Escapar el ID para evitar inyección SQL (aunque debería ser numérico)
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    
    // Construimos la consulta DELETE para eliminar el libro específico
    $sql = "DELETE FROM libros WHERE id = '$id'";
    
    // Ejecutamos la consulta
    if (mysqli_query($conexion, $sql)) {
        // Éxito: volvemos a mostrar la lista actualizada
        header("Location: libros_lista.php");
        exit();
    } else {
        // Fallo: redirigir a página de error con el mensaje detallado
        // Esto puede pasar si hay restricciones de clave foránea (ej. préstamos activos)
        $error_msg = "Error al eliminar el libro: " . mysqli_error($conexion);
        header("Location: error.php?msg=" . urlencode($error_msg));
        exit();
    }
} else {
    // Si no se proporciona ID, simplemente volvemos a la lista sin hacer nada
    header("Location: libros_lista.php");
    exit();
}
?>
