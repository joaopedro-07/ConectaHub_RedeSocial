<?php
session_start();
include '../conexao.php';

// busca posts (os mais recentes primeiro)
// $sql = "SELECT p.id_post, p.titulo_post, p.descricao_post, p.imagem_post, p.data_post, p.fk_usuario_post, u.nome_usuario 
//         FROM posts p
//         JOIN usuarios u ON p.fk_usuario_post = u.id_usuario
//         ORDER BY p.data_post DESC";

$sql = "SELECT p.id_post, p.titulo_post, p.descricao_post, p.imagem_post, p.data_post, 
        p.fk_usuario_post, u.nome_usuario, u.foto_usuario
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
        <form action="publicar.php" method="post" enctype="multipart/form-data">
            <h3>Nova Publicação</h3>
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo_post" required>

            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao_post" required>

            <label for="imagem">Imagem (opcional):</label>
            <input type="file" id="imagem" name="imagem_post" accept="image/*">

            <button type="submit">Publicar</button>
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($post = $result->fetch_assoc()): ?>
                <div class="post">
                    <div class="autor">
                        <img src="../img/default_user.png.png" alt="Usuário" class="foto-autor">
                        <h4><?= htmlspecialchars($post['nome_usuario']) ?></h4>
                    </div>

                    <h3><?= htmlspecialchars($post['titulo_post']) ?></h3>
                    <p><?= htmlspecialchars($post['descricao_post']) ?></p>

                    <?php if (!empty($post['imagem_post'])): ?>
                        <img class="img-post" 
                             src="data:image/jpeg;base64,<?= base64_encode($post['imagem_post']) ?>" 
                             alt="Imagem do post">
                    <?php endif; ?>

                    <div class="post-meta">
                        <?php
                                // verifica se o usuário logado já curtiu
                                $usuarioId = $_SESSION['id_usuario'];
                                $idPost = $post['id_post'];
                                $checkCurtir = $conn->prepare("SELECT 1 FROM curtidas WHERE fk_usuario = ? AND fk_post = ?");
                                $checkCurtir->bind_param("ii", $usuarioId, $idPost);
                                $checkCurtir->execute();
                                $jaCurtiu = $checkCurtir->get_result()->num_rows > 0;

                                // conta total de curtidas
                                $contaCurtir = $conn->prepare("SELECT COUNT(*) as total FROM curtidas WHERE fk_post = ?");
                                $contaCurtir->bind_param("i", $idPost);
                                $contaCurtir->execute();
                                $totalCurtidas = $contaCurtir->get_result()->fetch_assoc()['total'];
                            ?>
                        <div class="curtir-linha">
                            <form action="curtir.php" method="post">
                                <input type="hidden" name="id_post" value="<?= $post['id_post'] ?>">
                                <div class="curtir-quantidade">
                                <button class="botao-curtir" type="submit" name="<?= $jaCurtiu ? 'descurtir' : 'curtir' ?>">
                                    <?= $jaCurtiu ? '❤️' : '🤍' ?>
                                </button>
                                <span><?= $totalCurtidas ?> curtidas</span>
                                </div>
                                <small>Publicado em <?= date('d/m/Y', strtotime($post['data_post'])) ?></small>
                            </form>
                        </div>
                    </div>


                    <?php if ($post['fk_usuario_post'] == $_SESSION['id_usuario']): ?>
                        <div class="post-actions">
                            <button class="btn-edit" 
                                    onclick="abrirEdicao(<?= $post['id_post'] ?>, '<?= htmlspecialchars($post['titulo_post'], ENT_QUOTES) ?>', '<?= htmlspecialchars($post['descricao_post'], ENT_QUOTES) ?>')">
                                ✏️ Editar
                            </button>
                            <button class="btn-delete" onclick="excluirPost(<?= $post['id_post'] ?>)">🗑️ Excluir</button>
                        </div>
                    <?php endif; ?>

                    <div class="comentarios">
                        <form class="form-coment" action="comentar.php" method="post">
                            <input type="hidden" name="id_post" value="<?= $post['id_post'] ?>">
                            <textarea name="texto_comentario" placeholder="Escreva um comentário..." required></textarea>
                            <button type="submit">Comentar</button>
                        </form>
                        <?php
                        $id_post = $post['id_post'];
                        $sqlComentarios = "SELECT c.texto_comentario, c.data_comentario, u.nome_usuario
                                        FROM comentarios c
                                        JOIN usuarios u ON c.fk_usuario = u.id_usuario
                                        WHERE c.fk_post = ?
                                        ORDER BY c.data_comentario ASC";
                        $stmtComentarios = $conn->prepare($sqlComentarios);
                        $stmtComentarios->bind_param("i", $id_post);
                        $stmtComentarios->execute();
                        $comentarios = $stmtComentarios->get_result();
                    ?>

                    <div class="lista-comentarios">
                        <?php if ($comentarios->num_rows > 0): ?>
                            <?php while ($c = $comentarios->fetch_assoc()): ?>
                                <p><strong><?= htmlspecialchars($c['nome_usuario']) ?>:</strong> 
                                <?= htmlspecialchars($c['texto_comentario']) ?></p>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>Seja o primeiro a comentar!</p>
                        <?php endif; ?>
                    </div>
                    </div>        
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function excluirPost(id) {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("excluir_post.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id_post=" + id
                })
                .then(res => res.text())
                .then(data => {
                    if (data.trim() === "success") {
                        Swal.fire('Excluído!', 'Seu post foi excluído.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Erro!', 'Não foi possível excluir.', 'error');
                    }
                });
            }
        });
    }

    function abrirEdicao(id, titulo, descricao) {
        Swal.fire({
            title: 'Editar Post',
            html: `
                <form id="form-editar" enctype="multipart/form-data" method="POST" action="editar_post.php">
                    <input type="hidden" name="id_post" value="${id}">
                    <label>Título:</label>
                    <input type="text" name="titulo_post" value="${titulo}" required class="swal2-input">
                    <label>Descrição:</label>
                    <input type="text" name="descricao_post" value="${descricao}" required class="swal2-input">
                    <label>Imagem (opcional):</label>
                    <input type="file" name="imagem_post" accept="image/*" class="swal2-input">
                    <button type="submit" class="swal2-confirm swal2-styled" style="display:block; margin:15px auto;">Salvar</button>
                </form>
            `,
            showConfirmButton: false
        });
    }
    </script>
</body>
</html>