<?php
session_start();
include("../config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    echo "Você precisa estar logado para fazer check-in!";
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_evento = $_GET['id_evento'];
$lat_user = $_GET['lat'];
$long_user = $_GET['long'];

// Verifica se já fez check-in
$sql_check = "SELECT * FROM checkin WHERE id_usuario = $id_usuario AND id_evento = $id_evento";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    echo "Você já fez check-in neste evento!";
    exit();
}

// Busca localização do evento
$sql_evento = "SELECT latitude_local, longitude_local FROM evento WHERE id_evento = $id_evento";
$result = $conn->query($sql_evento);

$evento = $result->fetch_assoc();

$lat_evento = $evento['latitude_local'];
$long_evento = $evento['longitude_local'];

// Calcula distância
$distancia = 6371 * acos(
    cos(deg2rad($lat_user)) *
    cos(deg2rad($lat_evento)) *
    cos(deg2rad($long_evento) - deg2rad($long_user)) +
    sin(deg2rad($lat_user)) *
    sin(deg2rad($lat_evento))
);

// Regra: máximo 500 metros
if ($distancia > 0.5) {
    echo "Você não está no local do evento! Distância: " . round($distancia * 1000) . " metros.";
    exit();
}
// avalia evento

// Salva check-in
$sql = "INSERT INTO checkin 
(id_usuario, id_evento, data_checkin, latitude_checkin, longitude_checkin)
VALUES 
($id_usuario, $id_evento, NOW(), $lat_user, $long_user)";

if ($conn->query($sql)) {
    echo "Check-in realizado com sucesso!";
} else {
    echo "Erro ao fazer o check-in: " . $conn->error;
}
?>