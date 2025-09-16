<?php
session_start();
include '../conexao.php';

$titulo = $_POST['titulo_post'];
$descricao = $_POST['descricao_post'];
$usuarioId = $_SESSION['id_usuario'] ?? null;

// verifica se enviou imagem
$imagem = null;
if (isset($_FILES['imagem_post']) && $_FILES['imagem_post']['error'] === UPLOAD_ERR_OK) {
    $imagem = file_get_contents($_FILES['imagem_post']['tmp_name']);
}

if ($usuarioId) {
    if ($imagem !== null) {
        $sql = "INSERT INTO posts (titulo_post, descricao_post, imagem_post, data_post, fk_usuario_post) 
                VALUES (?, ?, ?, NOW(), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssbi", $titulo, $descricao, $imagem, $usuarioId);

        // como é blob, precisa mandar pelo send_long_data
        $stmt->send_long_data(2, $imagem); // índice 2 = terceiro parâmetro (imagem)
    } else {
        $sql = "INSERT INTO posts (titulo_post, descricao_post, data_post, fk_usuario_post) 
                VALUES (?, ?, NOW(), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $titulo, $descricao, $usuarioId);
    }

    if ($stmt->execute()) {
        header("Location: feed.php");
        exit;
    } else {
        echo "Erro ao publicar: " . $stmt->error;
    }
} else {
    echo "Usuário não logado.";
}
?>