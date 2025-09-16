<?php
include("./conexao.php");
session_start();

$mensagem = ""; // variável para armazenar erros

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email_usuario = $_POST["email_usuario"];
    $senha_usuario = $_POST["senha_usuario"];

    $sql = "SELECT * FROM usuarios WHERE email_usuario = '$email_usuario'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha_usuario, $usuario["senha_usuario"])) {
            $_SESSION["usuario"] = $usuario["nome_usuario"];
            header("Location: ./php/feed.php");
            exit;
        } else {
            $mensagem = "Senha incorreta!";
        }
    } 
}
?>

<form method="POST">
    Email: <input type="email" name="email_usuario" required><br>
    Senha: <input type="password" name="senha_usuario" required><br>
    <button type="submit">Entrar</button>
</form>

<p>É novo? <a href="./php/cadastro.php"><button type="button">Cadastre-se</button></a></p>

<?php if (!empty($mensagem)): ?>
    <p style="color:red;"><?php echo $mensagem; ?></p>
<?php endif; ?>
