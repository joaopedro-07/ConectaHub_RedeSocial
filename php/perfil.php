<?php
include("../conexao.php");
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../index.php");
    exit();
}

$id = $_SESSION["id_usuario"];

$stmt = $conn->prepare("SELECT nome_usuario, email_usuario, foto_usuario FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/perfil.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <title>Perfil</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <nav>
            <img src="../img/conectahub_logoFundoAzul.png" alt="Logo">
            <ul>
                <li><a href="../php/feed.php">Feed</a></li>
                <li><a href="../php/chat.php">Mensagens</a></li>
                <li><a href="../php/perfil.php">Perfil</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="wrapper">
        <div id="container">
            <h1>Seja bem-vindo, <?php echo htmlspecialchars($usuario['nome_usuario']); ?></h1>
            <p>Aqui você pode visualizar e editar suas informações pessoais.</p>

            <div id="user-info">
                <img src="../uploads/<?php echo $usuario['foto_usuario'] ? $usuario['foto_usuario'] : 'default.png'; ?>" alt="Foto de Perfil">
                <div class="info-text">
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome_usuario']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email_usuario']); ?></p>
                </div>
            </div>

            <button id="edit-profile">Editar Perfil</button>
            <button id="logout">Sair</button>
        </div>

        <div id="edit-panel" class="hidden">
            <h2>Editar Perfil</h2>

            <img id="preview" src="../uploads/<?php echo $usuario['foto_usuario'] ? $usuario['foto_usuario'] : 'default.png'; ?>" alt="Foto de Perfil">

            <form method="POST" action="salvar_perfil.php" enctype="multipart/form-data">
                <label>Alterar Foto:</label>
                <input type="file" name="foto_usuario" id="foto_usuario" accept="image/*">

                <label>Nome:</label>
                <input type="text" name="nome_usuario" value="<?php echo htmlspecialchars($usuario['nome_usuario']); ?>">

                <button type="submit" id="edit-profile">Salvar</button>
            </form>
        </div>
    </div>

    <script>
        // Alternar painel de edição
        const editBtn = document.getElementById('edit-profile');
        const editPanel = document.getElementById('edit-panel');
        const fileInput = document.getElementById('foto_usuario');
        const preview = document.getElementById('preview');

        editBtn.addEventListener('click', () => {
            editPanel.classList.toggle('hidden');
        });

        // Preview da foto
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(file);
            }
        });

        // Logout com SweetAlert2
        const logoutBtn = document.getElementById('logout');
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você será desconectado da sua conta!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, sair',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../php/logout.php';
                }
            });
        });
    </script>
</body>
</html>
