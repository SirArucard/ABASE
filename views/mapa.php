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

    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/mapa.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body class="mapa-page">

<div class="map-container">

    <div class="topo">

        <div class="topo-header">
            <button onclick="window.location.href='perfil.php'" class="btn-voltar">←</button>
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

    <!-- MODAL -->
    <div id="modal" class="modal hidden">
        <div class="modal-content">
            <p id="modal-text"></p>
            <div class="modal-actions">
                <button onclick="fecharModal()">OK</button>
            </div>
        </div>
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

window.onload = function() {

    navigator.geolocation.getCurrentPosition(function(position) {
        userLat = position.coords.latitude;
        userLong = position.coords.longitude;
        carregarEventos();
    });

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

// MODAL
function abrirModal(texto) {
    const modal = document.getElementById("modal");
    const textoEl = document.getElementById("modal-text");

    if (!modal || !textoEl) {
        console.error("Modal não encontrado");
        return;
    }

    textoEl.innerText = texto;
    modal.classList.remove("hidden");
}

function fecharModal() {
    document.getElementById("modal").classList.add("hidden");
}

// MAPA
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
            abrirModal("Localização não encontrada");
        }
    });
}

function limparMapa() {
    if (!map) return;

    markers.forEach(m => map.removeLayer(m));
    markers = [];
}

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

function carregarEventos() {
    fetchEventos(false);
}

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
        <a href="${evento.url_compra}" target="_blank">🎟 Ingresso</a>

        <button onclick="irDetalhes(${evento.id_evento})">
            📄 Detalhes
        </button>

        <button onclick="comprarEvento(${evento.id_evento})">
            ✅ Já comprei
        </button>
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
// detalhes
function irDetalhes(id) {
    window.location.href = `detalhes_evento.php?id=${id}`;
}

// COMPRA
function comprarEvento(id_evento) {
    fetch(`../controllers/comprar_evento.php?id_evento=${id_evento}`)
        .then(res => res.text())
        .then(msg => {
            console.log("Resposta:", msg);

            if (!msg || msg.trim() === "") {
                msg = "⚠️ Sem resposta do servidor.";
            }

            abrirModal(msg);
        })
        .catch(() => {
            abrirModal("❌ Erro ao conectar com servidor.");
        });
}
</script>

</body>
</html>