<?php
session_start();
include("../config/config.php");

// só admin entra
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// busca produtores pendentes
$sql = "SELECT u.id_usuario, u.email, p.cnpj, p.razao_social
        FROM usuario u
        JOIN usuario_produtor p ON u.id_usuario = p.id_usuario
        WHERE u.tipo_usuario = 'produtor' AND u.status_aprov = 0";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - ABase</title>

    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body class="admin-page">

<div class="admin-container">

    <div class="topo">
        <div class="topo-header">
            <button onclick="window.location.href='../index.php'" class="btn-voltar">←</button>
            <h2>Painel do Administrador</h2>
        </div>
    </div>

    <div class="lista-produtores">

        <?php if ($result->num_rows > 0): ?>
            <?php while ($prod = $result->fetch_assoc()): ?>

                <div class="produtor-card">
                    <h3><?= $prod['razao_social'] ?></h3>
                    <p>Email: <?= $prod['email'] ?></p>
                    <p>CNPJ: <?= $prod['cnpj'] ?></p>

                    <div class="acoes">
                        <button class="btn btn-ver"
                            onclick="verDados(<?= $prod['id_usuario'] ?>)">
                            Verificar dados
                        </button>

                        <button class="btn btn-aprovar"
                            onclick="aprovar(<?= $prod['id_usuario'] ?>)">
                            Aprovar
                        </button>

                        <button class="btn btn-recusar"
                            onclick="recusar(<?= $prod['id_usuario'] ?>)">
                            Recusar
                        </button>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum produtor pendente.</p>
        <?php endif; ?>

    </div>

</div>

<script>

function aprovar(id) {
    fetch(`../controllers/aprovar_produtor.php?id=${id}`)
        .then(() => location.reload());
}

function recusar(id) {
    if (confirm("Tem certeza que deseja recusar?")) {
        fetch(`../controllers/recusar_produtor.php?id=${id}`)
            .then(() => location.reload());
    }
}

function verDados(id) {
    window.location.href = `detalhes_produtor.php?id=${id}`;
}

</script>

</body>
</html>