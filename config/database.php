<?php
// ========================================
// config/database.php - CONEXIÓN A BASE DE DATOS
// ========================================

// Variables de conexión a MySQL
$host = 'localhost';          // Servidor (en XAMPP es localhost)
$db_name = 'galeria_arte';    // Nombre de la base de datos
$username = 'root';           // Usuario MySQL (en XAMPP es root)
$password = '';               // Contraseña (en XAMPP suele ser vacía)

// Intentar conectar a la base de datos usando PDO
try {
    // Crear conexión PDO
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
        $username,
        $password
    );
    
    // Configurar PDO para mostrar errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configurar PDO para devolver resultados como arrays asociativos
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Si hay error de conexión, mostrar el mensaje y detener
    die("Error de conexión: " . $e->getMessage());
}

// La variable $pdo ahora está disponible en todos los archivos que incluyan este archivo
?>