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
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f0f0;
            padding: 20px;
        }
        #chat {
            background: white;
            border: 1px solid #ccc;
            padding: 20px;
            height: 400px;
            overflow-y: scroll;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        #mensagem {
            width: 80%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        #enviar {
            padding: 10px 20px;
            border: none;
            background-color: #667eea;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Bem-vindo ao chat, <?php echo $_SESSION["nome_usuario"]; ?>!</h2>
    <div id="chat"></div>
    <input type="text" id="mensagem" placeholder="Digite sua mensagem...">
    <button id="enviar">Enviar</button>

    <script>
        let lastId = 0;

        function buscarMensagens() {
            fetch('chat_receber.php?last_id=' + lastId)
                .then(res => res.json())
                .then(data => {
                    data.forEach(msg => {
                        lastId = msg.id;
                        const div = document.createElement('div');
                        div.innerHTML = `<strong>${msg.usuario}</strong>: ${msg.texto}`;
                        document.getElementById('chat').appendChild(div);
                        document.getElementById('chat').scrollTop = document.getElementById('chat').scrollHeight;
                    });
                    buscarMensagens();
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
