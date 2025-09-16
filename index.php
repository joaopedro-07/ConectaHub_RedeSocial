<?php
include("./conexao.php");
session_start();

$mensagem = "";
$sucesso = false;

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
            // Salva informações principais na sessão
            $_SESSION["id_usuario"] = $usuario["id_usuario"];
            $_SESSION["nome_usuario"] = $usuario["nome_usuario"];

            $sucesso = true;
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="./styles/index.css">
</head>
<body>
    <div class="container">
        <div class="content-area">
            <h2>ENTRAR</h2>
            <form method="POST">
                <input type="email" name="email_usuario" placeholder="Email" required>
                <input type="password" name="senha_usuario" placeholder="Senha" required>
                <button type="submit">Entrar</button>
            </form>
            <a class="link" href="./php/cadastro.php">É novo por aqui? Cadastre-se</a>
        </div>
    </div>

    <?php if (!empty($mensagem)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo $mensagem; ?>'
            });
        </script>
    <?php elseif ($sucesso): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Login realizado com sucesso!',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = './php/feed.php';
            });
        </script>
    <?php endif; ?>
</body>
</html>
