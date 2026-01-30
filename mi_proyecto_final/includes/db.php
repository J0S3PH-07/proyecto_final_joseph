<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$host = '127.0.0.1';
$usuario = 'root';
$password = '';
$basedatos = 'biblioteca';

try {
    // Primero conectamos sin base de datos para asegurar la entrada
    $conexion = mysqli_connect($host, $usuario, $password);
    // Intentamos seleccionar la base de datos
    mysqli_select_db($conexion, $basedatos);
    mysqli_set_charset($conexion, 'utf8mb4');
} catch (Exception $e) {
    // Si la base de datos no existe, seguimos conectados al servidor para crearla
    $conexion = mysqli_connect($host, $usuario, $password);
}
?>