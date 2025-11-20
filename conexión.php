<?php
define('DB_HOST', 'localhost'); 
define('DB_USER', 'root'); 
define('DB_PASS', ''); 
define('DB_NAME', 'facebook-clone');

function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Dominios universitarios permitidos
function isUniversityEmail($email) {
    $universityDomains = [
        'uvg.edu.gt',
        
    ];
    
    $domain = substr(strrchr($email, "@"), 1);
    return in_array(strtolower($domain), $universityDomains);
}

// Iniciar sesión segura
function secureSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
?>