<?php
// Iniciamos la sesión para poder acceder a ella y destruirla
session_start();

// Borramos todas las variables de la sesión (array vacío)
$_SESSION = array();

// Si se desea destruir la sesión completamente, eliminamos también la cookie de sesión del navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruimos la sesión en el servidor
session_destroy();

// Redirigimos al usuario a la página de inicio (que probablemente le mostrará el login)
header("Location: index.php");
exit;
?>