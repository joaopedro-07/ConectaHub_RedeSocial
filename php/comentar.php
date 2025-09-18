<?php
session_start();
include '../conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    die("Usuário não logado.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_post = $_POST['id_post'];
    $id_usuario = $_SESSION['id_usuario'];
    $texto = trim($_POST['texto_comentario']);

    if (!empty($texto)) {
        $sql = "INSERT INTO comentarios (fk_post, fk_usuario, texto_comentario) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $id_post, $id_usuario, $texto);
        $stmt->execute();
    }
}

header("Location: feed.php");
exit;