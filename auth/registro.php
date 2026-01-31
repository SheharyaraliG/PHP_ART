<?php
// ========================================
// auth/registro.php - CREAR NUEVA CUENTA
// ========================================

// Incluir configuración de base de datos
include '../config/database.php';

// Incluir header
include '../header.php';

// Variables para mensajes
$error = '';      // Mensaje de error
$success = '';    // Mensaje de éxito

// Comprobar si se envió el formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre = trim($_POST['nombre']);              // Nombre completo
    $email = trim($_POST['email']);                // Email
    $password = $_POST['password'];                // Contraseña

    // Buscar si el email ya existe en la base de datos
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    // Comprobar si hay resultados (email duplicado)
    if ($stmt->rowCount() > 0) {
        // El email ya existe
        $error = "El email ya está registrado.";
    } else {
        // Email no existe: crear nueva cuenta
        // Encriptar contraseña para seguridad
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insertar nuevo usuario en la base de datos
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        
        // Ejecutar la inserción
        if ($stmt->execute([$nombre, $email, $hash])) {
            // Inserción exitosa
            $success = "Cuenta creada con éxito.";
        } else {
            // Error en la inserción
            $error = "Error al registrar.";
        }
    }
}
?>

<!-- HTML: Formulario de registro -->
<div class="auth-box">
    <!-- Título -->
    <h2 class="text-center" style="color: var(--primary); margin-bottom: 2rem;">Únete a PHP_ART</h2>

    <!-- Mostrar error si hay -->
    <?php if ($error): ?>
        <div class="alert alert-error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Mostrar éxito si se registró -->
    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
            <!-- Enlace para ir a login -->
            <a href="login.php" style="display:block; margin-top:10px; font-weight:bold;">Iniciar Sesión</a>
        </div>
    <?php else: ?>

        <!-- Formulario de registro -->
        <form action="registro.php" method="POST">
            <!-- Campo nombre -->
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" required placeholder="Ej: Juan Pérez">
            </div>
            
            <!-- Campo email -->
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="tu@email.com">
            </div>
            
            <!-- Campo contraseña -->
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Registrarse</button>
        </form>

        <p class="text-center" style="margin-top: 1.5rem; color: var(--text-light); font-size: 0.9rem;">
            ¿Ya tienes cuenta? <a href="login.php" style="color: var(--accent); font-weight: 600;">Entrar</a>
        </p>
    <?php endif; ?>
</div>
</body>

</html>