<?php
include("../conexao.php");
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../index.php");
    exit();
}

$id = $_SESSION["id_usuario"];
$nome_usuario = $_POST['nome_usuario'];

$foto_usuario = null;

if (isset($_FILES['foto_usuario']) && $_FILES['foto_usuario']['error'] === 0) {
    $extensao = pathinfo($_FILES['foto_usuario']['name'], PATHINFO_EXTENSION);
    $permitidos = ['jpg','jpeg','png','gif','webp'];

    if (in_array(strtolower($extensao), $permitidos)) {
        $novo_nome = uniqid() . "." . $extensao;
        $caminho = "../uploads/" . $novo_nome;

        if (!is_dir("../uploads")) {
            mkdir("../uploads", 0777, true);
        }

        if (move_uploaded_file($_FILES['foto_usuario']['tmp_name'], $caminho)) {
            $foto_usuario = $novo_nome;
        } else {
            die("Erro ao enviar a foto.");
        }
    } else {
        die("Formato de foto inválido.");
    }
}
if ($foto_usuario) {
    $stmt = $conn->prepare("UPDATE usuarios SET nome_usuario = ?, foto_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("ssi", $nome_usuario, $foto_usuario, $id);
} else {
    $stmt = $conn->prepare("UPDATE usuarios SET nome_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("si", $nome_usuario, $id);
}

if ($stmt->execute()) {
    $_SESSION['nome_usuario'] = $nome_usuario;
    header("Location: perfil.php");
    exit();
} else {
    die("Erro ao atualizar perfil: " . $conn->error);
}
?>
