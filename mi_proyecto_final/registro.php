<?php
include 'includes/db.php';
// Aseguramos que estamos trabajando con la base de datos correcta
mysqli_select_db($conexion, 'biblioteca');

// Verificamos si se ha enviado el formulario de registro
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    // Recogemos y limpiamos los datos de entrada para evitar inyección SQL básica
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']); 
    $email = mysqli_real_escape_string($conexion, $_POST['email']); 
    $password = $_POST['password']; // No escapamos aquí porque usaremos hash inmediatamente después

    // Validamos que los campos obligatorios no estén vacíos
    if (empty($nombre) || empty($email) || empty($password)) {
        header('Location: error.php?msg=Campos obligatorios');
        exit;
    }
    
    // Encriptamos la contraseña usando un algoritmo seguro (Bcrypt por defecto)
    $password_encriptada = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertamos el nuevo usuario en la base de datos con el rol por defecto 'lector'
    $query = "INSERT INTO usuarios (nombre, email, password, rol) VALUES ('$nombre', '$email', '$password_encriptada', 'lector')";
    
    if (mysqli_query($conexion, $query)) {
        // Si el registro es exitoso, redirigimos al login
        header('Location: login.php?msg=Registro exitoso');
        exit;
    } else {
        // Si falla, mostramos un error genérico (podría ser email duplicado, etc.)
        header('Location: error.php?msg=Error en la base de datos');
        exit;
    }
}

// Incluimos el header para mantener la consistencia visual
include 'header.php'; 
?>

<h2>Registro de Nuevo Usuario</h2>
<form action="registro.php" method="POST">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Registrar">
</form>

<?php include 'footer.php'; ?>
