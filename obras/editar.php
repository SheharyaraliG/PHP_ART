<?php
// obras/editar.php
include '../config/database.php';
include '../header.php';

if (!isset($_GET['id']))
    die("ID faltante");
$id = $_GET['id'];

// Check permissions
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'admin') {
    die("Acceso denegado");
}

// Fetch data
$stmt = $pdo->prepare("SELECT * FROM obras WHERE id = ?");
$stmt->execute([$id]);
$obra = $stmt->fetch();

if (!$obra)
    die("Obra no encontrada");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $precio = $_POST['precio'];
    $tipo = $_POST['tipo'];
    $imagen = $_POST['imagen'];

    $sql = "UPDATE obras SET titulo=?, precio=?, tipo=?, imagen=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $precio, $tipo, $imagen, $id]);

    header("Location: listar.php");
}
?>

<div class="auth-box" style="max-width: 600px;">
    <h2 class="section-title">Editar Obra</h2>

    <form method="POST">
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($obra['titulo']); ?>" required>
        </div>

        <div style="display: flex; gap: 20px;">
            <div class="form-group" style="flex:1;">
                <label>Precio (€)</label>
                <input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($obra['precio']); ?>"
                    required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Tipo</label>
                <select name="tipo">
                    <option value="original" <?php if ($obra['tipo'] == 'original')
                        echo 'selected'; ?>>Original</option>
                    <option value="lamina" <?php if ($obra['tipo'] == 'lamina')
                        echo 'selected'; ?>>Lámina</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>URL Imagen</label>
            <input type="url" name="imagen" value="<?php echo htmlspecialchars($obra['imagen']); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>

</html>