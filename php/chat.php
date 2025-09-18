<?php
include("../conexao.php");
session_start();

if (!isset($_SESSION["nome_usuario"])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style>

nav {
    height: 120px;
    background-color: #1D4ED8;
    color: white;
    display: flex;
    justify-content: space-evenly;
    align-items: center;
}

nav img {
    height: 120px;
    margin-left: 20px;
}

nav ul {
    display: flex;
    list-style-type: none;
    font-size: 20px;
    gap: 100px;
    margin-left: 100px;
}

nav ul li a {
    color: white;
    text-decoration: none;
}

nav ul li a:hover {
    cursor: pointer;
    text-decoration: underline;
}

nav h3 {
    margin-left: 100px;
}

body {
    margin: 0;
    font-family: 'Poppins';
    background: #f9fafb;
    color: #333;
}

main {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
    margin-top: 10px;
}

h2 {
    margin-bottom: 20px;
    color: #1D4ED8;
}

#chat {
    background: white;
    border: 1px solid #ddd;
    padding: 20px;
    height: 450px;
    overflow-y: auto;
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
}

#chat div {
    margin-bottom: 12px;
    padding: 10px 15px;
    border-radius: 8px;
    max-width: 70%;
    word-wrap: break-word;
}

#chat div strong {
    display: block;
    margin-bottom: 4px;
    color: #1D4ED8;
}

/* Mensagens do usuário atual */
.msg-eu {
    background-color: #DBEAFE;
    align-self: flex-end;
    margin-left: auto;
}

/* Mensagens de outros */
.msg-outro {
    background-color: #F3F4F6;
    align-self: flex-start;
    margin-right: auto;
}

/* ====== Input ====== */
.chat-input {
    display: flex;
    gap: 10px;
}

#mensagem {
    flex: 1;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 16px;
}

#enviar {
    padding: 12px 24px;
    border: none;
    background-color: #1D4ED8;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s;
}

#enviar:hover {
    background-color: #2563EB;
}

    </style>
</head>
<body>
    <header>
        <nav>
            <img src="../img/conectahub_logoFundoAzul.png" alt="Logo ConectaHub">
            <ul>
                <li><a href="../php/feed.php">Feed</a></li>
                <li><a href="../php/chat.php">Mensagens</a></li>
                <li><a href="../php/perfil.php">Perfil</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Bem-vindo ao chat, <?php echo $_SESSION["nome_usuario"]; ?>!</h2>
        <div id="chat"></div>
        <div class="chat-input">
            <input type="text" id="mensagem" placeholder="Digite sua mensagem...">
            <button id="enviar">Enviar</button>
        </div>
    </main>

    <script>
        let lastId = 0;
        const usuarioAtual = "<?php echo $_SESSION['nome_usuario']; ?>";

       function buscarMensagens() {
    fetch('chat_receber.php?last_id=' + lastId)
        .then(res => res.json())
        .then(data => {
            data.forEach(msg => {
                lastId = msg.id;
                const div = document.createElement('div');
                div.classList.add(msg.usuario === usuarioAtual ? 'msg-eu' : 'msg-outro');
                div.innerHTML = `<strong>${msg.usuario}</strong>${msg.texto}`;
                document.getElementById('chat').appendChild(div);
                document.getElementById('chat').scrollTop = document.getElementById('chat').scrollHeight;
            });
            setTimeout(buscarMensagens, 1000); // espera 1s antes de chamar de novo
        })
        .catch(() => setTimeout(buscarMensagens, 2000));
}


        document.getElementById('enviar').addEventListener('click', () => {
            const texto = document.getElementById('mensagem').value;
            if (texto.trim() === "") return;

            fetch('chat_enviar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'texto=' + encodeURIComponent(texto)
            }).then(() => {
                document.getElementById('mensagem').value = "";
            });
        });

        document.addEventListener('DOMContentLoaded', buscarMensagens);
    </script>
</body>
</html>
