<?php
// ========================================
// index.php - PÁGINA DE INICIO
// ========================================

// Incluir header con menú
include 'header.php';

// Incluir conexión a base de datos
include 'config/database.php';

// Obtener 3 obras aleatorias para mostrar como destacadas
try {
    // Preparar consulta para obtener obras al azar
    $stmt = $pdo->query("SELECT * FROM obras ORDER BY RAND() LIMIT 3");
    
    // Obtener todos los resultados
    $featured = $stmt->fetchAll();
} 
// Si hay error en la base de datos, inicializar array vacío
catch (PDOException $e) {
    $featured = [];
}
?>

<!-- SECCIÓN HERO: Encabezado principal con llamada a acción -->
<section class="hero">
    <!-- Título principal -->
    <h1>Arte que inspira tu mundo</h1>
    
    <!-- Descripción -->
    <p>Descubre obras originales y láminas exclusivas de artistas emergentes.</p>
    
    <!-- Botones de navegación -->
    <div style="margin-top: 2rem;">
        <!-- Botón para ver toda la colección -->
        <a href="obras/listar.php" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem;">
            Ver Colección Completa
        </a>
        
        <!-- Botón para ver solo láminas -->
        <a href="obras/listar.php?tipo=lamina" class="btn btn-secondary"
            style="margin-left: 1rem; padding: 1rem 2.5rem; font-size: 1.1rem;">
            Ver Láminas
        </a>
    </div>
</section>

<!-- CONTENEDOR PRINCIPAL -->
<div class="container">
    <!-- Encabezado de obras destacadas -->
    <h2 class="section-title">Obras Destacadas</h2>

    <!-- Si no hay obras destacadas, mostrar mensaje -->
    <?php if (empty($featured)): ?>
        <!-- Párrafo centrado -->
        <p class="text-center">Pronto tendremos novedades para ti.</p>
    
    <!-- Si hay obras, mostrar grid con las 3 destacadas -->
    <?php else: ?>
        <!-- Grid de obras -->
        <div class="grid">
            <!-- Recorrer cada obra destacada -->
            <?php foreach ($featured as $obra): ?>
                <!-- Tarjeta de obra -->
                <div class="card">
                    <!-- Imagen de la obra -->
                    <div class="card-img-container">
                        <!-- Si hay imagen, mostrarla -->
                        <?php if ($obra['imagen']): ?>
                            <img src="<?php echo htmlspecialchars($obra['imagen']); ?>"
                                alt="<?php echo htmlspecialchars($obra['titulo']); ?>" 
                                class="card-img">
                        <!-- Si no hay imagen, mostrar texto -->
                        <?php else: ?>
                            <span>Sin Imagen</span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Contenido de la tarjeta -->
                    <div class="card-body">
                        <!-- Título de la obra -->
                        <h3 class="card-title">
                            <?php echo htmlspecialchars($obra['titulo']); ?>
                        </h3>
                        
                        <!-- Tipo de obra (original, lámina, digital) -->
                        <div class="card-meta">
                            <?php echo ucfirst($obra['tipo']); ?>
                        </div>

                        <!-- Precio con dos decimales y símbolo de euro -->
                        <div class="card-price">
                            <span>
                                <?php echo number_format($obra['precio'], 2); ?> €
                            </span>
                        </div>

                        <!-- Botón para ver detalles -->
                        <div class="actions">
                            <a href="obras/listar.php" class="btn btn-primary"
                                style="width: 100%; text-align: center; font-size: 0.9rem;">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- PIE DE PÁGINA -->
<footer style="background: var(--primary); color: white; text-align: center; padding: 2rem; margin-top: 4rem;">
    <!-- Año actual y copyright -->
    <p>&copy; <?php echo date('Y'); ?> PHP_ART Gallery. Todos los derechos reservados.</p>
</footer>

</body>

</html>