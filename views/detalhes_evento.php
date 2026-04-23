<?php
include("../config/config.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Evento inválido";
    exit();
}

$sql = "SELECT * FROM evento WHERE id_evento = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Evento não encontrado";
    exit();
}

$evento = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Evento</title>

    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/components.css">
</head>

<body class="perfil-page">

<div class="container">

    <h1><?= $evento['nome'] ?></h1>

    <p><b>📅 Data:</b> <?= $evento['data_evento'] ?></p>
    <p><b>⏰ Hora:</b> <?= $evento['hora_inicio'] ?></p>

    <p><b>📍 Localização:</b><br>
        Lat: <?= $evento['latitude_local'] ?><br>
        Long: <?= $evento['longitude_local'] ?>
    </p>

    <p><b>Detalhes:</b><br>
        <?= nl2br($evento['detalhes']) ?>
    </p>

    <?php if ($evento['url_compra']): ?>
        <a href="<?= $evento['url_compra'] ?>" target="_blank">🎟 Comprar Ingresso</a>
    <?php endif; ?>

    <br><br>
    <button onclick="history.back()">⬅ Voltar</button>

</div>

</body>
</html>