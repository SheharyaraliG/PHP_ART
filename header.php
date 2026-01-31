<?php
// ========================================
// header.php - MENÚ Y ENCABEZADO
// ========================================

// Verificar si la sesión ya está iniciada
// Si no está iniciada, iniciarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir la URL base para que los enlaces funcionen desde cualquier carpeta
// Esto hace que los enlaces siempre apunten a /PHP_ART
$base_url = '/PHP_ART';
?>

<!DOCTYPE html>
<!-- Documento HTML5 en español -->
<html lang="es">

<!-- ENCABEZADO DEL DOCUMENTO (no visible en la página) -->
<head>
    <!-- Codificación de caracteres (UTF-8 para caracteres especiales) -->
    <meta charset="UTF-8">
    
    <!-- Viewport para diseño responsivo en móviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Título de la página (aparece en la pestaña del navegador) -->
    <title>Galería de Arte | Premium</title>
    
    <!-- Conectar con Google Fonts para fuentes especiales -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Importar fuente Outfit en diferentes pesos (300, 400, 600, 700) -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Enlazar archivo CSS externo para estilos -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
</head>

<!-- CUERPO DEL DOCUMENTO (contenido visible) -->
<body>

    <!-- ENCABEZADO PRINCIPAL CON MENÚ -->
    <header>
        <!-- Barra de navegación -->
        <div class="navbar">
            <!-- LOGO (que enlaza a la página principal) -->
            <div class="logo">
                <a href="<?php echo $base_url; ?>/index.php">PHP_ART</a>
            </div>

            <!-- MENÚ DE NAVEGACIÓN -->
            <nav class="nav-links">
                <!-- Enlace a ver todas las obras -->
                <a href="<?php echo $base_url; ?>/obras/listar.php">Colección</a>
                
                <!-- Enlace a la página de búsqueda -->
                <a href="<?php echo $base_url; ?>/buscar.php">Buscar</a>

                <!-- Si el usuario está logado, mostrar menú especial -->
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    
                    <!-- Si el usuario es admin, mostrar botón para subir obras -->
                    <?php if ($_SESSION['usuario_rol'] == 'admin'): ?>
                        <a href="<?php echo $base_url; ?>/obras/crear.php" style="color: var(--accent);">
                            + Subir Obra
                        </a>
                    <?php endif; ?>

                    <!-- Enlace a favoritos con icono de corazón -->
                    <a href="<?php echo $base_url; ?>/favoritos/ver.php">
                        ❤️ Favoritos
                    </a>

                    <!-- Zona con nombre del usuario y botón salir -->
                    <div style="margin-left: 10px; display: inline-flex; align-items: center; gap: 10px;">
                        <!-- Saludar al usuario con su nombre -->
                        <span style="font-size: 0.9rem;">
                            Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                        </span>
                        
                        <!-- Botón para cerrar sesión -->
                        <a href="<?php echo $base_url; ?>/auth/logout.php" class="btn btn-secondary"
                            style="padding: 0.4rem 1rem; font-size: 0.8rem;">
                            Salir
                        </a>
                    </div>
                
                <!-- Si el usuario NO está logado, mostrar enlaces de login/registro -->
                <?php else: ?>
                    <!-- Enlace a iniciar sesión -->
                    <a href="<?php echo $base_url; ?>/auth/login.php">Entrar</a>
                    
                    <!-- Botón para crear nueva cuenta -->
                    <a href="<?php echo $base_url; ?>/auth/registro.php" class="btn btn-primary">
                        Registrarse
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>