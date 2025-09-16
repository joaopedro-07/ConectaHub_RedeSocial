<?php
include("../conexao.php");

$last_id = isset($_GET["last_id"]) ? (int)$_GET["last_id"] : 0;

while (true) {
    $stmt = $conn->prepare("SELECT * FROM mensagens WHERE id > ? ORDER BY id ASC");
    $stmt->bind_param("i", $last_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mensagens = [];
        while ($row = $result->fetch_assoc()) {
            $mensagens[] = $row;
        }
        echo json_encode($mensagens);
        break;
    }

    sleep(2); // espera 2 segundos antes de tentar de novo
}
?>
