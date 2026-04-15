<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Mapa - ABase</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body class="mapa-page">

<div class="map-container">

    <div class="topo">
<div class="topo-header">
    <button onclick="window.location.href='perfil.php'" class="btn-voltar" title="Voltar">
    ←
</button>

    <h2>Eventos Próximos</h2>
</div>
    <div class="filtro">
        <div class="input-local">
            <input type="text" id="localizacao" placeholder="Digite sua localização">
            <div id="sugestoes"></div>
        </div>

        <input type="number" id="raio" placeholder="km" value="10">
        <button onclick="buscarLocalizacao()">Buscar</button>
    </div>

    <button onclick="toggleMapa()" class="btn-mapa">🗺 Mostrar mapa</button>

</div>

    <!-- LISTA -->
    <div id="lista-eventos"></div>

    <!-- MAPA -->
    <div id="map" class="hidden"></div>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
let map;
let userLat;
let userLong;
let markers = [];
let userMarker;
let mapaVisivel = false;
let timeout = null;

// inicializa depois que carregar DOM
window.onload = function() {

    // pega localização inicial
    navigator.geolocation.getCurrentPosition(function(position) {
        userLat = position.coords.latitude;
        userLong = position.coords.longitude;

        carregarEventos();
    });

    // autocomplete
    document.getElementById("localizacao").addEventListener("input", function() {

        clearTimeout(timeout);

        let query = this.value;

        if (query.length < 3) {
            document.getElementById("sugestoes").innerHTML = "";
            return;
        }

        timeout = setTimeout(() => {

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
            .then(res => res.json())
            .then(data => {

                let sugestoes = document.getElementById("sugestoes");
                sugestoes.innerHTML = "";

                data.slice(0, 5).forEach(local => {

                    let item = document.createElement("div");
                    let nomeCurto = local.display_name.split(",").slice(0, 3).join(",");

                    item.className = "sugestao";
                    item.innerText = nomeCurto;

                    item.onclick = () => {
                        document.getElementById("localizacao").value = nomeCurto;

                        userLat = parseFloat(local.lat);
                        userLong = parseFloat(local.lon);

                        sugestoes.innerHTML = "";

                        carregarEventos();
                    };

                    sugestoes.appendChild(item);
                });

            });

        }, 400);
    });
};

// mostrar / esconder mapa
function toggleMapa() {
    let mapDiv = document.getElementById("map");

    mapaVisivel = !mapaVisivel;

    if (mapaVisivel) {
        mapDiv.classList.remove("hidden");

        if (!map) {
            map = L.map('map').setView([userLat, userLong], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);
        }

        atualizarMapa();
    } else {
        mapDiv.classList.add("hidden");
    }
}

// buscar pelo botão
function buscarLocalizacao() {
    let lugar = document.getElementById("localizacao").value;

    if (!lugar) {
        carregarEventos();
        return;
    }

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${lugar}`)
    .then(res => res.json())
    .then(data => {
        if (data.length > 0) {
            userLat = parseFloat(data[0].lat);
            userLong = parseFloat(data[0].lon);

            carregarEventos();
        } else {
            alert("Localização não encontrada");
        }
    });
}

// limpar markers
function limparMapa() {
    if (!map) return;

    markers.forEach(m => map.removeLayer(m));
    markers = [];
}

// atualizar mapa
function atualizarMapa() {
    if (!map) return;

    limparMapa();

    map.setView([userLat, userLong], 13);

    if (userMarker) {
        map.removeLayer(userMarker);
    }

    userMarker = L.marker([userLat, userLong]).addTo(map)
        .bindPopup('Você está aqui!')
        .openPopup();

    fetchEventos(true);
}

// carregar lista
function carregarEventos() {
    fetchEventos(false);
}

// buscar eventos
function fetchEventos(atualizarMapaFlag) {

    let raio = document.getElementById("raio").value;

    fetch(`../controllers/buscar_eventos.php?lat=${userLat}&long=${userLong}&raio=${raio}`)
    .then(res => res.json())
    .then(data => {

        let lista = document.getElementById("lista-eventos");
        lista.innerHTML = "";

        data.forEach(evento => {

            lista.innerHTML += `
                <div class="evento-card">
                    <h3>${evento.nome}</h3>
                    <p>📏 ${parseFloat(evento.distancia).toFixed(2)} km</p>

                    <div class="acoes">
                        <a href="${evento.url_compra}" target="_blank">🎟 Comprar</a>
                        <a href="../controllers/checkin.php?id_evento=${evento.id_evento}&lat=${userLat}&long=${userLong}">📍 Check-in</a>
                        <a href="../views/avaliar.php?id_evento=${evento.id_evento}">⭐ Avaliar</a>
                    </div>
                </div>
            `;
        });

        if (data.length === 0) {
            lista.innerHTML = "<p>Nenhum evento encontrado.</p>";
        }

        if (mapaVisivel && atualizarMapaFlag) {
            data.forEach(evento => {
                let marker = L.marker([evento.latitude_local, evento.longitude_local]).addTo(map)
                    .bindPopup(`<b>${evento.nome}</b>`);

                markers.push(marker);
            });
        }

    });
}
</script>

</body>
</html>