<?php
session_start();
include("../config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_SESSION['id_usuario'];

$sql = "SELECT * FROM usuario WHERE id_usuario = $id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

/* EVENTOS DO USUÁRIO */
$sqlEventos = "
SELECT e.*, ue.checkin_realizado
FROM usuario_evento ue
JOIN evento e ON e.id_evento = ue.id_evento
WHERE ue.id_usuario = $id
";
$resultEventos = $conn->query($sqlEventos);

/* EVENTOS DO PRODUTOR */
$resultProdutor = null;

if ($user['tipo_usuario'] == 'produtor') {
    $sqlProdutor = "SELECT * FROM evento WHERE id_produtor = $id ORDER BY id_evento DESC";
    $resultProdutor = $conn->query($sqlProdutor);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Perfil - ABase</title>

    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link rel="stylesheet" href="../assets/css/mapa.css">
</head>

<body class="perfil-page">

<div class="container">

    <h1>Meu Perfil</h1>

    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'evento_editado'): ?>
        <p style="color:#22c55e; margin-bottom:10px;">Evento atualizado com sucesso!</p>
    <?php endif; ?>

    <div class="info">
        <p><b>Email:</b> <?= $user['email'] ?></p>
        <p><b>Tipo:</b> <?= $user['tipo_usuario'] ?></p>
        <p><b>Telefone:</b> <?= $user['telefone'] ?? 'Não informado' ?></p>
        <p><b>Nascimento:</b> <?= $user['data_nascimento'] ?? 'Não informado' ?></p>
    </div>

    <h2>Meus Eventos</h2>

    <div class="grid-eventos">
    <?php while ($evento = $resultEventos->fetch_assoc()): ?>
        <div class="evento-card">

            <h3><?= $evento['nome'] ?></h3>

            <div class="evento-acoes">

                <a href="<?= $evento['url_compra'] ?>" target="_blank" class="btn btn-success">
                    Ingresso
                </a>

                <?php if (!$evento['checkin_realizado']): ?>
                    <button class="btn btn-warning" onclick="fazerCheckin(<?= $evento['id_evento'] ?>)">
                        Check-in
                    </button>
                <?php else: ?>
                    <a href="../views/avaliar.php?id_evento=<?= $evento['id_evento'] ?>" class="btn btn-primary">
                        Avaliar
                    </a>
                <?php endif; ?>

                <button class="btn btn-primary" onclick="removerEvento(<?= $evento['id_evento'] ?>)">
                    Remover
                </button>

            </div>

        </div>
    <?php endwhile; ?>
    </div>

    <?php if ($user['tipo_usuario'] == 'produtor'): ?>
        <h2>Meus Eventos Criados</h2>

        <div class="grid-eventos">
        <?php if ($resultProdutor && $resultProdutor->num_rows > 0): ?>
            <?php while ($evento = $resultProdutor->fetch_assoc()): ?>
                <div class="evento-card">

                    <h3><?= $evento['nome'] ?></h3>
                    <p><?= $evento['detalhes'] ?></p>

                    <div class="evento-acoes">

                        <a href="<?= $evento['url_compra'] ?>" target="_blank" class="btn btn-success">
                            Ver Ingresso
                        </a>

                        <button class="btn btn-primary" onclick="editarEvento(<?= $evento['id_evento'] ?>)">
                            Editar
                        </button>

                    </div>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Você ainda não criou eventos.</p>
        <?php endif; ?>
        </div>
    <?php endif; ?>

    <button onclick="window.location.href='mapa.php'" class="btn btn-primary">Ver Eventos</button>

    <?php if ($user['tipo_usuario'] == 'produtor' && $user['status_aprov'] == 1): ?>
        <button onclick="window.location.href='criar_evento.php'" class="btn btn-success">
            Criar Evento
        </button>
    <?php endif; ?>

    <button onclick="window.location.href='../index.php'" class="btn btn-warning">
        Sair
    </button>

</div>

<script>
function fazerCheckin(id) {
    fetch(`../controllers/checkin.php?id_evento=${id}`)
    .then(() => location.reload());
}

function removerEvento(id) {
    fetch(`../controllers/remover_evento_usuario.php?id_evento=${id}`)
    .then(() => location.reload());
}

function editarEvento(id) {
    window.location.href = `editar_evento.php?id=${id}`;
}
</script>

</body>
</html>