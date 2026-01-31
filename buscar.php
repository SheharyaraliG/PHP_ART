<?php
// ========================================
// buscar.php - BUSCADOR DE OBRAS DE ARTE
// ========================================

// Incluir conexión a base de datos
include 'config/database.php';

// Incluir header con menú
include 'header.php';

// Recoger término de búsqueda desde GET (parámetro 'q')
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// Inicializar array vacío para almacenar resultados
$obras = [];

// Si hay un término de búsqueda, buscar en la base de datos
if ($q) {
    // Preparar consulta SQL para buscar en título o tipo
    $stmt = $pdo->prepare("SELECT * FROM obras WHERE titulo LIKE ? OR tipo LIKE ?");
    
    // Crear parámetro con % para búsqueda parcial
    $param = "%$q%";
    
    // Ejecutar la consulta con dos parámetros
    $stmt->execute([$param, $param]);
    
    // Obtener todos los resultados como un array
    $obras = $stmt->fetchAll();
}
?>

<!-- HTML: Buscador y resultados -->
<div class="container">
    <!-- Encabezado con buscador -->
    <div class="text-center" style="margin-bottom: 3rem;">
        <!-- Título principal -->
        <h2 class="section-title">¿Qué buscas hoy?</h2>
        
        <!-- Formulario de búsqueda -->
        <form action="buscar.php" class="auth-box" style="margin: 0 auto; padding: 1.5rem; display: flex; gap: 10px;">
            <!-- Campo de entrada para el término de búsqueda -->
            <input type="text" 
                   name="q" 
                   value="<?php echo htmlspecialchars($q); ?>"
                   placeholder="Arte abstracto, láminas..."
                   style="flex:1; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
            
            <!-- Botón para enviar búsqueda -->
            <button class="btn btn-primary">Buscar</button>
        </form>
    </div>

    <!-- Si hay una búsqueda, mostrar resultados -->
    <?php if ($q): ?>
        <!-- Encabezado con el término buscado -->
        <h3 style="margin-bottom: 2rem;">
            Resultados para "<strong><?php echo htmlspecialchars($q); ?></strong>"
        </h3>
        
        <!-- Grid de obras encontradas -->
        <div class="grid">
            <!-- Recorrer cada obra encontrada -->
            <?php foreach ($obras as $obra): ?>
                <!-- Tarjeta de obra -->
                <div class="card">
                    <!-- Imagen de la obra -->
                    <div class="card-img-container">
                        <!-- Mostrar imagen con ruta relativa -->
                        <img src="<?php echo htmlspecialchars($obra['imagen']); ?>" 
                             class="card-img"
                             alt="<?php echo htmlspecialchars($obra['titulo']); ?>">
                    </div>
                    
                    <!-- Contenido de la tarjeta -->
                    <div class="card-body">
                        <!-- Título de la obra -->
                        <h3 class="card-title">
                            <?php echo htmlspecialchars($obra['titulo']); ?>
                        </h3>
                        
                        <!-- Precio y botón favoritos -->
                        <div class="card-price" style="display: flex; justify-content: space-between; align-items: center;">
                            <!-- Mostrar precio con dos decimales -->
                            <span><?php echo number_format($obra['precio'], 2); ?> €</span>
                            
                            <!-- Corazón para añadir/quitar de favoritos (solo si está logado) -->
                            <?php if (isset($_SESSION['usuario_id'])): ?>
                                <?php 
                                    // Verificar si esta obra está en favoritos del usuario
                                    $stmt_fav = $pdo->prepare("SELECT COUNT(*) as count FROM favoritos WHERE usuario_id = ? AND obra_id = ?");
                                    $stmt_fav->execute([$_SESSION['usuario_id'], $obra['id']]);
                                    
                                    // Si count > 0, está en favoritos
                                    $es_favorito = $stmt_fav->fetch()['count'] > 0;
                                ?>
                                
                                <!-- Si está en favoritos, mostrar corazón rojo -->
                                <?php if ($es_favorito): ?>
                                    <a href="favoritos/accion.php?id=<?php echo $obra['id']; ?>&action=remove" 
                                       title="Quitar de favoritos" 
                                       style="font-size: 1.5rem; color: #e74c3c;">
                                        ❤
                                    </a>
                                <!-- Si NO está en favoritos, mostrar corazón gris -->
                                <?php else: ?>
                                    <a href="favoritos/accion.php?id=<?php echo $obra['id']; ?>&action=add" 
                                       title="Añadir a favoritos" 
                                       style="font-size: 1.5rem; color: #ccc;">
                                        ❤
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Botón para ver la obra completa -->
                        <div class="actions">
                            <a href="obras/listar.php" class="btn btn-secondary" style="width:100%; text-align:center;">
                                Ver Colección
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