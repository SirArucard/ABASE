<?php
session_start();
include("../config/config.php");

// segurança
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// buscar produtores
$sql = "
SELECT u.id_usuario, u.email, p.cnpj, p.razao_social, u.status_aprov
FROM usuario u
JOIN usuario_produtor p ON u.id_usuario = p.id_usuario
WHERE u.tipo_usuario = 'produtor'
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Painel Admin</title>

    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/components.css">
</head>

<body class="perfil-page">

<div class="container">

    <h1>🛠 Painel Admin</h1>

    <?php while ($p = $result->fetch_assoc()): ?>

        <div class="evento-card">
            <h3><?= $p['razao_social'] ?></h3>
            <p><?= $p['email'] ?></p>
            <p><?= $p['cnpj'] ?></p>

            <div class="acoes">

                <?php if ($p['status_aprov'] == 0): ?>

                    <button onclick="aprovar(<?= $p['id_usuario'] ?>)">✅ Aprovar</button>
                    <button onclick="recusar(<?= $p['id_usuario'] ?>)">❌ Recusar</button>

                <?php else: ?>
                    <span style="color: green;">✔ Aprovado</span>
                <?php endif; ?>

            </div>
        </div>

    <?php endwhile; ?>

</div>

<script>
function aprovar(id) {
    fetch(`../controllers/aprovar_produtor.php?id=${id}`)
    .then(() => location.reload());
}

function recusar(id) {
    fetch(`../controllers/recusar_produtor.php?id=${id}`)
    .then(() => location.reload());
}
</script>

</body>
</html>