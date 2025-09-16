<?php
$host = "localhost";
$user = "root"; 
$pass = "";     // se você não colocou senha no MySQL do XAMPP, deixa vazio
$db   = "conecta_tech"; // esse nome tem que ser igual ao do banco que você criou

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
