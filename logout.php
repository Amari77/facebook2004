<?php
// logout.php
require_once 'conexión.php';
secureSession();

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la cookie de sesión
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destruir la sesión
session_destroy();

// Redirigir al login
header('Location: index.php');
exit();
?>