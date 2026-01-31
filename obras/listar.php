<?php
// ========================================
// obras/listar.php - MOSTRAR CATÁLOGO DE OBRAS
// ========================================

// Incluir conexión a base de datos
include '../config/database.php';

// Incluir header
include '../header.php';

// Recoger filtro de tipo (original, lamina, digital)
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

// Construir consulta SQL para obtener obras
$sql = "SELECT * FROM obras WHERE 1=1";
$params = [];

// Si hay filtro de tipo, añadirlo a la consulta
if ($tipo) {
    $sql .= " AND tipo = ?";
    $params[] = $tipo;
}

// Ordenar por más recientes primero
$sql .= " ORDER BY created_at DESC";

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Obtener todos los resultados como un array
$obras = $stmt->fetchAll();
?>

<!-- HTML: Mostrar catálogo -->
<div class="container">
    <!-- Encabezado con filtros -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <!-- Título -->
        <h2 style="font-weight: 300; font-size: 2rem; margin: 0;">
            Colección <span style="font-weight: 700;">Exclusiva</span>
        </h2>

        <!-- Botones de filtro -->
        <div class="filters">
            <!-- Botón para ver todas las obras -->
            <a href="listar.php" 
               class="btn <?php echo !$tipo ? 'btn-primary' : 'btn-secondary'; ?>"
               style="padding: 5px 15px; font-size: 0.9rem;">
                Todo
            </a>
            
            <!-- Botón para filtrar por originales -->
            <a href="listar.php?tipo=original" 
               class="btn <?php echo $tipo == 'original' ? 'btn-primary' : 'btn-secondary'; ?>"
               style="padding: 5px 15px; font-size: 0.9rem;">
                Originales
            </a>
            
            <!-- Botón para filtrar por láminas -->
            <a href="listar.php?tipo=lamina" 
               class="btn <?php echo $tipo == 'lamina' ? 'btn-primary' : 'btn-secondary'; ?>"
               style="padding: 5px 15px; font-size: 0.9rem;">
                Láminas
            </a>
        </div>
    </div>

    <!-- Mostrar obras en grid -->
    <?php if (empty($obras)): ?>
        <!-- Si no hay obras, mostrar mensaje -->
        <div class="text-center" style="padding: 4rem;">
            <p style="font-size: 1.2rem; color: #999;">
                No hay obras disponibles en esta categoría.
            </p>
        </div>
    <?php else: ?>
        <!-- Grid de obras -->
        <div class="grid">
            <?php foreach ($obras as $obra): ?>
                <!-- Tarjeta de obra -->
                <div class="card">
                    <!-- Imagen de la obra -->
                    <div class="card-img-container">
                        <?php if ($obra['imagen']): ?>
                            <!-- Mostrar imagen con ruta relativa ../ -->
                            <img src="<?php echo htmlspecialchars('../' . $obra['imagen']); ?>" 
                                 class="card-img">
                        <?php else: ?>
                            <!-- Si no hay imagen, mostrar texto -->
                            <span>Sin Imagen</span>
                        <?php endif; ?>
                    </div>

                    <!-- Contenido de la tarjeta -->
                    <div class="card-body">
                        <div>
                            <!-- Título de la obra -->
                            <h3 class="card-title">
                                <?php echo htmlspecialchars($obra['titulo']); ?>
                            </h3>
                            
                            <!-- Tipo y disponibilidad -->
                            <div class="card-meta">
                                <?php echo ucfirst($obra['tipo']); ?>
                                <?php if ($obra['stock'] < 1)
                                    echo '• <span style="color:red">Agotado</span>'; ?>
                            </div>
                        </div>

                        <!-- Precio y corazón (favoritos) -->
                        <div class="card-price">
                            <?php echo number_format($obra['precio'], 2); ?> €

                            <!-- Corazón para favoritos (solo si está logado) -->
                            <?php if (isset($_SESSION['usuario_id'])): ?>
                                <?php 
                                    // Verificar si esta obra está en favoritos
                                    $stmt_fav = $pdo->prepare("SELECT COUNT(*) as count FROM favoritos WHERE usuario_id = ? AND obra_id = ?");
                                    $stmt_fav->execute([$_SESSION['usuario_id'], $obra['id']]);
                                    $es_favorito = $stmt_fav->fetch()['count'] > 0;
                                ?>
                                <?php if ($es_favorito): ?>
                                    <!-- Si está en favoritos, mostrar corazón rojo -->
                                    <a href="../favoritos/accion.php?id=<?php echo $obra['id']; ?>&action=remove" 
                                       title="Quitar de favoritos" style="font-size: 1.5rem; color: #e74c3c;">
                                        ❤
                                    </a>
                                <?php else: ?>
                                    <!-- Si NO está en favoritos, mostrar corazón gris -->
                                    <a href="../favoritos/accion.php?id=<?php echo $obra['id']; ?>&action=add" 
                                       title="Añadir a favoritos" style="font-size: 1.5rem; color: #ccc;">
                                        ❤
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Botones de acción -->
                        <div class="actions">
                            <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 'admin'): ?>
                                <!-- Si es admin, mostrar botones editar y eliminar -->
                                <a href="editar.php?id=<?php echo $obra['id']; ?>" 
                                   class="btn btn-secondary"
                                   style="flex:1; text-align:center;">
                                    Editar
                                </a>
                                <a href="eliminar.php?id=<?php echo $obra['id']; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('¿Borrar?')" 
                                   style="padding: 0.6rem;">
                                    🗑
                                </a>
                            <?php else: ?>
                                <!-- Si no es admin, mostrar botón carrito -->
                                <button class="btn btn-primary" style="width: 100%;">
                                    Añadir al Carrito
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>