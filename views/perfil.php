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

if ($result->num_rows == 0) {
    echo "Usuário não encontrado.";
    exit();
}

$user = $result->fetch_assoc();

$sqlEventos = "
SELECT e.*, ue.checkin_realizado
FROM usuario_evento ue
JOIN evento e ON e.id_evento = ue.id_evento
WHERE ue.id_usuario = $id
";

$resultEventos = $conn->query($sqlEventos);
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

    <h1>👤 Meu Perfil</h1>

    <div class="info">
        <p><b>Email:</b> <?= $user['email'] ?></p>
        <p><b>Tipo:</b> <?= $user['tipo_usuario'] ?></p>
        <p><b>Telefone:</b> <?= $user['telefone'] ?? 'Não informado' ?></p>
        <p><b>Data de Nascimento:</b> <?= $user['data_nascimento'] ?? 'Não informado' ?></p>
        <p><b>Cadastro:</b> <?= $user['data_cadastro'] ?></p>
    </div>

    <h2>🎟 Meus Eventos</h2>

    <?php while ($evento = $resultEventos->fetch_assoc()): ?>

        <div class="evento-card">

            <h3><?= $evento['nome'] ?></h3>

            <div class="acoes">

                <a href="<?= $evento['url_compra'] ?>" target="_blank">
                    🎟 Ingresso
                </a>

                <?php if (!$evento['checkin_realizado']): ?>
                    <button onclick="fazerCheckin(<?= $evento['id_evento'] ?>)">
                        📍 Check-in
                    </button>
                <?php else: ?>
                    <a href="../views/avaliar.php?id_evento=<?= $evento['id_evento'] ?>">
                        ⭐ Avaliar
                    </a>
                <?php endif; ?>

                <button onclick="removerEvento(<?= $evento['id_evento'] ?>, '<?= $evento['data_evento'] ?>')">
                    🗑
                </button>

            </div>

        </div>

    <?php endwhile; ?>

    <button onclick="window.location.href='mapa.php'">📍 Ver Eventos</button>

    <?php if ($user['tipo_usuario'] == 'produtor'): ?>
    <?php if ($user['status_aprov'] == 1): ?>
        <button onclick="window.location.href='criar_evento.php'">
            ➕ Criar Evento
        </button>
    <?php else: ?>
        <p style="color: orange;">⏳ Aguardando aprovação</p>
    <?php endif; ?>
<?php endif; ?>

    <button onclick="window.location.href='../index.php'" class="logout">🚪 Sair</button>

</div>

<!-- MODAL -->
<div id="modal" class="modal hidden">
    <div class="modal-content">

        <p id="modal-text"></p>

        <div class="modal-actions">
            <button id="modal-confirm" class="btn-confirm">Confirmar</button>
            <button onclick="fecharModal()" class="btn-cancel">Cancelar</button>
        </div>

    </div>
</div>

<script>

let acaoConfirmar = null;

// garante que o DOM carregou
window.onload = function() {

    document.getElementById("modal-confirm").onclick = function() {
        if (acaoConfirmar) {
            acaoConfirmar();
        }
        fecharModal();
    };

};

// CHECK-IN
function fazerCheckin(id_evento) {
    fetch(`../controllers/checkin.php?id_evento=${id_evento}`)
    .then(() => {

        abrirModal("✅ Check-in realizado!");

        setTimeout(() => {
            location.reload();
        }, 1500);

    });
}

// REMOVER EVENTO
function removerEvento(id_evento, data_evento) {

    let hoje = new Date();
    let dataEvento = new Date(data_evento);

    let mensagem = "Tem certeza que deseja remover este evento?";

    if (dataEvento > hoje) {
        mensagem = "⚠️ Esse evento ainda não ocorreu!\nDeseja remover mesmo assim?";
    }

    abrirModal(mensagem, () => {

        fetch(`../controllers/remover_evento_usuario.php?id_evento=${id_evento}`)
        .then(() => {
            location.reload();
        });

    });
}

// MODAL
function abrirModal(texto, callbackConfirmar = null) {
    document.getElementById("modal-text").innerText = texto;
    document.getElementById("modal").classList.remove("hidden");

    acaoConfirmar = callbackConfirmar;

    document.getElementById("modal-confirm").style.display =
        callbackConfirmar ? "block" : "none";
}

function fecharModal() {
    document.getElementById("modal").classList.add("hidden");
    acaoConfirmar = null;
}

</script>

</body>
</html>