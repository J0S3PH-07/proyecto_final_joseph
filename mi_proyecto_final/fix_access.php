<?php
require 'includes/db.php';

echo "<style>body{font-family:sans-serif;padding:2rem;}</style>";
echo "<h1>🛠️ Reparación de Acceso</h1>";

// Herramienta de Reparación de Acceso
// Este script es de utilidad para resetear contraseñas masivamente en entorno de desarrollo.
// NO debe existir en producción.

require 'includes/db.php';

echo "<style>body{font-family:sans-serif;padding:2rem;}</style>";
echo "<h1>🛠️ Reparación de Acceso</h1>";

// 1. Forzar contraseñas a '1234'
// Generamos el hash para la contraseña '1234'
$pass_hash = password_hash('1234', PASSWORD_DEFAULT);

// ACTUALIZAR TODOS LOS USUARIOS A 1234
// Precaución: Esto cambia la contraseña de TODOS los usuarios registrados.
$sql_all = "UPDATE usuarios SET password='$pass_hash'";

if ($conexion->query($sql_all)) {
    echo "<p>✅ <strong>ÉXITO:</strong> Se han actualizado TODOS los usuarios a la contraseña '1234'.</p>";
    echo "<p>Usuarios afectados: " . $conexion->affected_rows . "</p>";
} else {
    echo "<p>❌ Error al actualizar: " . $conexion->error . "</p>";
}

// 2. Verificar lista de usuarios
// Mostramos los usuarios existentes para facilitar las pruebas
echo "<h3>Usuarios Disponibles:</h3><ul>";
$res = $conexion->query("SELECT nombre, email, rol FROM usuarios");
while ($u = $res->fetch_assoc()) {
    echo "<li><strong>" . $u['nombre'] . "</strong> (" . $u['email'] . ") - Rol: " . $u['rol'] . " - Clave: 1234</li>";
}
echo "</ul>";

// Formulario de prueba directa en el mismo script para verificar que el login funciona
echo "<h2>Prueba de Acceso Directa</h2>";
echo "<form method='POST'>
    Email: <input type='text' name='verificar_email' value='admin@biblioteca.com'><br>
    Pass: <input type='text' name='verificar_pass' value='1234'><br>
    <button>Probar Login</button>
</form>";

// Procesamiento del formulario de prueba
if (isset($_POST['verificar_email'])) {
    $e = $_POST['verificar_email'];
    $p = $_POST['verificar_pass'];
    
    $q = $conexion->query("SELECT * FROM usuarios WHERE email='$e'");
    if ($row = $q->fetch_assoc()) {
        echo "<p>Usuario encontrado: " . $row['email'] . "</p>";
        echo "<p>Hash en DB: " . $row['password'] . "</p>";
        // Verificación manual del hash en este script de prueba
        if (password_verify($p, $row['password'])) {
            echo "<h3 style='color:green'>✅ ¡CONTRASEÑA CORRECTA!</h3>";
            echo "<p>El problema no es la base de datos.</p>";
            echo "<a href='login.php'>Ir al Login Real</a>";
        } else {
            echo "<h3 style='color:red'>❌ CONTRASEÑA INCORRECTA</h3>";
            echo "<p>El hash no coincide.</p>";
        }
    } else {
        echo "<h3 style='color:red'>❌ USUARIO NO ENCONTRADO</h3>";
    }
}
?>
