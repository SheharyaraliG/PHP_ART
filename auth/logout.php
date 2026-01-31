<?php
// ========================================
// auth/logout.php - CERRAR SESIÓN
// ========================================

// Iniciar sesión para acceder a los datos de sesión
session_start();

// Destruir la sesión actual (eliminar todas las variables de sesión)
// Esto borra usuario_id, usuario_nombre, usuario_rol y cualquier otro dato
session_destroy();

// Redirigir al usuario a la página de login
header("Location: login.php");

// Detener la ejecución del script
exit;
?>