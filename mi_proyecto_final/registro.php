<?php
include 'includes/db.php';
// Aseguramos que estamos en la base de datos correcta
mysqli_select_db($conexion, 'biblioteca');

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']); 
    $email = mysqli_real_escape_string($conexion, $_POST['email']); 
    $password = $_POST['password']; // No escapamos aquí porque usaremos hash

    if (empty($nombre) || empty($email) || empty($password)) {
        header('Location: error.php?msg=Campos obligatorios');
        exit;
    }
    $password_encriptada = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO usuarios (nombre, email, password, rol) VALUES ('$nombre', '$email', '$password_encriptada', 'lector')";
    if (mysqli_query($conexion, $query)) {
        header('Location: login.php?msg=Registro exitoso');
        exit;
    } else {
        header('Location: error.php?msg=Error en la base de datos');
        exit;
    }
}

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
