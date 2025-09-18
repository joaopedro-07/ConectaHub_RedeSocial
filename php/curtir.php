<?php
session_start();
include '../conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    die("Usuário não logado.");
}

$usuarioId = $_SESSION['id_usuario'];
$idPost = $_POST['id_post'];

if (isset($_POST['curtir'])) {
    $sql = "INSERT IGNORE INTO curtidas (fk_usuario, fk_post) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $usuarioId, $idPost);
    $stmt->execute();
} elseif (isset($_POST['descurtir'])) {
    $sql = "DELETE FROM curtidas WHERE fk_usuario = ? AND fk_post = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $usuarioId, $idPost);
    $stmt->execute();
}

header("Location: feed.php");
exit;
?>