<?php
include("../conexao.php");
session_start();

if (!isset($_SESSION["id_usuario"])) {
    die("Usuário não logado.");
}

$idPost = $_POST["id_post"] ?? null;
$idUsuario = $_SESSION["id_usuario"];

// Verifica se o post pertence ao usuário logado
$sql = "DELETE FROM posts WHERE id_post = ? AND fk_usuario_post = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idPost, $idUsuario);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}