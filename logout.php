<?php
session_start();

// Verifica si hay una sesión activa antes de destruirla
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

// Redirige al usuario a la página de inicio
header("Location: index.php");
exit();
?>
