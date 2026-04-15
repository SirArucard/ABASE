<?php
session_start();
include("../config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_SESSION['id_usuario'];

// consulta usuário
$sql = "SELECT * FROM usuario WHERE id_usuario = $id";
$result = $conn->query($sql);

// valida se encontrou
if ($result->num_rows == 0) {
    echo "Usuário não encontrado.";
    exit();
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Perfil - ABase</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="perfil-page">

<div class="container">

    <h1>👤 Meu Perfil</h1>

    <div class="info">
        <p><b>Email:</b> <?php echo $user['email']; ?></p>
        <p><b>Tipo:</b> <?php echo $user['tipo_usuario']; ?></p>
        <p><b>Telefone:</b> <?php echo $user['telefone'] ?? 'Não informado'; ?></p>
        <p><b>Data de Nascimento:</b> <?php echo $user['data_nascimento'] ?? 'Não informado'; ?></p>
        <p><b>Cadastro:</b> <?php echo $user['data_cadastro']; ?></p>
    </div>

    <!-- BOTÕES -->

    <button onclick="window.location.href='mapa.php'">📍 Ver Eventos</button>

    <?php if ($user['tipo_usuario'] == 'produtor'): ?>
        <?php if ($user['status_aprov'] == 1): ?>
            <button onclick="window.location.href='criar_evento.php'">➕ Criar Evento</button>
        <?php else: ?>
            <p style="color: orange;">⏳ Aguardando aprovação do admin</p>
        <?php endif; ?>
    <?php endif; ?>

    <button onclick="window.location.href='../controllers/logout.php'" class="logout">🚪 Sair</button>

</div>

</body>
</html>