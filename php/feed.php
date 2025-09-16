<?php
session_start();
include '../conexao.php';

// busca posts (os mais recentes primeiro)
$sql = "SELECT p.id_post, p.titulo_post, p.descricao_post, p.imagem_post, p.data_post, u.nome_usuario 
        FROM posts p
        JOIN usuarios u ON p.fk_usuario_post = u.id_usuario
        ORDER BY p.data_post DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/feed.css">
    <title>Feed</title>
</head>
<body>
    <header>
        <nav>
            <img src="../img/conectahub_logoFundoAzul.png" alt="">
            <ul>
                <li><a href="../php/feed.php">Feed</a></li>
                <li><a href="../php/chat.php">Mensagens</a></li>
                <li><a href="../php/perfil.php">Perfil</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Nova Publicação</h1>
        <form action="publicar.php" method="post" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo_post" required>

            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao_post" required>
            <!-- <textarea id="descricao" name="descricao_post" required></textarea> -->

            <label for="imagem">Imagem (opcional):</label>
            <input type="file" id="imagem" name="imagem_post" accept="image/*">

            <button type="submit">Publicar</button>
        </form>


        <h2 id="post-recente">Posts recentes</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($post = $result->fetch_assoc()): ?>
                <div class="post">
                    <div class="autor">
                        <img src="../img/palmeiras_escudo.png" alt="">
                        <h4><?= htmlspecialchars($post['nome_usuario']) ?></h4>
                    </div>

                    <h3><?= htmlspecialchars($post['titulo_post']) ?></h3>
                    <p><?= htmlspecialchars($post['descricao_post']) ?></p>

                    <?php if (!empty($post['imagem_post'])): ?>
                        <img class="img-post" 
                             src="data:image/jpeg;base64,<?= base64_encode($post['imagem_post']) ?>" 
                             alt="Imagem do post">
                    <?php endif; ?>

                    <small>Publicado em <?= date('d/m/Y H:i', strtotime($post['data_post'])) ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum post ainda.</p>
        <?php endif; ?>
    </main>
    <footer>
        <div class="footer-links">
            <a href="https://www.linkedin.com/in/gabriel-moreira-3b75a92b7/" target="_blank">Gabriel Moreira</a>
            <a href="https://www.linkedin.com/in/jo%C3%A3o-gustavo-mota-ramos-9b60242a2/" target="_blank">João Gustavo</a>
            <a href="https://www.linkedin.com/in/jo%C3%A3o-pedro-da-cunha-machado-2089482b7/" target="_blank">João Pedro</a>
        </div>
    </footer>
</body>
</html>