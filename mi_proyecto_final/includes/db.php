<?php

// Configuración de reporte de errores para MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Datos de conexión a la base de datos
$host = '127.0.0.1';
$usuario = 'root';
$password = '';
$basedatos = 'biblioteca';

try {
    // Primero conectamos sin base de datos para asegurar la entrada al servidor MySQL
    // Esto es útil si la base de datos aún no existe (ej. primera instalación)
    $conexion = mysqli_connect($host, $usuario, $password);
    
    // Intentamos seleccionar la base de datos específica 'biblioteca'
    mysqli_select_db($conexion, $basedatos);
    
    // Establecemos el juego de caracteres a utf8mb4 para soportar caracteres especiales
    mysqli_set_charset($conexion, 'utf8mb4');
} catch (Exception $e) {
    // Si ocurre un error (ej. la base de datos no existe),
    // seguimos conectados al servidor para permitir operaciones de creación de DB si fuera necesario
    // o simplemente para manejar el error de conexión inicial.
    $conexion = mysqli_connect($host, $usuario, $password);
}
?>