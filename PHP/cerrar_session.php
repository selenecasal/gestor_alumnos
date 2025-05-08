<?php
session_start();
if (isset($_SESSION['nombreusuario'])) {
    // Destruir todas las variables de sesión
    $_SESSION = array();

    // Si se desea destruir la sesión completamente, también se debe borrar la cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destruye la sesión
    session_destroy();

    // Redirige a la página principal
    header("Location: ../index.php");
    exit(); // Asegúrate de salir del script
} else {
    // Si no hay sesión activa, redirige a la página principal
    header("Location: ../index.php");
    exit();
}
?>
<?php
/*
session_start(); // Inicia la sesión
if(isset($_SESSION['nombreusuario'])) {
    session_destroy(); // Destruye la sesión
    header("Location: ../index.php"); // Redirige a la página principal
    exit(); // Asegúrate de salir del script
}
?>*/