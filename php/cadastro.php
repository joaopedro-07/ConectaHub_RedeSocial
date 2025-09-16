<?php
include(__DIR__ . "/../conexao.php");

$mensagem = "";
$sucesso = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario  = $_POST["nome_usuario"];
    $email_usuario = $_POST["email_usuario"];
    $senha_usuario = password_hash($_POST["senha_usuario"], PASSWORD_DEFAULT);

    // Upload da foto
    $foto_usuario = null;
    if (isset($_FILES["foto_usuario"]) && $_FILES["foto_usuario"]["error"] == 0) {
        $extensao = pathinfo($_FILES["foto_usuario"]["name"], PATHINFO_EXTENSION);
        $permitidos = ["jpg", "jpeg", "png", "gif"];

        if (in_array(strtolower($extensao), $permitidos)) {
            $novo_nome = uniqid() . "." . $extensao;
            $caminho = "../uploads/" . $novo_nome;

            if (!is_dir("../uploads")) {
                mkdir("../uploads", 0777, true);
            }

            if (move_uploaded_file($_FILES["foto_usuario"]["tmp_name"], $caminho)) {
                $foto_usuario = $novo_nome;
            } else {
                $mensagem = "Erro ao salvar a foto.";
            }
        } else {
            $mensagem = "Formato de imagem inválido. Use JPG, PNG ou GIF.";
        }
    }

    if (empty($mensagem)) {
        $verifica = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email_usuario = ?");
        $verifica->bind_param("s", $email_usuario);
        $verifica->execute();
        $resultado = $verifica->get_result();

        if ($resultado->num_rows > 0) {
            $mensagem = "Este e-mail já está cadastrado.";
        } else {
            $stmt = $conn->prepare("INSERT INTO usuarios (nome_usuario, email_usuario, senha_usuario, foto_usuario) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nome_usuario, $email_usuario, $senha_usuario, $foto_usuario);

            if ($stmt->execute()) {
                $sucesso = true;
            } else {
                $mensagem = 'Erro ao cadastrar: ' . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="../styles/cadastro.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 15px 0;
        }
        .upload-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: pointer;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .upload-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        input[type="file"] {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>CADASTRO</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nome_usuario" placeholder="Nome" required>
            <input type="email" name="email_usuario" placeholder="Email" required>
            <input type="password" name="senha_usuario" placeholder="Senha" required>

            <!-- Upload de foto -->
            <div class="upload-container">
                <label for="foto_usuario" class="upload-preview" id="preview">
                    <span>+</span>
                </label>
                <input type="file" name="foto_usuario" id="foto_usuario" accept="image/*">
                <p style="font-size: 12px; color: #777;">Clique para escolher sua foto</p>
            </div>

            <button type="submit">Cadastrar</button>
        </form>
        <a class="link" href="../index.php">Já tem conta? Faça login</a>
    </div>

    <script>
        const inputFile = document.getElementById("foto_usuario");
        const preview = document.getElementById("preview");

        inputFile.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = "<span>+</span>";
            }
        });
    </script>

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
