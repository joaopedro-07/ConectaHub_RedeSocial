<?php
include("../conexao.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["usuario"])) {
    $usuario = $_SESSION["usuario"];
    $texto = trim($_POST["texto"]);

    if ($texto !== "") {
        $stmt = $conn->prepare("INSERT INTO mensagens (usuario, texto) VALUES (?, ?)");
        $stmt->bind_param("ss", $usuario, $texto);
        $stmt->execute();
    }
}
?>
