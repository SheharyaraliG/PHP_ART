<?php
// obras/eliminar.php
include '../config/database.php';
session_start();

if (!isset($_GET['id']) || !isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'admin') {
    header("Location: listar.php");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM obras WHERE id = ?");
$stmt->execute([$_GET['id']]);

header("Location: listar.php");
?>