<?php
// ========================================
// favoritos/accion.php - AÑADIR/QUITAR FAVORITOS
// ========================================

// Incluir conexión a base de datos
include '../config/database.php';

// Iniciar sesión para acceder a $_SESSION
session_start();

// Verificar que el usuario está logado y que tiene los parámetros necesarios
// Si no cumple, redirigir a login
if (!isset($_SESSION['usuario_id']) || !isset($_GET['id']) || !isset($_GET['action'])) {
    // Ir a página de login
    header("Location: ../auth/login.php");
    exit; // Detener la ejecución del script
}

// Obtener el ID del usuario actual desde la sesión
$uid = $_SESSION['usuario_id'];

// Obtener el ID de la obra desde la URL
$oid = $_GET['id'];

// Obtener la acción (add o remove) desde la URL
$action = $_GET['action'];

// Usar try-catch para manejar errores de base de datos
try {
    // Si la acción es "add", añadir a favoritos
    if ($action == 'add') {
        // Preparar consulta INSERT (IGNORE evita duplicados)
        $stmt = $pdo->prepare("INSERT IGNORE INTO favoritos (usuario_id, obra_id) VALUES (?, ?)");
        
        // Ejecutar con los parámetros del usuario y la obra
        $stmt->execute([$uid, $oid]);
    } 
    // Si la acción es "remove", eliminar de favoritos
    else {
        // Preparar consulta DELETE
        $stmt = $pdo->prepare("DELETE FROM favoritos WHERE usuario_id = ? AND obra_id = ?");
        
        // Ejecutar con los parámetros del usuario y la obra
        $stmt->execute([$uid, $oid]);
    }
} 
// Si hay error, no hacer nada (fail silencioso)
catch (Exception $e) {
    // No mostrar error, simplemente continuar
}

// Redirigir a la página anterior (donde venía el usuario)
header("Location: " . $_SERVER['HTTP_REFERER']);
?>