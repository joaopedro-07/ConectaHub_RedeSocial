<?php
include("./conexao.php");
session_start();

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email_usuario = $_POST["email_usuario"];
    $senha_usuario = $_POST["senha_usuario"];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email_usuario = ?");
    $stmt->bind_param("s", $email_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha_usuario, $usuario["senha_usuario"])) {
            $_SESSION["usuario"] = $usuario["nome_usuario"];
            header("Location: ./php/feed.php");
            exit;
        } else {
            $mensagem = "Email ou senha incorretos!";
        }
    } else {
        $mensagem = "Email ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            background: linear-gradient(to right, #667eea, #764ba2);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            background-color: #667eea;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }
        button:hover {
            background-color: #5a67d8;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .link {
            margin-top: 20px;
            display: block;
            color: #764ba2;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Entrar</h2>
        <form method="POST">
            <input type="email" name="email_usuario" placeholder="Email" required>
            <input type="password" name="senha_usuario" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <a class="link" href="./php/cadastro.php">É novo por aqui? Cadastre-se</a>
        <?php if (!empty($mensagem)): ?>
            <p class="error"><?php echo $mensagem; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
