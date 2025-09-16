<?php
include(__DIR__ . "/../conexao.php");

$mensagem = "";
$sucesso = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario  = $_POST["nome_usuario"];
    $email_usuario = $_POST["email_usuario"];
    $senha_usuario = password_hash($_POST["senha_usuario"], PASSWORD_DEFAULT);

    $verifica = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email_usuario = ?");
    $verifica->bind_param("s", $email_usuario);
    $verifica->execute();
    $resultado = $verifica->get_result();

    if ($resultado->num_rows > 0) {
        $mensagem = "Este e-mail já está cadastrado.";
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (nome_usuario, email_usuario, senha_usuario) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome_usuario, $email_usuario, $senha_usuario);

        if ($stmt->execute()) {
            $sucesso = true;
        } else {
            $mensagem = 'Erro ao cadastrar: ' . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(to right, #43cea2, #185a9d);
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
            background-color: #43cea2;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }
        button:hover {
            background-color: #2bb673;
        }
        .link {
            margin-top: 20px;
            display: block;
            color: #185a9d;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro</h2>
        <form method="POST">
            <input type="text" name="nome_usuario" placeholder="Nome" required>
            <input type="email" name="email_usuario" placeholder="Email" required>
            <input type="password" name="senha_usuario" placeholder="Senha" required>
            <button type="submit">Cadastrar</button>
        </form>
        <a class="link" href="../index.php">Já tem conta? Faça login</a>
    </div>

    <?php if (!empty($mensagem)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: '<?php echo $mensagem; ?>'
                });
            });
        </script>
    <?php elseif ($sucesso): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Cadastro realizado com sucesso!',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = '../index.php';
                });
            });
        </script>
    <?php endif; ?>
</body>
</html>
