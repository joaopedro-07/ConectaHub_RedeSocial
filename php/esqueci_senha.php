<?php
include("../conexao.php");
session_start();

$mensagem = "";
$sucesso = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email_usuario = $_POST["email_usuario"];
    $nova_senha = $_POST["nova_senha"];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email_usuario = ?");
    $stmt->bind_param("s", $email_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $novaSenhaHash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE usuarios SET senha_usuario = ? WHERE email_usuario = ?");
        $update->bind_param("ss", $novaSenhaHash, $email_usuario);

        if ($update->execute()) {
            $sucesso = true;
        } else {
            $mensagem = "Erro ao redefinir senha. Tente novamente.";
        }
    } else {
        $mensagem = "Email não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="../styles/index.css">
</head>
<body>
    <div class="container">
        <div class="content-area">
            <h2>Redefinir Senha</h2>
            <form method="POST">
                <input type="email" name="email_usuario" placeholder="Digite seu email" required>
                <input type="password" name="nova_senha" placeholder="Nova senha" required>
                <button type="submit">Redefinir</button>
            </form>
            <a class="link" href="../index.php">Voltar ao Login</a>
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
                title: 'Senha redefinida com sucesso!',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>
    <?php endif; ?>
</body>
</html>
