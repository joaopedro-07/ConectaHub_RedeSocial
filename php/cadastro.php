<?php
include(__DIR__ . "/../conexao.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario  = $_POST["nome_usuario"];
    $email_usuario = $_POST["email_usuario"];
    $senha_usuario = password_hash($_POST["senha_usuario"], PASSWORD_DEFAULT); // senha criptografada

    $sql = "INSERT INTO usuarios (nome_usuario, email_usuario, senha_usuario) VALUES ('$nome_usuario', '$email_usuario', '$senha_usuario')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Cadastro realizado com sucesso! <a href='../index.php'>Fazer login</a>";
    } else {
        echo "Erro: " . $conn->error;
    }
}
?>

<form method="POST">
    Nome: <input type="text" name="nome_usuario" required><br>
    Email: <input type="email" name="email_usuario" required><br>
    Senha: <input type="password" name="senha_usuario" required><br>
    <button type="submit">Cadastrar</button>
</form>
