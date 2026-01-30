<?php
session_start();
// Incluimos el archivo de conexión a la base de datos
require 'includes/db.php';

// Asegurar conexión a la base de datos correcta
mysqli_select_db($conexion, 'biblioteca');

// 2. Depuración de conexión
if (!$conexion) {
    die("Error crítico: No conectado a la DB");
}

$error = '';

// Verificamos si el formulario ha sido enviado vía POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizamos y recogemos los datos del formulario
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Validamos que los campos no estén vacíos
    if (!empty($email) && !empty($password)) {
        // Preparamos la consulta SQL para buscar al usuario por email
        // Usamos sentencias preparadas para prevenir inyección SQL
        $stmt = $conexion->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Verificamos si se encontró exactamente un usuario
        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();
            
            // Verificamos que la contraseña introducida coincida con el hash almacenado
            if (password_verify($password, $usuario['password'])) {
                // Login correcto: Guardamos datos del usuario en la sesión
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol'] = $usuario['rol'];
                
                // Redirigir al usuario al dashboard principal
                header("Location: index.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no registrado.";
        }
    } else {
        $error = "Completa todos los campos.";
    }
}

include 'header.php'; // Incluimos el header para que se vea el menú y el diseño
?>

<div class="dashboard">
    <div class="admin-panel"> <h1>Acceso a la Biblioteca</h1>
        <p>Introduce tus credenciales para gestionar tus libros.</p>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" style="box-shadow: none; border: none; padding: 0;">
            <div style="text-align: left;">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
        </form>

        <p style="margin-top: 20px; font-size: 0.9em;">
            ¿No tienes cuenta? <a href="registro.php" style="color: #007bff; font-weight: bold;">Regístrate aquí</a>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>