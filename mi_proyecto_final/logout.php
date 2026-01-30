<?php
// Iniciamos la sesión para poder acceder a ella y destruirla
session_start();

// Borramos todas las variables de la sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, eliminamos también la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruimos la sesión
session_destroy();

// Redirigimos al usuario a la página de inicio o al login
header("Location: index.php");
exit;
?>