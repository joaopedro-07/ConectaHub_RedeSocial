<?php
include("../conexao.php");

$last_id = isset($_GET["last_id"]) ? (int)$_GET["last_id"] : 0;

$stmt = $conn->prepare("SELECT id, usuario, texto FROM mensagens WHERE id > ? ORDER BY id ASC");
$stmt->bind_param("i", $last_id);
$stmt->execute();
$result = $stmt->get_result();

$mensagens = [];
while ($row = $result->fetch_assoc()) {
    $mensagens[] = $row;
}

header("Content-Type: application/json; charset=utf-8");
echo json_encode($mensagens);
?>
