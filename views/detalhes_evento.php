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

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/detalhes_evento.css">
</head>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<body class="detalhes-page">

<div class="detalhes-container">
    
    <div class="evento-imagem"
        style="background-image: url('https://images.unsplash.com/photo-1501281668745-f7f57925c3b4');">
    </div>

    <!-- TÍTULO -->
    <h1 class="evento-titulo"><?= $evento['nome'] ?></h1>

    <!-- INFO -->
    <p class="evento-info">📅 <?= $evento['data_evento'] ?> às <?= $evento['hora_inicio'] ?></p>

    <div id="mapa-evento"></div>

    <!-- DESCRIÇÃO -->
    <p class="evento-descricao">
        <?= nl2br($evento['detalhes']) ?>
    </p>

    <!-- AÇÕES -->
    <div class="evento-acoes">

        <?php if ($evento['url_compra']): ?>
            <a href="<?= $evento['url_compra'] ?>" target="_blank" class="btn btn-ingresso">
                Ingresso
            </a>
        <?php endif; ?>

        
        <button class="btn btn-ja-comprei">
            Já comprei
        </button>

        <button class="btn btn-detalhe" onclick="history.back()">
            Voltar
        </button>

    </div>

</div>

<script>

let lat = <?= $evento['latitude_local'] ?>;
let lng = <?= $evento['longitude_local'] ?>;

let map = L.map('mapa-evento').setView([lat, lng], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

L.marker([lat, lng])
    .addTo(map)
    .openPopup();

</script>

</body>
</html>