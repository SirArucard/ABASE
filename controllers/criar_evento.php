<?php
session_start();
include("../config/config.php");

if ($_SESSION['tipo_usuario'] != 'produtor') {
    header("Location: ../index.php");
    exit();
}

$id_produtor = $_SESSION['id_usuario'];

$nome = $_POST['nome'];
$data = $_POST['data_evento'];
$hora = $_POST['hora_inicio']; // 🔥 IMPORTANTE
$lat = $_POST['latitude'];
$long = $_POST['longitude'];
$url = $_POST['url_compra'] ?? null;
$detalhes = $_POST['detalhes'] ?? null;

$sql = "INSERT INTO evento 
(nome, data_evento, hora_inicio, latitude_local, longitude_local, url_compra, id_produtor, detalhes)
VALUES
('$nome', '$data', '$hora', '$lat', '$long', '$url', $id_produtor, '$detalhes')";

if ($conn->query($sql)) {
    header("Location: ../views/perfil.php");
} else {
    echo "Erro: " . $conn->error;
}