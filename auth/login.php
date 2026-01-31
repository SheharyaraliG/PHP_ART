<?php
// ========================================
// auth/login.php - INICIAR SESIÓN
// ========================================

// Incluir configuración de base de datos
include '../config/database.php';

// Incluir header (menú y encabezado)
include '../header.php';

// Variable para guardar mensajes de error
$error = '';

// Comprobar si se envió el formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger email del formulario
    $email = trim($_POST['email']);
    
    // Recoger contraseña del formulario
    $password = $_POST['password'];

    // Buscar usuario en la base de datos por email
    // Usar ? para evitar inyección SQL
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    
    // Ejecutar la consulta con el email
    $stmt->execute([$email]);
    
    // Traer el resultado como array
    $user = $stmt->fetch();

    // Comprobar si el usuario existe y si la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) {
        // Contraseña correcta: crear sesión
        // $_SESSION guarda datos del usuario mientras está logado
        $_SESSION['usuario_id'] = $user['id'];              // ID del usuario
        $_SESSION['usuario_nombre'] = $user['nombre'];      // Nombre del usuario
        $_SESSION['usuario_rol'] = $user['rol'];            // Rol (admin, etc.)
        
        // Redirigir al inicio
        header("Location: ../index.php");
        exit;
    } else {
        // Usuario no existe o contraseña incorrecta
        $error = "Email o contraseña incorrectos.";
    }
}
?>

<!-- HTML: Formulario de login -->
<div class="auth-box">
    <!-- Título -->
    <h2 class="text-center" style="color: var(--primary); margin-bottom: 2rem;">Iniciar Sesión</h2>

    <!-- Mostrar error si hay -->
    <?php if ($error): ?>
        <div class="alert alert-error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <form action="login.php" method="POST">
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

        <!-- Botón enviar -->
        <button type="submit" class="btn btn-primary" style="width: 100%;">Entrar</button>
    </form>

    <!-- Enlace a registro -->
    <p class="text-center" style="margin-top: 1.5rem; color: var(--text-light); font-size: 0.9rem;">
        ¿Nuevo aquí? <a href="registro.php" style="color: var(--accent); font-weight: 600;">Crear cuenta</a>
    </p>
</div>
</body>

</html>