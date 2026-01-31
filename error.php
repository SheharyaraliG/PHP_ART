<?php
// error.php
include 'header.php';
$msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : "Error desconocido";
?>
<div class="container text-center" style="padding: 5rem 0;">
    <h2 style="color: #e74c3c;">Oops!</h2>
    <p class="alert alert-error" style="display: inline-block; margin-top: 1rem;">
        <?php echo $msg; ?>
    </p>
    <div style="margin-top: 2rem;">
        <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>
</div>
</body>

</html>