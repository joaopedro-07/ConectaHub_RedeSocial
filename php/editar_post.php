<?php
include("../conexao.php");
session_start();

if (!isset($_SESSION["id_usuario"])) {
    die("Usuário não logado.");
}

$idPost = $_POST["id_post"] ?? null;
$titulo = $_POST["titulo_post"] ?? "";
$descricao = $_POST["descricao_post"] ?? "";
$idUsuario = $_SESSION["id_usuario"];

$imagem = null;
if (!empty($_FILES["imagem_post"]["tmp_name"])) {
    $imagem = file_get_contents($_FILES["imagem_post"]["tmp_name"]);
}

if ($imagem) {
    $sql = "UPDATE posts SET titulo_post = ?, descricao_post = ?, imagem_post = ? 
            WHERE id_post = ? AND fk_usuario_post = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $titulo, $descricao, $imagem, $idPost, $idUsuario);
} else {
    $sql = "UPDATE posts SET titulo_post = ?, descricao_post = ? 
            WHERE id_post = ? AND fk_usuario_post = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $titulo, $descricao, $idPost, $idUsuario);
}

if ($stmt->execute()) {
    header("Location: feed.php");
    exit;
} else {
    echo "Erro ao editar.";
}