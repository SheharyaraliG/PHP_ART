<?php
// ========================================
// favoritos/ver.php - VER MIS FAVORITOS
// ========================================

// Incluir conexión a base de datos
include '../config/database.php';

// Incluir header con menú
include '../header.php';

// Verificar que el usuario está logado
// Si no está logado, redirigir a login
if (!isset($_SESSION['usuario_id'])) {
    // Usar JavaScript para redirigir
    echo "<script>window.location='../auth/login.php'</script>";
    exit; // Detener la ejecución
}

// Preparar consulta SQL para obtener las obras favoritas del usuario
// Hacer JOIN entre obras y favoritos para obtener solo las favoritas
$stmt = $pdo->prepare("SELECT obras.* FROM obras JOIN favoritos ON obras.id = favoritos.obra_id WHERE favoritos.usuario_id = ?");

// Ejecutar con el ID del usuario logado
$stmt->execute([$_SESSION['usuario_id']]);

// Obtener todos los favoritos como un array
$obras = $stmt->fetchAll();
?>

<!-- HTML: Mostrar favoritos -->
<div class="container">
    <!-- Encabezado de la página -->
    <h2 class="section-title">Mis Favoritos</h2>

    <!-- Si no hay favoritos, mostrar mensaje -->
    <?php if (empty($obras)): ?>
        <!-- Div centrado con mensaje -->
        <div class="text-center">
            <!-- Texto indicando que no hay favoritos -->
            <p>Aún no tienes favoritos.</p>
            
            <!-- Enlace para ir a explorar obras -->
            <a href="../obras/listar.php" class="btn btn-primary">
                Explorar Obras
            </a>
        </div>
    
    <!-- Si hay favoritos, mostrar grid con las obras -->
    <?php else: ?>
        <!-- Grid para mostrar favoritos -->
        <div class="grid">
            <!-- Recorrer cada obra favorita -->
            <?php foreach ($obras as $obra): ?>
                <!-- Tarjeta de obra -->
                <div class="card">
                    <!-- Imagen de la obra -->
                    <div class="card-img-container">
                        <!-- Mostrar imagen con ruta relativa ../ (estamos en favoritos/ subdirectorio) -->
                        <img src="<?php echo htmlspecialchars('../' . $obra['imagen']); ?>" 
                             class="card-img"
                             alt="<?php echo htmlspecialchars($obra['titulo']); ?>">
                    </div>
                    
                    <!-- Contenido de la tarjeta -->
                    <div class="card-body">
                        <!-- Título de la obra -->
                        <h3 class="card-title">
                            <?php echo htmlspecialchars($obra['titulo']); ?>
                        </h3>
                        
                        <!-- Precio con dos decimales -->
                        <div class="card-price">
                            <?php echo number_format($obra['precio'], 2); ?> €
                        </div>
                        
                        <!-- Botón para eliminar de favoritos -->
                        <div class="actions">
                            <a href="accion.php?id=<?php echo $obra['id']; ?>&action=remove" 
                               class="btn btn-danger"
                               style="width:100%; text-align:center;">
                                ❌ Eliminar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>

</html>