<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ABase - Mapa</title>

    <!-- CSS do projeto -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body>

<header>
    ABase - Onde todo Rolê começa!
</header>

<div class="container">

    <h2>Eventos Próximos</h2>

    <label>Raio (km):</label>
    <input type="number" id="raio" value="50">

    <button onclick="carregarMapa()">Buscar</button>

</div>

<div id="map"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

function carregarMapa() {

    let raio = document.getElementById("raio").value;

    navigator.geolocation.getCurrentPosition(function(position) {

        let lat = position.coords.latitude;
        let long = position.coords.longitude;

        // remove mapa antigo
        if (window.mapa) {
            window.mapa.remove();
        }

        var map = L.map('map').setView([lat, long], 13);
        window.mapa = map;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // marcador do usuário
        L.marker([lat, long]).addTo(map)
            .bindPopup("📍 Você está aqui!")
            .openPopup();

        // buscar eventos
        fetch(`../controllers/buscar_eventos.php?lat=${lat}&long=${long}&raio=${raio}`)
        .then(response => response.json())
        .then(data => {

            data.forEach(evento => {

                L.marker([evento.latitude_local, evento.longitude_local])
                    .addTo(map)
                    .bindPopup(
                        "<div style='color:black'>" +
                        "<b>" + evento.nome + "</b><br>" +
                        "📍 " + parseFloat(evento.distancia).toFixed(2) + " km<br><br>" +
                        "<a href='" + evento.url_compra + "' target='_blank'>Comprar</a><br><br>" +
                        "<button onclick='fazerCheckin(" + evento.id_evento + ")'>Check-in</button><br><br>" +
                        "<button onclick='avaliarEvento(" + evento.id_evento + ")'>Avaliar</button>" +
                        "</div>"
                    );

            });

        });

    }, function() {
        alert("Permita a localização para usar o mapa!");
    });

}

// CHECK-IN
function fazerCheckin(id_evento) {

    navigator.geolocation.getCurrentPosition(function(position) {

        let lat = position.coords.latitude;
        let long = position.coords.longitude;

        fetch(`../controllers/checkin.php?id_evento=${id_evento}&lat=${lat}&long=${long}`)
        .then(response => response.text())
        .then(msg => alert(msg));

    });

}

// AVALIAÇÃO
function avaliarEvento(id_evento) {

    let nota = prompt("Dê uma nota de 1 a 5:");
    let comentario = prompt("Comentário:");

    fetch(`../controllers/avaliar.php?id_evento=${id_evento}&nota=${nota}&comentario=${comentario}`)
    .then(response => response.text())
    .then(msg => alert(msg));

}

</script>

</body>
</html>