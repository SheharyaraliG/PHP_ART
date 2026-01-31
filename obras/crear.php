<?php
// obras/crear.php
include '../config/database.php';
include '../header.php';

if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'admin') {
    die("<div class='container alert alert-error'>Acceso Denegado</div>");
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $precio = $_POST['precio'];
    $tipo = $_POST['tipo'];
    $imagen = $_POST['imagen'];
    $usuario_id = $_SESSION['usuario_id'];

    $sql = "INSERT INTO obras (titulo, precio, tipo, imagen, usuario_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$titulo, $precio, $tipo, $imagen, $usuario_id])) {
        header("Location: listar.php");
    } else {
        $error = "Error al guardar.";
    }
}
?>

<div class="auth-box" style="max-width: 600px;">
    <h2 class="section-title">Añadir Obra</h2>

    <form method="POST">
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" required>
        </div>

        <div style="display: flex; gap: 20px;">
            <div class="form-group" style="flex:1;">
                <label>Precio (€)</label>
                <input type="number" step="0.01" name="precio" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Tipo</label>
                <select name="tipo">
                    <option value="original">Original</option>
                    <option value="lamina">Lámina</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>URL Imagen</label>
            <input type="url" name="imagen" placeholder="https://...">
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>

</html>